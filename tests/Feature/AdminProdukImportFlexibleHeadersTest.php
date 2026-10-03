<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminProdukImportController;
use App\Http\Controllers\HomeController;
use App\Models\Medicine;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminProdukImportFlexibleHeadersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('nama_obat');
            $table->string('sediaan')->nullable();
            $table->string('kelompok')->nullable();
            $table->string('kategori')->nullable();
            $table->string('brand')->nullable();
            $table->string('kategori_produk')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->integer('terjual')->default(0);
            $table->string('grade')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('komposisi')->nullable();
            $table->text('indikasi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('medicines');
        parent::tearDown();
    }

    public function test_import_accepts_loose_headers_when_required_columns_are_present(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'import');
        file_put_contents($path, "SKU,Nama Produk,Brand,Harga,Stok,Terjual,Grade,Komposisi,Indikasi,Kategori\nSKU-999,Obat Uji,Brand Uji,12000,10,12,A,Paracetamol,Demam,PRODUK LENGKAP\n");

        $file = new UploadedFile($path, 'test.csv', 'text/csv', null, true);
        $request = new Request();
        $request->setMethod('POST');
        $request->files->set('file', $file);

        $response = (new AdminProdukImportController())->import($request);

        $this->assertTrue($response->isRedirect());
        $errors = $response->getSession()->get('errors');
        $this->assertTrue($errors === null || !$errors->any(), $errors ? $errors->first('file') : 'No errors');
        $this->assertDatabaseHas('medicines', [
            'sku' => 'SKU-999',
            'nama_obat' => 'Obat Uji',
            'brand' => 'Brand Uji',
            'terjual' => 12,
            'grade' => 'A',
        ]);

        @unlink($path);
    }

    public function test_homepage_featured_products_only_include_grade_a_records(): void
    {
        Medicine::create([
            'nama_obat' => 'Obat Grade A',
            'harga' => 12000,
            'stok' => 10,
            'grade' => 'A',
        ]);

        Medicine::create([
            'nama_obat' => 'Obat Grade B',
            'harga' => 15000,
            'stok' => 10,
            'grade' => 'B',
        ]);

        $view = (new HomeController())->index(new Request());
        $featuredProducts = $view->getData()['featuredProducts'];

        $this->assertCount(1, $featuredProducts);
        $this->assertSame('Obat Grade A', $featuredProducts->first()->nama_obat);
    }
}
