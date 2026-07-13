<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PurchaseHistoryTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_purchase_history_can_be_stored_via_public_endpoint(): void
    {
        $response = $this->postJson('/orders/history', [
            'buyer_type' => 'apotik',
            'buyer_name' => 'Apotik Sehat',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 1',
            'kecamatan' => 'Kemayoran',
            'kota' => 'Jakarta Pusat',
            'sia' => 'SIA-001',
            'sipa' => 'SIPA-001',
            'items' => [
                ['name' => 'Paracetamol', 'qty' => 2, 'price' => 15000],
            ],
            'total' => 30000,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('purchase_histories', [
            'buyer_name' => 'Apotik Sehat',
            'buyer_type' => 'apotik',
            'total' => 30000,
        ]);
    }

    public function test_purchase_history_can_store_pbf_buyer_type(): void
    {
        $response = $this->postJson('/orders/history', [
            'buyer_type' => 'pbf',
            'buyer_name' => 'PT Contoh PBF',
            'phone' => '081234567891',
            'address' => 'Jl. PBF No. 10',
            'kecamatan' => 'Ilir Timur II',
            'kota' => 'Palembang',
            'sia' => 'SIA-PBF-001',
            'sipa' => 'SIPA-PBF-001',
            'items' => [
                ['name' => 'Paracetamol', 'qty' => 1, 'price' => 10000],
            ],
            'total' => 10000,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('purchase_histories', [
            'buyer_name' => 'PT Contoh PBF',
            'buyer_type' => 'pbf',
            'total' => 10000,
        ]);
    }
}
