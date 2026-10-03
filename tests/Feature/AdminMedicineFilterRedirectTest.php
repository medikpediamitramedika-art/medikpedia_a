<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminMedicineController;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminMedicineFilterRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_redirects_back_to_filtered_index_with_query_parameters(): void
    {
        $medicine = Medicine::create([
            'nama_obat' => 'Paracetamol',
            'kategori' => 'ABC',
            'harga' => 10000,
            'stok' => 10,
            'deskripsi' => 'Obat demam',
            'is_resep' => false,
            'is_grosir' => false,
        ]);

        $request = Request::create('/admin/medicines/' . $medicine->id, 'PUT', [
            'nama_obat' => 'Paracetamol',
            'kategori' => 'ABC',
            'harga' => 10000,
            'stok' => 10,
            'komposisi' => 'Paracetamol',
            'indikasi' => 'Demam',
            'golongan' => 'BEBAS',
            'search' => 'para',
            'tipe' => 'biasa',
            'page' => 3,
        ]);

        $response = (new AdminMedicineController())->update($request, $medicine);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(
            route('admin.medicines.index', ['search' => 'para', 'kategori' => 'ABC', 'tipe' => 'biasa', 'page' => 3]),
            $response->getTargetUrl()
        );
    }
}
