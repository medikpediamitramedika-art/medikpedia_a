<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('medicines_grosir')) {
            Schema::create('medicines_grosir', function (Blueprint $table) {
                $table->id();
                $table->string('nama_obat');
                $table->string('kategori')->nullable();
                $table->double('harga')->default(0);
                $table->integer('stok')->default(0);
                $table->text('deskripsi')->nullable();
                $table->boolean('is_resep')->default(false);
                $table->timestamps();
            });
        }
    }
};
