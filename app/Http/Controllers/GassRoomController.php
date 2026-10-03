<?php

namespace App\Http\Controllers;

use App\Models\GassRoomFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GassRoomController extends Controller
{
    public function accessForm(Request $request): View
    {
        $request->session()->forget('gass_room_access');

        return view('gass.access');
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('gass_room_access')) {
            return redirect()->route('gass.access.form');
        }

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);
        $search = trim($validated['search'] ?? '');
        $files = GassRoomFile::query()
            ->when($search !== '', fn ($query) => $query->where('original_name', 'like', '%'.$search.'%'))
            ->latest()
            ->get();

        return view('gass.room', compact('files', 'search'));
    }

    public function access(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'access_code' => ['required', 'string'],
        ]);

        if (! hash_equals((string) config('gass.access_code'), $validated['access_code'])) {
            return redirect()->route('gass.access.form')
                ->withErrors(['access_code' => 'Kode akses tidak sesuai.']);
        }

        $request->session()->regenerate();
        $request->session()->put('gass_room_access', true);

        return redirect()->route('gass.room');
    }

    public function upload(Request $request): RedirectResponse
    {
        $this->ensureRoomAccess($request);

        $validated = $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file'],
        ]);

        foreach ($validated['files'] as $file) {
            $path = 'gass-room/'.Str::uuid();
            $roomFile = GassRoomFile::query()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'size_bytes' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            if ($file->storeAs('gass-room', basename($path), 'local') === false) {
                $roomFile->delete();
                Log::error('GASS room file could not be written to private storage.', [
                    'file_id' => $roomFile->id,
                ]);

                return back()->with('error', 'File gagal disimpan. File yang sudah berhasil diunggah tetap tersedia.');
            }
        }

        return redirect()->route('gass.room')->with('success', 'File berhasil diunggah ke Ruang GASS.');
    }

    public function download(Request $request, GassRoomFile $file)
    {
        $this->ensureRoomAccess($request);

        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return Storage::disk('local')->download($file->path, $file->original_name, [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
        ]);
    }

    public function viewFile(Request $request, GassRoomFile $file): View
    {
        $this->ensureRoomAccess($request);

        abort_unless(Storage::disk('local')->exists($file->path), 404);

        $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
        $previewType = match (true) {
            in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'bmp'], true) => 'image',
            in_array($extension, ['mp4', 'webm', 'ogv', 'mov'], true) => 'video',
            in_array($extension, ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'], true) => 'audio',
            $extension === 'pdf' => 'pdf',
            in_array($extension, ['txt', 'csv', 'log', 'md'], true) => 'text',
            in_array($extension, ['xls', 'xlsx'], true) => 'excel',
            $extension === 'docx' => 'word',
            default => 'unavailable',
        };

        return view('gass.preview', compact('file', 'previewType'));
    }

    public function fileContent(Request $request, GassRoomFile $file): BinaryFileResponse
    {
        $this->ensureRoomAccess($request);

        abort_unless(Storage::disk('local')->exists($file->path), 404);

        $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
        $contentTypes = [
            'aac' => 'audio/aac',
            'avif' => 'image/avif',
            'bmp' => 'image/bmp',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'flac' => 'audio/flac',
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'm4a' => 'audio/mp4',
            'md' => 'text/plain',
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4',
            'ogg' => 'audio/ogg',
            'ogv' => 'video/ogg',
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'txt' => 'text/plain',
            'wav' => 'audio/wav',
            'webm' => 'video/webm',
            'webp' => 'image/webp',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'log' => 'text/plain',
        ];

        return response()->file(Storage::disk('local')->path($file->path), [
            'Content-Type' => $contentTypes[$extension] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="preview"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function destroy(Request $request, GassRoomFile $file): RedirectResponse
    {
        $this->ensureRoomAccess($request);

        if (Storage::disk('local')->exists($file->path) && ! Storage::disk('local')->delete($file->path)) {
            Log::error('GASS room file could not be deleted from private storage.', [
                'file_id' => $file->id,
            ]);

            return redirect()->route('gass.room', array_filter([
                'search' => $request->input('search'),
            ]))->with('error', 'File gagal dihapus. Silakan coba lagi.');
        }

        $file->delete();

        return redirect()->route('gass.room', array_filter([
            'search' => $request->input('search'),
        ]))->with('success', 'File berhasil dihapus.');
    }

    public function leave(Request $request): RedirectResponse
    {
        $request->session()->forget('gass_room_access');

        return redirect()->route('gass.access.form')->with('success', 'Anda telah keluar dari Ruang GASS.');
    }

    private function ensureRoomAccess(Request $request): void
    {
        abort_unless($request->session()->get('gass_room_access'), 403);
    }
}
