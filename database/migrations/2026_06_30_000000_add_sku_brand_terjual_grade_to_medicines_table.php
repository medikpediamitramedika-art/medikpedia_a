<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'sku')) {
                $table->string('sku')->nullable()->after('id');
            }
            if (!Schema::hasColumn('medicines', 'brand')) {
                $table->string('brand')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('medicines', 'terjual')) {
                $table->integer('terjual')->default(0)->after('stok');
            }
            if (!Schema::hasColumn('medicines', 'grade')) {
                $table->string('grade')->nullable()->after('terjual');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['sku', 'brand', 'terjual', 'grade']);
        });
    }
};
