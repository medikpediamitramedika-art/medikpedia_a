<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gass_room_files', function (Blueprint $table) {
            $table->boolean('is_public')->default(false);
            $table->string('share_token', 64)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('gass_room_files', function (Blueprint $table) {
            $table->dropUnique(['share_token']);
            $table->dropColumn(['is_public', 'share_token']);
        });
    }
};