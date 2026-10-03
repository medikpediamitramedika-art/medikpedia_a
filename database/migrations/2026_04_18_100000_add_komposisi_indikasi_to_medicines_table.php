<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'komposisi')) {
                $table->text('komposisi')->nullable()->after('kategori_produk');
            }
            if (!Schema::hasColumn('medicines', 'indikasi')) {
                $table->text('indikasi')->nullable()->after('komposisi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['komposisi', 'indikasi']);
        });
    }
};
