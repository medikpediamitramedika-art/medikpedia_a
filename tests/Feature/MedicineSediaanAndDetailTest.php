<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineSediaanAndDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_sediaan_for_produk(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $medicine = Medicine::create([
            'nama_obat' => 'Paracetamol',
            'kategori_produk' => 'Bintang',
            'kategori' => 'PT. Test',
            'harga' => 12000,
            'stok' => 10,
            'sediaan' => 'box',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.produk.update', $medicine), [
            'nama_obat' => 'Paracetamol 500mg',
            'kategori_produk' => 'Bintang',
            'kategori' => 'PT. Test',
            'kelompok' => 'APOTEK',
            'sku' => 'SKU-1',
            'brand' => 'Test Brand',
            'terjual' => 0,
            'grade' => 'A',
            'harga' => 15000,
            'stok' => 12,
            'deskripsi' => 'Deskripsi',
            'komposisi' => 'Paracetamol',
            'indikasi' => 'Demam',
            'sediaan' => 'fls',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('medicines', [
            'id' => $medicine->id,
            'sediaan' => 'fls',
        ]);
    }

    public function test_public_detail_page_shows_sediaan_information(): void
    {
        $medicine = Medicine::create([
            'nama_obat' => 'Vitamin C',
            'kategori' => 'PT. Test',
            'harga' => 20000,
            'stok' => 5,
            'sediaan' => 'box',
            'deskripsi' => 'Deskripsi produk',
        ]);

        $response = $this->get(route('medicines.show', $medicine));

        $response->assertOk();
        $response->assertSee('Sediaan');
        $response->assertSee('Box');
    }
}
