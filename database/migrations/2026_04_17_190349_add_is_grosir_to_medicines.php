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
        if (Schema::hasColumn("medicines", "is_grosir")) {
            return;
        }

        Schema::table("medicines", function (Blueprint $table) {
            $table->boolean("is_grosir")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn("medicines", "is_grosir")) {
            return;
        }

        Schema::table("medicines", function (Blueprint $table) {
            $table->dropColumn("is_grosir");
        });
    }
};
