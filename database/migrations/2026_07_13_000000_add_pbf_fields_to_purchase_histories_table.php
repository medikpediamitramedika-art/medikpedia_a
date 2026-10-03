<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_histories', 'no_izin_pbf')) {
                $table->string('no_izin_pbf')->nullable()->after('sipa');
            }
            if (!Schema::hasColumn('purchase_histories', 'apj')) {
                $table->string('apj')->nullable()->after('no_izin_pbf');
            }
            if (!Schema::hasColumn('purchase_histories', 'sika')) {
                $table->string('sika')->nullable()->after('apj');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_histories', 'sika')) {
                $table->dropColumn('sika');
            }
            if (Schema::hasColumn('purchase_histories', 'apj')) {
                $table->dropColumn('apj');
            }
            if (Schema::hasColumn('purchase_histories', 'no_izin_pbf')) {
                $table->dropColumn('no_izin_pbf');
            }
        });
    }
};
