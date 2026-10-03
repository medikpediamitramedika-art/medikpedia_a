<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_histories', 'requester_name')) {
                $table->string('requester_name')->nullable()->after('buyer_name');
            }
            if (!Schema::hasColumn('purchase_histories', 'outlet_name')) {
                $table->string('outlet_name')->nullable()->after('requester_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_histories', 'outlet_name')) {
                $table->dropColumn('outlet_name');
            }
            if (Schema::hasColumn('purchase_histories', 'requester_name')) {
                $table->dropColumn('requester_name');
            }
        });
    }
};