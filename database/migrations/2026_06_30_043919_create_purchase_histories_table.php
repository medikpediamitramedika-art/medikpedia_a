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
        if (Schema::hasTable("purchase_histories")) {
            return;
        }

        Schema::create("purchase_histories", function (Blueprint $table) {
            $table->id();
            $table->string("buyer_type")->default("umum");
            $table->string("buyer_name");
            $table->string("phone")->nullable();
            $table->text("address")->nullable();
            $table->string("kecamatan")->nullable();
            $table->string("kota")->nullable();
            $table->string("sia")->nullable();
            $table->string("sipa")->nullable();
            $table->string("no_izin_pbf")->nullable();
            $table->string("apj")->nullable();
            $table->string("sika")->nullable();
            $table->json("items")->nullable();
            $table->unsignedInteger("total")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("purchase_histories");
    }
};
