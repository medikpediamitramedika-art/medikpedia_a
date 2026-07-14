<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class AdminPrescriptionController extends Controller
{
    private array $companies = Companies::LIST;

    // LIST
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Medicine::where('is_resep', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $medicines  = $query->latest()->paginate(10)->withQueryString();
        $categories = $this->companies;
        $total      = $query->count();

        return view('admin.grosir.index', compact(
            'medicines',
            'search',
            'kategori',
            'categories',
            'total'
        ));
    }

    // CREATE
    public function create()
    {
        return view('admin.grosir.create', [
            'categories' => $this->companies
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string'],
            'indikasi'  => ['required', 'string'],
            'golongan'  => ['required', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image'],
        ]);

        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];

        unset($validated['komposisi'], $validated['indikasi']);

        $validated['is_resep'] = true;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = ImageHelper::storeProductImage($request->file('gambar'));
        }

        Medicine::create($validated);

        return redirect()->route('admin.grosir.index')
            ->with('success', 'Produk resep berhasil ditambahkan!');
    }

    // EDIT
    public function edit(Medicine $prescription)
    {
        if (!$prescription->is_resep) abort(404);

        return view('admin.grosir.edit', [
            'medicine'   => $prescription,
            'categories' => $this->companies,
        ]);
    }

    // UPDATE
    public function update(Request $request, Medicine $prescription)
    {
        if (!$prescription->is_resep) abort(404);

        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string'],
            'indikasi'  => ['required', 'string'],
            'golongan'  => ['required', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image'],
        ]);

        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];

        unset($validated['komposisi'], $validated['indikasi']);

        $validated['is_resep'] = true;

        if ($request->hasFile('gambar')) {
            ImageHelper::deleteProductImage($prescription->gambar);
            $validated['gambar'] = ImageHelper::storeProductImage($request->file('gambar'));
        } elseif ($request->boolean('delete_gambar') && $prescription->gambar) {
            ImageHelper::deleteProductImage($prescription->gambar);
            $validated['gambar'] = null;
        }

        $prescription->update($validated);

        $queryParams = $this->buildIndexQueryParams($request);

        return redirect()->route('admin.prescriptions.index', $queryParams)
            ->with('success', 'Produk resep berhasil diupdate!');
    }

    // DELETE
    public function destroy(Request $request, Medicine $prescription)
    {
        if (!$prescription->is_resep) abort(404);
        ImageHelper::deleteProductImage($prescription->gambar);
        $prescription->delete();

        $queryParams = $this->buildIndexQueryParams($request);

        return redirect()->route('admin.prescriptions.index', $queryParams)
            ->with('success', 'Produk resep berhasil dihapus!');
    }

    private function buildIndexQueryParams(Request $request): array
    {
        $params = [];

        foreach (['search', 'kategori', 'page'] as $field) {
            $value = $request->query($field, $request->input($field));
            if ($value !== null && $value !== '') {
                $params[$field] = $value;
            }
        }

        return $params;
    }

    // UPDATE STOK
    public function updateStock(Request $request, Medicine $prescription)
    {
        if (!$prescription->is_resep) abort(404);

        $validated = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $prescription->update(['stok' => $validated['stok']]);

        return back()->with('success', 'Stok berhasil diupdate!');
    }

    // FORM IMPORT
    public function showImportForm()
    {
        return view('admin.grosir.import', [
            'categories' => $this->companies
        ]);
    }

    // IMPORT (FIX REDIRECT)
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120'],
        ]);

        return $this->importCsvFile($request->file('file'), true);
    }

    // CSV IMPORT (FIX REDIRECT)
    private function importCsvFile($file, bool $resepOnly = false)
    {
        $path    = $file->getRealPath();
        $content = file_get_contents($path);

        $lines = array_map('str_getcsv', explode("\n", $content));

        foreach ($lines as $i => $row) {
            if ($i === 0) continue;

            if (empty($row[1])) continue;

            Medicine::create([
                'nama_obat' => $row[1],
                'kategori'  => $row[0] ?? '',
                'harga'     => (float) $row[2],
                'stok'      => (int) ($row[3] ?? 0),
                'deskripsi' => ($row[4] ?? '') . ' | ' . ($row[5] ?? ''),
                'is_resep'  => true,
            ]);
        }

        return redirect()->route('admin.grosir.index')
            ->with('success', 'Import berhasil!');
    }
}