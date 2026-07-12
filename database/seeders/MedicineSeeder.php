<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'nama_obat' => 'Paracetamol 500mg',
                'kategori' => 'Analgesik',
                'harga' => 5000,
                'stok' => 100,
                'deskripsi' => 'Obat untuk mengatasi demam dan nyeri ringan. Cocok untuk anak-anak dan dewasa.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Ibuprofen 400mg',
                'kategori' => 'Antiinflamasi',
                'harga' => 8000,
                'stok' => 75,
                'deskripsi' => 'Obat anti inflamasi untuk mengurangi peradangan dan nyeri pada otot dan sendi.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Amoksisilin 500mg',
                'kategori' => 'Antibiotik',
                'harga' => 12000,
                'stok' => 50,
                'deskripsi' => 'Antibiotik spektrum luas untuk infeksi bakteri. Harus dengan resep dokter.',
                'is_resep' => true,
            ],
            [
                'nama_obat' => 'Vitamin C 1000mg',
                'kategori' => 'Vitamin',
                'harga' => 25000,
                'stok' => 200,
                'deskripsi' => 'Suplemen vitamin C untuk meningkatkan imunitas tubuh dan menjaga kesehatan.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Omeprazole 20mg',
                'kategori' => 'Analgesik',
                'harga' => 15000,
                'stok' => 60,
                'deskripsi' => 'Obat untuk mengatasi asam lambung dan perlindungan lapisan lambung.',
                'is_resep' => true,
            ],
            [
                'nama_obat' => 'Loratadine 10mg',
                'kategori' => 'Antiinflamasi',
                'harga' => 10000,
                'stok' => 80,
                'deskripsi' => 'Obat anti alergi yang efektif untuk mengatasi gejala alergi ringan hingga sedang.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Dextromethorphan Syrup',
                'kategori' => 'Obat Batuk',
                'harga' => 20000,
                'stok' => 40,
                'deskripsi' => 'Sirup untuk mengatasi batuk kering dan ringan. Aman untuk anak-anak di atas 2 tahun.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Fluimucil Forte',
                'kategori' => 'Obat Batuk',
                'harga' => 35000,
                'stok' => 30,
                'deskripsi' => 'Obat untuk mengencerkan dahak dan memudahkan pengeluaran. Untuk batuk berdahak.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Vitamin B Complex',
                'kategori' => 'Vitamin',
                'harga' => 30000,
                'stok' => 150,
                'deskripsi' => 'Suplemen vitamin B kompleks untuk kesehatan saraf dan energi tubuh.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Zinc Tablet 20mg',
                'kategori' => 'Suplemen',
                'harga' => 22000,
                'stok' => 120,
                'deskripsi' => 'Suplemen mineral untuk meningkatkan imunitas dan kesehatan.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Vitamin D3 1000IU',
                'kategori' => 'Vitamin',
                'harga' => 28000,
                'stok' => 100,
                'deskripsi' => 'Suplemen vitamin D untuk kesehatan tulang dan penyerapan kalsium.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Tempra Anak Syrup',
                'kategori' => 'Vitamin Anak',
                'harga' => 18000,
                'stok' => 60,
                'deskripsi' => 'Obat aman untuk menurunkan demam pada anak. Dosis sesuai umur anak.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Cefixime 200mg',
                'kategori' => 'Antibiotik',
                'harga' => 25000,
                'stok' => 45,
                'deskripsi' => 'Antibiotik untuk berbagai jenis infeksi bakteri. Gunakan sesuai resep dokter.',
                'is_resep' => true,
            ],
            [
                'nama_obat' => 'Betadine Gargle',
                'kategori' => 'Lainnya',
                'harga' => 15000,
                'stok' => 70,
                'deskripsi' => 'Obat kumur untuk mengatasi infeksi tenggorokan dan gigi.',
                'is_resep' => false,
            ],
            [
                'nama_obat' => 'Minyak Kayu Putih',
                'kategori' => 'Lainnya',
                'harga' => 12000,
                'stok' => 200,
                'deskripsi' => 'Minyak tradisional untuk mengatasi pegal linu dan masuk angin.',
                'is_resep' => false,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
