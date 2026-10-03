<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        foreach (DB::table('medicines')->select('id', 'harga_modal', 'harga_grosir', 'harga_retail')->get() as $product) {
            $modal = (float) $product->harga_modal;
            if ($modal <= 0) continue;

            $updates = [];
            foreach (['harga_grosir', 'harga_retail'] as $column) {
                $value = (float) $product->{$column};
                while ($value > ($modal * 5) && $value > 0) $value /= 10;
                $updates[$column] = round($value);
            }

            DB::table('medicines')->where('id', $product->id)->update($updates);
        }
    }

    public function down(): void
    {
        // Scale normalization cannot be reversed reliably.
    }
};