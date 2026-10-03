<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_histories', 'original_total')) {
                $table->unsignedInteger('original_total')->default(0)->after('total');
            }
            if (!Schema::hasColumn('purchase_histories', 'discounted_total')) {
                $table->unsignedInteger('discounted_total')->default(0)->after('original_total');
            }
            if (!Schema::hasColumn('purchase_histories', 'approval_status')) {
                $table->string('approval_status')->default('pending')->after('discounted_total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_histories', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
            if (Schema::hasColumn('purchase_histories', 'discounted_total')) {
                $table->dropColumn('discounted_total');
            }
            if (Schema::hasColumn('purchase_histories', 'original_total')) {
                $table->dropColumn('original_total');
            }
        });
    }
};
