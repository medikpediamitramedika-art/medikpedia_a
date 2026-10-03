<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

class ImportMedicines extends Command
{
    protected $signature   = 'medicines:import {file : Path ke file CSV}
                                               {--fresh : Hapus semua data lama sebelum import}
                                               {--skip-duplicate : Lewati baris yang nama_obat-nya sudah ada}';

    protected $description = 'Import data obat dari file CSV';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (! file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        if ($this->option('fresh')) {
            if ($this->confirm('Hapus semua data medicines yang ada?', false)) {
                DB::table('medicines')->truncate();
                $this->info('Data lama dihapus.');
            }
        }

        $this->info("Membaca file: {$filePath}");

        $rows      = $this->parseCsv($filePath);
        $total     = count($rows);
        $imported  = 0;
        $skipped   = 0;
        $errors    = 0;

        $this->info("Total baris ditemukan: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($rows as $i => $row) {
            $bar->advance();

            // Lewati header atau baris kosong
            if (empty($row[0]) || strtolower(trim($row[0])) === 'nama_obat') {
                $skipped++;
                continue;
            }

            try {
                $namaObat  = $this->cleanText($row[0] ?? '');
                $perusahaan = $this->cleanText($row[1] ?? 'UMUM');
                $harga     = $this->cleanHarga($row[2] ?? 0);
                $stok      = (int) ($row[3] ?? 0);
                $deskripsi = $this->cleanText($row[4] ?? '-');

                if (empty($namaObat)) {
                    $skipped++;
                    continue;
                }

                // Skip duplicate jika opsi aktif
                if ($this->option('skip-duplicate')) {
                    if (Medicine::where('nama_obat', $namaObat)->exists()) {
                        $skipped++;
                        continue;
                    }
                }

                Medicine::create([
                    'nama_obat'  => $namaObat,
                    'kategori'   => empty($perusahaan) ? 'UMUM' : $perusahaan,
                    'harga'      => $harga,
                    'stok'       => $stok > 0 ? $stok : 1000,
                    'deskripsi'  => empty($deskripsi) ? '-' : $deskripsi,
                    'gambar'     => null,
                ]);

                $imported++;

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->warn("Baris " . ($i + 2) . " error: " . $e->getMessage() . " | Data: " . implode(',', array_slice($row, 0, 3)));
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil diimport', $imported],
                ['⏭  Dilewati',         $skipped],
                ['❌ Error',             $errors],
                ['📦 Total baris',       $total],
            ]
        );

        $this->info("Import selesai!");
        return 0;
    }

    /**
     * Parse CSV dengan menangani:
     * - Field yang mengandung newline di dalam quotes
     * - Koma di dalam quotes
     * - Encoding UTF-8 / ANSI
     */
    private function parseCsv(string $filePath): array
    {
        // Baca seluruh file
        $content = file_get_contents($filePath);

        // Deteksi dan konversi encoding
        $encoding = mb_detect_encoding($content, ['UTF-8', 'Windows-1252', 'ISO-8859-1'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        // Hapus BOM jika ada
        $content = ltrim($content, "\xEF\xBB\xBF");

        // Normalisasi line endings
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        // Tulis ke file temp agar fgetcsv bisa membacanya
        $tmpFile = tempnam(sys_get_temp_dir(), 'csv_import_');
        file_put_contents($tmpFile, $content);

        $rows   = [];
        $handle = fopen($tmpFile, 'r');

        if ($handle === false) {
            $this->error('Tidak bisa membuka file.');
            return [];
        }

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            // Bersihkan setiap cell dari whitespace berlebih
            $row = array_map(fn($cell) => trim($cell), $row);
            $rows[] = $row;
        }

        fclose($handle);
        unlink($tmpFile);

        // Hapus baris header (baris pertama)
        if (! empty($rows) && strtolower($rows[0][0] ?? '') === 'nama_obat') {
            array_shift($rows);
        }

        return $rows;
    }

    private function cleanText(string $text): string
    {
        // Hapus newline di dalam teks
        $text = str_replace(["\n", "\r"], ' ', $text);
        // Hapus spasi berlebih
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function cleanHarga(string $harga): float
    {
        // Hapus semua karakter selain angka dan titik
        $harga = preg_replace('/[^0-9.]/', '', $harga);
        return (float) $harga;
    }
}
