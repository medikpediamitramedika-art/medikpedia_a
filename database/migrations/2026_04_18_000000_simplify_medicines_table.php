<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kosongkan semua data produk lama
        DB::table('medicines')->truncate();

        // Hapus tabel medicines_grosir jika ada
        Schema::dropIfExists('medicines_grosir');

        Schema::table('medicines', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('medicines', 'is_resep')) {
                $table->dropColumn('is_resep');
            }
            if (Schema::hasColumn('medicines', 'is_grosir')) {
                $table->dropColumn('is_grosir');
            }

            // Tambah kolom kategori_produk
            if (!Schema::hasColumn('medicines', 'kategori_produk')) {
                $table->string('kategori_produk')->default('OBAT')->after('kategori');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (Schema::hasColumn('medicines', 'kategori_produk')) {
                $table->dropColumn('kategori_produk');
            }
            $table->boolean('is_resep')->default(false);
            $table->boolean('is_grosir')->default(false);
        });
    }
};
