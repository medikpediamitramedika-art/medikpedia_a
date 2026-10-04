<?php

namespace App\Http\Controllers;

use App\Models\GassRoomFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'uploaded_on' => ['nullable', 'date_format:Y-m-d'],
            'sort' => ['nullable', 'in:newest,name_asc,size_asc,size_desc'],
        ]);
        $search = trim($validated['search'] ?? '');
        $uploadedOn = $validated['uploaded_on'] ?? '';
        $sort = $validated['sort'] ?? 'newest';
        $query = GassRoomFile::query()
            ->when($search !== '', fn ($query) => $query->where('original_name', 'like', '%'.$search.'%'));

        if ($uploadedOn !== '') {
            $localDay = Carbon::createFromFormat('!Y-m-d', $uploadedOn, config('gass.timezone'));
            $startOfDay = $localDay->copy()->startOfDay()->setTimezone(config('app.timezone'));
            $endOfDay = $localDay->copy()->endOfDay()->setTimezone(config('app.timezone'));
            $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }

        if ($sort === 'name_asc') {
            $query->orderByRaw('LOWER(original_name) ASC')->orderByDesc('created_at');
        } elseif ($sort === 'size_asc') {
            $query->orderBy('size_bytes')->orderBy('original_name');
        } elseif ($sort === 'size_desc') {
            $query->orderByDesc('size_bytes')->orderBy('original_name');
        } else {
            $query->latest();
        }

        $files = $query->get();

        return view('gass.room', compact('files', 'search', 'uploadedOn', 'sort'));
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
            'files' => ['required', 'array', 'min:1', 'max:5'],
            'files.*' => ['required', 'file'],
        ], [
            'files.max' => 'Maksimal 5 file dapat diunggah sekaligus.',
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

    public function toggleSharing(Request $request, GassRoomFile $file): RedirectResponse|JsonResponse
    {
        $this->ensureRoomAccess($request);

        if ($file->is_public) {
            $file->forceFill(['is_public' => false, 'share_token' => null])->save();

            if ($request->expectsJson()) {
                return response()->json(['is_public' => false]);
            }

            return back()->with('success', 'Berbagi publik untuk file ini telah dinonaktifkan.');
        }

        $file->forceFill(['is_public' => true, 'share_token' => Str::random(64)])->save();

        if ($request->expectsJson()) {
            return response()->json([
                'is_public' => true,
                'url' => route('gass.files.shared.view', $file->share_token),
            ]);
        }

        return back()->with('success', 'File ini sekarang dapat dibuka melalui tautan publik.');
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

        return $this->preview($file, false);
    }

    public function sharedView(string $token): View
    {
        $file = $this->findSharedFile($token);
        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return $this->preview($file, true);
    }

    public function sharedContent(string $token): BinaryFileResponse
    {
        $file = $this->findSharedFile($token);
        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return $this->contentResponse($file);
    }

    public function sharedDownload(string $token)
    {
        $file = $this->findSharedFile($token);
        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return Storage::disk('local')->download($file->path, $file->original_name, [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
        ]);
    }

    private function preview(GassRoomFile $file, bool $shared): View
    {
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

        $contentUrl = $shared
            ? route('gass.files.shared.content', $file->share_token)
            : route('gass.files.content', $file);
        $downloadUrl = $shared
            ? route('gass.files.shared.download', $file->share_token)
            : route('gass.files.download', $file);

        return view('gass.preview', compact('file', 'previewType', 'shared', 'contentUrl', 'downloadUrl'));
    }

    public function fileContent(Request $request, GassRoomFile $file): BinaryFileResponse
    {
        $this->ensureRoomAccess($request);

        abort_unless(Storage::disk('local')->exists($file->path), 404);

        return $this->contentResponse($file);
    }

    private function contentResponse(GassRoomFile $file): BinaryFileResponse
    {
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

    private function findSharedFile(string $token): GassRoomFile
    {
        return GassRoomFile::query()
            ->where('share_token', $token)
            ->where('is_public', true)
            ->firstOrFail();
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
                'uploaded_on' => $request->input('uploaded_on'),
                'sort' => $request->input('sort'),
            ]))->with('error', 'File gagal dihapus. Silakan coba lagi.');
        }

        $file->delete();

        return redirect()->route('gass.room', array_filter([
            'search' => $request->input('search'),
            'uploaded_on' => $request->input('uploaded_on'),
            'sort' => $request->input('sort'),
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
