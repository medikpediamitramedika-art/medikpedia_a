<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $products = DB::table('medicines')->select('id', 'harga_modal', 'harga_grosir', 'harga_retail')->get();

        foreach ($products as $product) {
            $updates = [];
            foreach (['harga_modal', 'harga_grosir', 'harga_retail'] as $column) {
                $value = (float) $product->{$column};
                if ($value <= 1000000000) continue;

                $digits = preg_replace('/[^0-9]/', '', (string) $product->{$column});
                $updates[$column] = (int) substr($digits, 0, 5);
            }

            if ($updates) DB::table('medicines')->where('id', $product->id)->update($updates);
        }
    }

    public function down(): void
    {
        // Malformed values cannot be restored reliably after normalization.
    }
};
