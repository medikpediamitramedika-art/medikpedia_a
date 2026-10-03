<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite tidak support ALTER COLUMN, jadi kita patch via raw SQL
        // Patch semua record yang punya deskripsi kosong/null
        DB::statement("UPDATE medicines SET deskripsi = nama_obat WHERE deskripsi IS NULL OR deskripsi = '' OR deskripsi = ' | '");

        // Untuk SQLite: buat ulang tabel dengan deskripsi nullable tidak bisa langsung,
        // tapi kita set default agar tidak constraint fail
        // Laravel SQLite workaround: gunakan doctrineSchemaManager tidak tersedia di semua env,
        // cukup patch data saja — deskripsi tetap NOT NULL tapi data sudah bersih
    }

    public function down(): void
    {
        // nothing to reverse
    }
};
