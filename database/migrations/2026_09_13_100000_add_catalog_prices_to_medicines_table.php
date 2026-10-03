<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'harga_modal')) {
                $table->decimal('harga_modal', 12, 2)->default(0)->after('harga');
            }
            if (!Schema::hasColumn('medicines', 'harga_grosir')) {
                $table->decimal('harga_grosir', 12, 2)->default(0)->after('harga_modal');
            }
            if (!Schema::hasColumn('medicines', 'harga_retail')) {
                $table->decimal('harga_retail', 12, 2)->default(0)->after('harga_grosir');
            }
        });

        DB::statement('UPDATE medicines SET harga_retail = harga WHERE harga_retail = 0');
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            foreach (['harga_retail', 'harga_grosir', 'harga_modal'] as $column) {
                if (Schema::hasColumn('medicines', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};