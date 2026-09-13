<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_histories', 'pbf_name')) {
                $table->string('pbf_name')->nullable()->after('outlet_name');
            }
            if (!Schema::hasColumn('purchase_histories', 'pbf_address')) {
                $table->text('pbf_address')->nullable()->after('pbf_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_histories', 'pbf_address')) $table->dropColumn('pbf_address');
            if (Schema::hasColumn('purchase_histories', 'pbf_name')) $table->dropColumn('pbf_name');
        });
    }
};