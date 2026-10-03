<?php

namespace App\Http\Controllers;

use App\Models\PromoProduct;
use App\Helpers\ImageHelper;
use App\Rules\LandscapeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminPromoProductController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('promo_products')) {
            return view('admin.promo_products.index', ['promos' => collect()])
                ->with('warning', 'Tabel promo_products belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $promos = PromoProduct::orderBy('urutan')->orderBy('id')->get();
        return view('admin.promo_products.index', compact('promos'));
    }

    public function create()
    {
        $tableReady = Schema::hasTable('promo_products');
        return view('admin.promo_products.create', compact('tableReady'));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('promo_products')) {
            return redirect()->route('admin.promo-products.index')
                ->with('warning', 'Tabel promo_products belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'subjudul'     => ['nullable', 'string', 'max:255'],
            'gambar'       => ['required', 'image', 'mimes:jpeg,jpg,png,webp', new LandscapeImage],
            'url_tujuan'   => ['nullable', 'string', 'max:255'],
            'label_tombol' => ['nullable', 'string', 'max:50'],
            'urutan'       => ['nullable', 'integer', 'min:0'],
            'aktif'        => ['nullable'],
        ]);

        $data['gambar']       = ImageHelper::storePromoImage($request->file('gambar'));
        $data['aktif']        = $request->boolean('aktif', true);
        $data['label_tombol'] = $data['label_tombol'] ?? 'Lihat Sekarang';
        $data['urutan']       = $data['urutan'] ?? 0;

        PromoProduct::create($data);

        return redirect()->route('admin.promo-products.index')
            ->with('success', 'Promo produk berhasil ditambahkan!');
    }

    public function edit(PromoProduct $promoProduct)
    {
        if (!Schema::hasTable('promo_products')) {
            return redirect()->route('admin.promo-products.index')
                ->with('warning', 'Tabel promo_products belum tersedia.');
        }

        return view('admin.promo_products.edit', compact('promoProduct'));
    }

    public function update(Request $request, PromoProduct $promoProduct)
    {
        if (!Schema::hasTable('promo_products')) {
            return redirect()->route('admin.promo-products.index')
                ->with('warning', 'Tabel promo_products belum tersedia.');
        }

        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'subjudul'     => ['nullable', 'string', 'max:255'],
            'gambar'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', new LandscapeImage],
            'url_tujuan'   => ['nullable', 'string', 'max:255'],
            'label_tombol' => ['nullable', 'string', 'max:50'],
            'urutan'       => ['nullable', 'integer', 'min:0'],
            'aktif'        => ['nullable'],
        ]);

        if ($request->hasFile('gambar')) {
            ImageHelper::deletePromoImage($promoProduct->gambar);
            $data['gambar'] = ImageHelper::storePromoImage($request->file('gambar'));
        } else {
            unset($data['gambar']);
        }

        $data['aktif']        = $request->boolean('aktif', false);
        $data['label_tombol'] = $data['label_tombol'] ?? 'Lihat Sekarang';
        $data['urutan']       = $data['urutan'] ?? 0;

        $promoProduct->update($data);

        return redirect()->route('admin.promo-products.index')
            ->with('success', 'Promo produk berhasil diperbarui!');
    }

    public function destroy(PromoProduct $promoProduct)
    {
        if (!Schema::hasTable('promo_products')) {
            return redirect()->route('admin.promo-products.index')
                ->with('warning', 'Tabel promo_products belum tersedia.');
        }

        ImageHelper::deletePromoImage($promoProduct->gambar);
        $promoProduct->delete();

        return redirect()->route('admin.promo-products.index')
            ->with('success', 'Promo produk berhasil dihapus!');
    }

    /**
     * Toggle aktif/nonaktif via AJAX
     */
    public function toggleAktif(PromoProduct $promoProduct)
    {
        if (!Schema::hasTable('promo_products')) {
            return response()->json(['aktif' => false, 'message' => 'Tabel promo_products belum tersedia.'], 409);
        }

        $promoProduct->update(['aktif' => !$promoProduct->aktif]);
        return response()->json(['aktif' => $promoProduct->aktif]);
    }
}
