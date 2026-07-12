<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'kategori_produk')) {
                $table->string('kategori_produk')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('medicines', 'is_resep')) {
                $table->boolean('is_resep')->default(false)->after('indikasi');
            }
            if (!Schema::hasColumn('medicines', 'is_grosir')) {
                $table->boolean('is_grosir')->default(false)->after('is_resep');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumnIfExists('kategori_produk');
        });
    }
};
