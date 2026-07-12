<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Banner;
use App\Models\PromoProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    // Halaman utama
    public function index(Request $request)
    {
        // Produk unggulan grade A — hanya non-PBF
        $featuredProducts = Medicine::where('stok', '>', 0)
                                    ->whereRaw('UPPER(grade) = ?', ['A'])
                                    ->nonPbf()
                                    ->latest()
                                    ->limit(20)
                                    ->get();

        // Semua produk yang tersedia untuk section "Semua Produk"
        $allProducts = Medicine::where('stok', '>', 0)
                                ->nonPbf()
                                ->orderBy('nama_obat')
                                ->get();

        $totalProducts = Medicine::nonPbf()->count();

        // Hitung total produk per kategori produk
        $categoryColumn = Schema::hasColumn('medicines', 'kategori_produk') ? 'kategori_produk' : 'kategori';

        $kategoryCounts = Medicine::selectRaw($categoryColumn . ', COUNT(*) as total')
                                  ->nonPbf()
                                  ->whereNotNull($categoryColumn)
                                  ->groupBy($categoryColumn)
                                  ->pluck('total', $categoryColumn);

        // Banner promo aktif
        $banners = Schema::hasTable('banners') ? Banner::aktif()->get() : collect();

        // Promo produk aktif
        $promoProducts = Schema::hasTable('promo_products') ? PromoProduct::aktif()->get() : collect();

        return view('home', compact('featuredProducts', 'allProducts', 'kategoryCounts', 'totalProducts', 'banners', 'promoProducts'));
    }

    // Halaman Tentang Kami
    public function about()
    {
        return view('about');
    }

    // Halaman Kontak
    public function contact()
    {
        return view('contact');
    }

    // Detail obat
    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);

        // Related products dari kategori yang sama
        $relatedMedicines = Medicine::where('kategori', $medicine->kategori)
                                    ->where('id', '!=', $medicine->id)
                                    ->limit(4)
                                    ->get();

        return view('medicines.detail', [
            'medicine' => $medicine,
            'relatedMedicines' => $relatedMedicines,
        ]);
    }

    // Kategori
    public function byCategory($kategori)
    {
        // Hanya tampilkan produk OTC di halaman kategori publik
        $medicines = Medicine::where('kategori', $kategori)
                            ->where('is_resep', false)
                            ->paginate(12);
        $allCategories = Medicine::where('is_resep', false)
                                ->distinct()
                                ->pluck('kategori');

        return view('medicines.category', [
            'medicines' => $medicines,
            'kategori' => $kategori,
            'allCategories' => $allCategories,
        ]);
    }

    // Farmakologi
    public function farmakologi()
    {
        $diseases = [
            [
                'name' => 'Demam & Flu',
                'icon' => 'fa-solid fa-temperature-high',
                'symptoms' => [
                    ['gejala' => 'Demam tinggi', 'komposisi' => 'Paracetamol 500mg', 'obat' => 'Panadol / Sanmol / Tempra', 'dosis' => '3x1 tablet/hari'],
                    ['gejala' => 'Hidung tersumbat', 'komposisi' => 'Pseudoephedrine / Phenylephrine', 'obat' => 'Decolgen / Rhinos / Neozep', 'dosis' => '3x1 tablet/hari'],
                    ['gejala' => 'Batuk berdahak', 'komposisi' => 'Guaifenesin / Bromhexine', 'obat' => 'OBH Combi / Bisolvon / Mucosolvan', 'dosis' => '3x1 sendok/hari'],
                    ['gejala' => 'Sakit tenggorokan', 'komposisi' => 'Benzydamine / Povidone Iodine', 'obat' => 'Tantum Verde / Betadine Gargle', 'dosis' => 'Kumur 3x/hari'],
                ],
            ],
            [
                'name' => 'Hipertensi',
                'icon' => 'fa-solid fa-heart-pulse',
                'symptoms' => [
                    ['gejala' => 'Tekanan darah tinggi', 'komposisi' => 'Amlodipine 5-10mg', 'obat' => 'Norvasc / Tensivask / Amlodipine', 'dosis' => '1x1 tablet/hari'],
                    ['gejala' => 'Sakit kepala', 'komposisi' => 'Captopril 12.5-25mg', 'obat' => 'Capoten / Tensicap', 'dosis' => '2x1 tablet/hari'],
                    ['gejala' => 'Pusing berputar', 'komposisi' => 'Betahistine 8-16mg', 'obat' => 'Betaserc / Merislon', 'dosis' => '3x1 tablet/hari'],
                ],
            ],
            [
                'name' => 'Diabetes',
                'icon' => 'fa-solid fa-droplet',
                'symptoms' => [
                    ['gejala' => 'Gula darah tinggi', 'komposisi' => 'Metformin 500-850mg', 'obat' => 'Glucophage / Diabex / Metformin', 'dosis' => '2-3x1 tablet/hari'],
                    ['gejala' => 'Sering haus & lapar', 'komposisi' => 'Glibenclamide 2.5-5mg', 'obat' => 'Daonil / Euglucon', 'dosis' => '1x1 tablet/hari'],
                    ['gejala' => 'Luka sulit sembuh', 'komposisi' => 'Insulin / Glipizide', 'obat' => 'Glucotrol / Minidiab', 'dosis' => 'Sesuai anjuran dokter'],
                ],
            ],
            [
                'name' => 'Gangguan Pencernaan',
                'icon' => 'fa-solid fa-stomach',
                'symptoms' => [
                    ['gejala' => 'Maag / Nyeri lambung', 'komposisi' => 'Omeprazole 20mg / Antasida', 'obat' => 'Omeprazole / Promag / Mylanta', 'dosis' => '1x1 kapsul/hari'],
                    ['gejala' => 'Mual & muntah', 'komposisi' => 'Domperidone 10mg / Metoclopramide', 'obat' => 'Vometa / Primperan / Domperidone', 'dosis' => '3x1 tablet/hari'],
                    ['gejala' => 'Diare', 'komposisi' => 'Loperamide 2mg / Attapulgite', 'obat' => 'Imodium / Diapet / New Diatabs', 'dosis' => '2 tablet awal, lanjut 1 tablet'],
                    ['gejala' => 'Sembelit', 'komposisi' => 'Bisacodyl 5mg / Lactulose', 'obat' => 'Dulcolax / Laxadine / Lactulax', 'dosis' => '1-2 tablet malam hari'],
                ],
            ],
            [
                'name' => 'Infeksi & Antibiotik',
                'icon' => 'fa-solid fa-bacteria',
                'symptoms' => [
                    ['gejala' => 'Infeksi bakteri umum', 'komposisi' => 'Amoxicillin 500mg', 'obat' => 'Amoxicillin / Amoxsan / Intermoxil', 'dosis' => '3x1 kapsul/hari (5-7 hari)'],
                    ['gejala' => 'Infeksi saluran kemih', 'komposisi' => 'Ciprofloxacin 500mg', 'obat' => 'Ciprofloxacin / Baquinor / Ciproxin', 'dosis' => '2x1 tablet/hari (3-7 hari)'],
                    ['gejala' => 'Infeksi kulit', 'komposisi' => 'Cefadroxil 500mg', 'obat' => 'Cefadroxil / Droxyl / Longcef', 'dosis' => '2x1 kapsul/hari (5-7 hari)'],
                ],
            ],
            [
                'name' => 'Nyeri & Peradangan',
                'icon' => 'fa-solid fa-bone',
                'symptoms' => [
                    ['gejala' => 'Nyeri sendi / otot', 'komposisi' => 'Ibuprofen 400mg / Naproxen', 'obat' => 'Ibuprofen / Advil / Ponstan', 'dosis' => '3x1 tablet/hari (sesudah makan)'],
                    ['gejala' => 'Asam urat', 'komposisi' => 'Allopurinol 100-300mg', 'obat' => 'Allopurinol / Zyloric / Puricemia', 'dosis' => '1x1 tablet/hari'],
                    ['gejala' => 'Rematik', 'komposisi' => 'Meloxicam 7.5-15mg', 'obat' => 'Mobic / Meloxicam / Artrilox', 'dosis' => '1x1 tablet/hari'],
                ],
            ],
            [
                'name' => 'Alergi & Kulit',
                'icon' => 'fa-solid fa-hand-dots',
                'symptoms' => [
                    ['gejala' => 'Gatal-gatal / urtikaria', 'komposisi' => 'Cetirizine 10mg / Loratadine', 'obat' => 'Zyrtec / Claritin / Cetirizine', 'dosis' => '1x1 tablet/hari'],
                    ['gejala' => 'Ruam kulit', 'komposisi' => 'Hydrocortisone 1% / Betamethasone', 'obat' => 'Hydrocortisone cream / Betason', 'dosis' => 'Oleskan 2x/hari'],
                    ['gejala' => 'Jerawat', 'komposisi' => 'Benzoyl Peroxide / Clindamycin', 'obat' => 'Benzolac / Dalacin T / Acnecide', 'dosis' => 'Oleskan 1-2x/hari'],
                ],
            ],
            [
                'name' => 'Gangguan Pernapasan',
                'icon' => 'fa-solid fa-lungs',
                'symptoms' => [
                    ['gejala' => 'Asma / sesak napas', 'komposisi' => 'Salbutamol 2-4mg / Terbutaline', 'obat' => 'Ventolin / Bricasma / Salbutamol', 'dosis' => '3x1 tablet/hari atau inhaler'],
                    ['gejala' => 'Batuk kering', 'komposisi' => 'Dextromethorphan 15mg', 'obat' => 'Woods / Siladex / Dextromethorphan', 'dosis' => '3x1 sendok/hari'],
                    ['gejala' => 'Bronkitis', 'komposisi' => 'Ambroxol 30mg / Erdosteine', 'obat' => 'Mucosolvan / Erdotin / Ambroxol', 'dosis' => '3x1 tablet/hari'],
                ],
            ],
        ];

        return view('farmakologi', compact('diseases'));
    }
}

// Tambahan sementara - akan diganti dengan method yang benar
