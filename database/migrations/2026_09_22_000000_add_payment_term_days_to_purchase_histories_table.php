<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            $table->unsignedInteger('payment_term_days')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            $table->dropColumn('payment_term_days');
        });
    }
};
