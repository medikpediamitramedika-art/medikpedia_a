<?php

namespace Tests\Feature;

use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PurchaseHistoryTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_checkout_decrements_product_stock(): void
    {
        $medicine = Medicine::create([
            'nama_obat' => 'Paracetamol',
            'kategori' => 'KIMIA FARMA',
            'harga' => 15000,
            'stok' => 10,
            'deskripsi' => 'Pereda demam dan nyeri',
        ]);

        $response = $this->postJson('/orders/history', [
            'buyer_type' => 'umum',
            'buyer_name' => 'Budi',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 1',
            'items' => [
                ['id' => $medicine->id, 'quantity' => 3, 'harga' => 15000],
            ],
            'total' => 45000,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('medicines', [
            'id' => $medicine->id,
            'stok' => 7,
        ]);
    }

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
        $response->assertJsonStructure(['success', 'id', 'invoice_url']);
        $history = \App\Models\PurchaseHistory::findOrFail($response->json('id'));

        $this->assertSame('Apotik Sehat', $history->buyer_name);
        $this->assertSame(30000, $history->total);
        $this->assertSame('Paracetamol', $history->items[0]['name']);
        $this->assertSame(15000, $history->items[0]['price']);
    }

    public function test_purchase_history_keeps_item_price_and_calculates_discount(): void
    {
        $response = $this->postJson('/orders/history', [
            'buyer_type' => 'umum',
            'buyer_name' => 'Budi',
            'items' => [
                ['id' => null, 'nama_obat' => 'Vitamin C', 'quantity' => 2, 'harga' => 25000, 'potongan' => 5000],
            ],
            'total' => 45000,
        ]);

        $response->assertOk();
        $history = \App\Models\PurchaseHistory::findOrFail($response->json('id'));

        $this->assertSame(50000, $history->original_total);
        $this->assertSame(45000, $history->discounted_total);
        $this->assertSame(25000, $history->items[0]['harga']);
        $this->assertSame(5000, $history->items[0]['potongan']);
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

    public function test_purchase_history_can_store_doctor_buyer_details(): void
    {
        $response = $this->postJson('/orders/history', [
            'buyer_type' => 'dokter',
            'requester_name' => 'Admin Klinik',
            'outlet_name' => 'Klinik Sehat',
            'phone' => '081234567892',
            'address' => 'Jl. Dokter No. 5',
            'items' => [],
            'total' => 0,
        ]);

        $response->assertOk()->assertJsonStructure(['success', 'id', 'invoice_url']);
        $this->assertDatabaseHas('purchase_histories', [
            'buyer_name' => 'Admin Klinik',
            'requester_name' => 'Admin Klinik',
            'outlet_name' => 'Klinik Sehat',
            'buyer_type' => 'dokter',
        ]);
    }

    public function test_purchase_history_can_store_tempo_payment_with_custom_days_for_apotik_and_doctor(): void
    {
        $response = $this->postJson('/orders/history', [
            'buyer_type' => 'apotik',
            'requester_name' => 'Apt. Rina',
            'outlet_name' => 'Apotik Sejahtera',
            'phone' => '081234567893',
            'address' => 'Jl. Tempo No. 2',
            'payment_method' => 'Tempo',
            'payment_term_days' => 14,
            'items' => [
                ['name' => 'Paracetamol', 'qty' => 1, 'price' => 10000],
            ],
            'total' => 10000,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('purchase_histories', [
            'buyer_type' => 'apotik',
            'payment_method' => 'Tempo',
            'payment_term_days' => 14,
        ]);
    }
}
