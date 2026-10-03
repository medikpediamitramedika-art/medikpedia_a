<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Simpan gambar produk ke storage/medicines/
     *
     * Struktur folder hasil:
     *   public_html/storage/medicines/namafile.jpg   ← berjejer dengan app/, framework/, logs/
     *
     * URL akses: https://domain.com/storage/medicines/namafile.jpg
     * Nilai di DB: "medicines/namafile.jpg"
     */
    public static function storeProductImage(UploadedFile $file): string
    {
        $ext       = strtolower($file->getClientOriginalExtension());
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = storage_path('medicines');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $imageName);

        return 'medicines/' . $imageName;
    }

    /**
     * Hapus gambar produk dari storage/medicines/
     * $path dari DB: "medicines/namafile.jpg"
     */
    public static function deleteProductImage(?string $path): void
    {
        if (!$path) return;

        $fullPath = storage_path($path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    /**
     * Simpan gambar banner ke storage/banners/
     *
     * Struktur folder hasil:
     *   public_html/storage/banners/namafile.jpg   ← berjejer dengan app/, framework/, logs/
     *
     * URL akses: https://domain.com/storage/banners/namafile.jpg
     * Nilai di DB: "banners/namafile.jpg"
     */
    public static function storeBannerImage(UploadedFile $file): string
    {
        $ext       = strtolower($file->getClientOriginalExtension());
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = storage_path('banners');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $imageName);

        return 'banners/' . $imageName;
    }

    /**
     * Hapus gambar banner dari storage/banners/
     * $path dari DB: "banners/namafile.jpg"
     */
    public static function deleteBannerImage(?string $path): void
    {
        if (!$path) return;

        $fullPath = storage_path($path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    /**
     * Simpan gambar promo produk ke storage/promos/
     * Nilai di DB: "promos/namafile.jpg"
     */
    public static function storePromoImage(UploadedFile $file): string
    {
        $ext       = strtolower($file->getClientOriginalExtension());
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = storage_path('promos');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $imageName);

        return 'promos/' . $imageName;
    }

    /**
     * Hapus gambar promo produk dari storage/promos/
     * $path dari DB: "promos/namafile.jpg"
     */
    public static function deletePromoImage(?string $path): void
    {
        if (!$path) return;

        $fullPath = storage_path($path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
