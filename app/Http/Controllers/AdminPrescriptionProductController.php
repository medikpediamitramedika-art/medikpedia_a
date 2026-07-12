<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class AdminPrescriptionProductController extends Controller
{
    private array $companies = Companies::LIST;

    // List produk resep
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Medicine::where('is_resep', true)->latest();

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

        $medicines  = $query->paginate(10)->withQueryString();
        $categories = Companies::LIST;

        return view('admin.prescriptions.products.index', compact('medicines', 'search', 'kategori', 'categories'));
    }

    // Form tambah produk resep
    public function create()
    {
        return view('admin.prescriptions.products.create', ['categories' => $this->companies]);
    }

    // Simpan produk resep baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string', 'max:255'],
            'indikasi'  => ['required', 'string', 'max:255'],
            'golongan'  => ['required', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
        ]);

        // Tentukan is_resep berdasarkan golongan
        $validated['is_resep'] = ($validated['golongan'] === 'KERAS');
        
        // Gabung komposisi dan indikasi untuk deskripsi
        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
        
        // Hapus field yang tidak perlu di database
        unset($validated['komposisi']);
        unset($validated['indikasi']);
        unset($validated['golongan']);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = ImageHelper::storeProductImage($request->file('gambar'));
        }

        Medicine::create($validated);

        return redirect()->route('admin.prescriptions.products.index')
                       ->with('success', 'Produk resep berhasil ditambahkan!');
    }

    // Form edit produk resep
    public function edit(Medicine $product)
    {
        if (!$product->is_resep) {
            abort(404);
        }

        return view('admin.prescriptions.products.edit', [
            'medicine'   => $product,
            'categories' => $this->companies,
        ]);
    }

    // Update produk resep
    public function update(Request $request, Medicine $product)
    {
        if (!$product->is_resep) {
            abort(404);
        }

        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string', 'max:255'],
            'indikasi'  => ['required', 'string', 'max:255'],
            'golongan'  => ['required', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            'delete_gambar' => ['nullable', 'boolean'],
        ]);

        // Tentukan is_resep berdasarkan golongan
        $validated['is_resep'] = ($validated['golongan'] === 'KERAS');
        
        // Gabung komposisi dan indikasi untuk deskripsi
        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
        
        // Hapus field yang tidak perlu di database
        unset($validated['komposisi']);
        unset($validated['indikasi']);
        unset($validated['golongan']);
        unset($validated['delete_gambar']);

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            ImageHelper::deleteProductImage($product->gambar);
            $validated['gambar'] = ImageHelper::storeProductImage($request->file('gambar'));
        } elseif ($request->input('delete_gambar') == '1' && $product->gambar) {
            ImageHelper::deleteProductImage($product->gambar);
            $validated['gambar'] = null;
        }

        $product->update($validated);

        return redirect()->route('admin.prescriptions.products.index')
                       ->with('success', 'Produk resep berhasil diupdate!');
    }

    // Hapus produk resep
    public function destroy(Medicine $product)
    {
        if (!$product->is_resep) {
            abort(404);
        }

        ImageHelper::deleteProductImage($product->gambar);
        $product->delete();

        return redirect()->route('admin.prescriptions.products.index')
                       ->with('success', 'Produk resep berhasil dihapus!');
    }

    // Update stok produk resep
    public function updateStock(Request $request, Medicine $product)
    {
        if (!$product->is_resep) {
            abort(404);
        }

        $validated = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $product->update(['stok' => $validated['stok']]);

        return back()->with('success', 'Stok produk resep berhasil diupdate!');
    }
}
