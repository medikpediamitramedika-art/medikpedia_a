<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Helpers\ImageHelper;
use App\Rules\LandscapeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminBannerController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable("banners")) {
            return view("admin.banners.index", ["banners" => collect()])->with(
                "warning",
                "Tabel banner belum tersedia di database. Jalankan migrasi untuk mengaktifkan fitur banner.",
            );
        }

        $banners = Banner::orderBy("urutan")->orderBy("id")->get();
        return view("admin.banners.index", compact("banners"));
    }

    public function create()
    {
        $bannerTableReady = Schema::hasTable("banners");
        return view("admin.banners.create", compact("bannerTableReady"));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable("banners")) {
            return redirect()
                ->route("admin.banners.index")
                ->with("warning", "Tabel banner belum tersedia di database. Banner tidak dapat disimpan sebelum migrasi dijalankan.");
        }

        $data = $request->validate([
            "judul"        => ["required", "string", "max:255"],
            "subjudul"     => ["nullable", "string", "max:255"],
            "gambar"       => ["required", "image", "mimes:jpeg,jpg,png,webp", new LandscapeImage],
            "url_tujuan"   => ["nullable", "string", "max:255"],
            "label_tombol" => ["nullable", "string", "max:50"],
            "urutan"       => ["nullable", "integer", "min:0"],
            "aktif"        => ["nullable"],
        ]);

        $data["gambar"]       = ImageHelper::storeBannerImage($request->file("gambar"));
        $data["aktif"]        = $request->boolean("aktif", true);
        $data["label_tombol"] = $data["label_tombol"] ?? "Lihat Sekarang";
        $data["urutan"]       = $data["urutan"] ?? 0;

        Banner::create($data);

        return redirect()->route("admin.banners.index")->with("success", "Banner berhasil ditambahkan!");
    }

    public function edit(Banner $banner)
    {
        if (!Schema::hasTable("banners")) {
            return redirect()->route("admin.banners.index")->with("warning", "Tabel banner belum tersedia di database.");
        }

        return view("admin.banners.edit", compact("banner"));
    }

    public function update(Request $request, Banner $banner)
    {
        if (!Schema::hasTable("banners")) {
            return redirect()
                ->route("admin.banners.index")
                ->with("warning", "Tabel banner belum tersedia di database. Banner tidak dapat diperbarui.");
        }

        $data = $request->validate([
            "judul"        => ["required", "string", "max:255"],
            "subjudul"     => ["nullable", "string", "max:255"],
            "gambar"       => ["nullable", "image", "mimes:jpeg,jpg,png,webp", new LandscapeImage],
            "url_tujuan"   => ["nullable", "string", "max:255"],
            "label_tombol" => ["nullable", "string", "max:50"],
            "urutan"       => ["nullable", "integer", "min:0"],
            "aktif"        => ["nullable"],
        ]);

        if ($request->hasFile("gambar")) {
            ImageHelper::deleteBannerImage($banner->gambar);
            $data["gambar"] = ImageHelper::storeBannerImage($request->file("gambar"));
        } else {
            unset($data["gambar"]);
        }

        $data["aktif"]        = $request->boolean("aktif", false);
        $data["label_tombol"] = $data["label_tombol"] ?? "Lihat Sekarang";
        $data["urutan"]       = $data["urutan"] ?? 0;

        $banner->update($data);

        return redirect()->route("admin.banners.index")->with("success", "Banner berhasil diperbarui!");
    }

    public function destroy(Banner $banner)
    {
        if (!Schema::hasTable("banners")) {
            return redirect()
                ->route("admin.banners.index")
                ->with("warning", "Tabel banner belum tersedia di database. Banner tidak dapat dihapus.");
        }

        ImageHelper::deleteBannerImage($banner->gambar);
        $banner->delete();

        return redirect()->route("admin.banners.index")->with("success", "Banner berhasil dihapus!");
    }

    /**
     * Toggle aktif/nonaktif via AJAX
     */
    public function toggleAktif(Banner $banner)
    {
        if (!Schema::hasTable("banners")) {
            return response()->json(["aktif" => false, "message" => "Tabel banner belum tersedia di database."], 409);
        }

        $banner->update(["aktif" => !$banner->aktif]);
        return response()->json(["aktif" => $banner->aktif]);
    }
}
