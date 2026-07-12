<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProdukImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.produk.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'xlsx') {
            return $this->importXlsx($file);
        }

        // CSV dan XLS (text-based)
        return $this->importCsv($file);
    }

    public function downloadTemplate()
    {
        $columns = ['SKU', 'PABRIK', 'BRAND', 'NAMA PRODUK', 'SEDIAAN', 'DESKRIPSI', 'HARGA', 'STOK', 'TERJUAL', 'GRADE', 'KOMPOSISI', 'INDIKASI', 'KELOMPOK', 'KATEGORI'];
        $widths  = [12, 18, 18, 30, 10, 35, 12, 8, 10, 8, 25, 30, 12, 22];

        $rows = [
            ['SKU-001', 'KIMIA FARMA', 'KIMIA FARMA',  'Paracetamol 500mg',    'fls', 'Obat pereda demam dan nyeri ringan.',                        '5000',   '100', '20', 'A', 'Paracetamol 500 mg',  'Demam & nyeri',                'PBF',    'OBAT'],
            ['SKU-002', 'WARDAH',      'WARDAH',        'Pelembab Wajah SPF30', 'box', 'Pelembab wajah untuk kelembapan dan perlindungan SPF30.',    '85000',  '50',  '12', 'B', 'Aqua, Glycerin, SPF', 'Melembabkan & melindungi kulit', 'APOTEK', 'SKINCARE & KOSMETIK'],
            ['SKU-003', 'OMRON',       'OMRON',         'Tensimeter Digital',   '',    'Tensimeter digital portabel, akurat untuk pemakaian rumah.', '350000', '20',  '5',  'A', '-',                   'Mengukur tekanan darah',        'PBF',    'ALAT KESEHATAN'],
        ];

        return \App\Helpers\XlsxWriter::download('template_produk.xlsx', $columns, $rows, $widths);
    }

    // ─── XLSX Parser (pure PHP, no ZipArchive needed) ────────────────────────

    private function importXlsx($file)
    {
        $path = $file->getRealPath();

        // XLSX adalah ZIP — coba ZipArchive dulu, fallback ke manual binary extract
        if (class_exists('ZipArchive')) {
            return $this->importXlsxViaZip($path);
        }

        // Fallback: extract ZIP secara manual menggunakan PharData (built-in PHP)
        return $this->importXlsxViaPhar($path);
    }

    private function importXlsxViaZip(string $path)
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return back()->withErrors(['file' => 'File XLSX tidak dapat dibuka.']);
        }

        $sharedStrings = $this->parseSharedStrings($zip->getFromName('xl/sharedStrings.xml') ?: '');
        $sheetXml      = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return back()->withErrors(['file' => 'Sheet tidak ditemukan dalam file XLSX.']);
        }

        return $this->processRows($this->parseSheetXml($sheetXml, $sharedStrings));
    }

    private function importXlsxViaPhar(string $path)
    {
        // Salin ke file .zip sementara agar PharData bisa baca
        $tmpZip = sys_get_temp_dir() . '/' . uniqid('xlsx_') . '.zip';
        copy($path, $tmpZip);

        try {
            $phar = new \PharData($tmpZip);

            $ssContent = '';
            if (isset($phar['xl/sharedStrings.xml'])) {
                $ssContent = file_get_contents($phar['xl/sharedStrings.xml']->getPathname());
            }

            if (!isset($phar['xl/worksheets/sheet1.xml'])) {
                return back()->withErrors(['file' => 'Sheet tidak ditemukan dalam file XLSX.']);
            }

            $sheetXml      = file_get_contents($phar['xl/worksheets/sheet1.xml']->getPathname());
            $sharedStrings = $this->parseSharedStrings($ssContent);

            return $this->processRows($this->parseSheetXml($sheetXml, $sharedStrings));
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca XLSX: ' . $e->getMessage()
                . ' — Coba simpan ulang file sebagai CSV dari Excel.']);
        } finally {
            @unlink($tmpZip);
        }
    }

    private function parseSharedStrings(string $xml): array
    {
        $sharedStrings = [];
        if (empty($xml)) return $sharedStrings;

        // Hapus namespace agar simplexml tidak gagal parse
        $xml = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $xml);
        $ss  = @simplexml_load_string($xml);
        if (!$ss) return $sharedStrings;

        foreach ($ss->si as $si) {
            $text = '';
            foreach ($si->r as $r) {
                $text .= (string) $r->t;
            }
            if (empty($text)) {
                $text = (string) $si->t;
            }
            $sharedStrings[] = trim($text);
        }

        return $sharedStrings;
    }

    private function parseSheetXml(string $sheetXml, array $sharedStrings): array
    {
        // Hapus namespace agar simplexml tidak gagal parse
        $sheetXml = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $sheetXml);
        $sheet    = @simplexml_load_string($sheetXml);
        if (!$sheet) return [];

        $rows = [];
        foreach ($sheet->sheetData->row as $row) {
            $rowData    = [];
            $lastColIdx = -1;

            foreach ($row->c as $cell) {
                // Hitung index kolom dari atribut r (A=0, B=1, AA=26, dst)
                $cellRef = (string) $cell['r'];
                preg_match('/^([A-Z]+)/', $cellRef, $colMatch);
                $colLetters = $colMatch[1] ?? 'A';
                $colIdx     = 0;
                foreach (str_split($colLetters) as $ch) {
                    $colIdx = $colIdx * 26 + (ord($ch) - ord('A') + 1);
                }
                $colIdx--; // 0-based

                // Isi kolom yang dilewati dengan string kosong (sparse cells)
                while ($lastColIdx < $colIdx - 1) {
                    $rowData[] = '';
                    $lastColIdx++;
                }

                $type  = (string) $cell['t'];
                $value = (string) $cell->v;

                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) $cell->is->t;
                }

                $rowData[]  = trim($value);
                $lastColIdx = $colIdx;
            }

            if (!empty(array_filter($rowData))) {
                $rows[] = $rowData;
            }
        }

        return $rows;
    }

    // ─── CSV / XLS (text-based) Parser ───────────────────────────────────────

    private function importCsv($file)
    {
        $content = file_get_contents($file->getRealPath());

        if (mb_detect_encoding($content, ['UTF-16', 'UTF-16LE', 'UTF-16BE'], true)) {
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16');
        }

        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $lines   = array_values(array_filter(explode("\n", $content)));

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File kosong atau tidak valid.']);
        }

        $rows = [];
        foreach ($lines as $line) {
            $row    = preg_split('/[;,\t]/', $line);
            $row    = array_map(fn($v) => trim(preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $v)), $row);
            $rows[] = $row;
        }

        return $this->processRows($rows);
    }

    // ─── Shared processing logic ──────────────────────────────────────────────

    private function processRows(array $rows)
    {
        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File kosong atau tidak valid.']);
        }

        // Cari baris header — baris pertama yang mengandung kolom nama produk yang dikenal
        $headerIdx = 0;
        foreach ($rows as $i => $row) {
            $normalized = array_map(
                fn($h) => $this->normalizeHeader($h),
                $row
            );
            if (in_array('NAMAPRODUK', $normalized) || in_array('NAMA', $normalized) || in_array('PRODUK', $normalized)) {
                $headerIdx = $i;
                break;
            }
        }

        $header = $rows[$headerIdx] ?? [];
        $headerKeys = array_map(fn($h) => $this->resolveHeaderKey($h), $header);

        $availableKeys = array_filter($headerKeys);
        if (!in_array('NAMA_PRODUK', $availableKeys, true)) {
            return back()->withErrors([
                'file' => 'Header tidak cocok. Kolom nama produk wajib ada (contoh: Nama Produk, Nama, Produk). Ditemukan: ' . implode(', ', array_filter($header)),
            ]);
        }

        $imported      = 0;
        $skipped       = 0;
        $validKategori = Companies::LIST;

        DB::beginTransaction();
        try {
            foreach (array_slice($rows, $headerIdx + 1) as $row) {
                $row  = array_pad($row, count($header), '');
                $row  = array_slice($row, 0, count($header));
                $data = [];
                foreach ($headerKeys as $index => $headerKey) {
                    $data[$headerKey] = $row[$index] ?? '';
                }

                $namaProduk = trim((string) ($data['NAMA_PRODUK'] ?? ''));
                if (empty($namaProduk)) {
                    $skipped++;
                    continue;
                }

                $katRaw    = strtoupper(trim((string) ($data['KATEGORI'] ?? '')));
                $katProduk = in_array($katRaw, $validKategori) ? $katRaw : 'OBAT';

                $hargaRaw = $data['HARGA'] ?? '0';
                $sku      = trim((string) ($data['SKU'] ?? ''));
                $brand    = trim((string) $this->getValue($data, ['BRAND', 'PABRIK', 'MERK']));
                $terjual  = isset($data['TERJUAL']) ? (int) preg_replace('/[^0-9]/', '', (string) $data['TERJUAL']) : 0;
                $gradeRaw = trim((string) ($data['GRADE'] ?? ''));
                $grade    = '';
                if ($gradeRaw !== '') {
                    $grade = strtoupper($gradeRaw);
                    $grade = preg_replace('/^GRADE\s*/i', '', $grade) ?? $grade;
                    $grade = trim($grade);
                }

                // Sediaan
                $sediaan = null;
                if (isset($data['SEDIAAN']) && !empty($data['SEDIAAN'])) {
                    $sediaan = strtolower(trim($data['SEDIAAN']));
                    if (!in_array($sediaan, ['fls', 'box'])) {
                        $sediaan = null;
                    }
                }

                // Kelompok: PBF atau APOTEK — harus diketahui sebelum menentukan $match
                $kelompok = null;
                if (isset($data['KELOMPOK']) && !empty($data['KELOMPOK'])) {
                    $k = strtoupper(trim($data['KELOMPOK']));
                    if (in_array($k, ['PBF', 'APOTEK'])) {
                        $kelompok = $k;
                    }
                }

                // Match key: sertakan kelompok agar PBF dan APOTEK tersimpan sebagai record terpisah
                if (!empty($sku)) {
                    $match = ['sku' => $sku, 'kelompok' => $kelompok ?? ''];
                } else {
                    $match = ['nama_obat' => $namaProduk, 'kelompok' => $kelompok ?? ''];
                }

                // Susun deskripsi dari DESKRIPSI, KOMPOSISI, INDIKASI — fallback ke nama produk
                $deskripsiBagian = array_filter(array_map('trim', [
                    $data['DESKRIPSI']  ?? '',
                    $data['KOMPOSISI']  ?? '',
                    $data['INDIKASI']   ?? '',
                ]));
                $deskripsiValue = !empty($deskripsiBagian)
                    ? implode(' | ', $deskripsiBagian)
                    : $namaProduk;

                Medicine::updateOrCreate(
                    $match,
                    [
                        'sku'              => $sku ?: null,
                        'nama_obat'        => $namaProduk,
                        'sediaan'          => $sediaan,
                        'kelompok'         => $kelompok,
                        'kategori'         => $data['PABRIK'] ?? ($data['BRAND'] ?? ''),
                        'brand'            => $brand ?: null,
                        'kategori_produk'  => $katProduk,
                        'harga'            => $this->parseHarga($hargaRaw),
                        'stok'             => isset($data['STOK']) ? (int) preg_replace('/[^0-9]/', '', (string) $data['STOK']) : 0,
                        'terjual'          => $terjual,
                        'grade'            => $grade ?: null,
                        'deskripsi'        => $deskripsiValue,
                        'komposisi'        => ($data['KOMPOSISI'] ?? '') ?: null,
                        'indikasi'         => ($data['INDIKASI'] ?? '') ?: null,
                    ]
                );
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Error saat menyimpan data: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.produk.index')
            ->with('success', "Import berhasil: {$imported} produk ditambahkan/diperbarui, {$skipped} baris dilewati.");
    }

    private function resolveHeaderKey(string $header): string
    {
        $normalized = $this->normalizeHeader($header);

        $aliases = [
            'SKU' => ['SKU', 'KODEPRODUK', 'KODE', 'PRODUCTCODE'],
            'NAMA_PRODUK' => ['NAMAPRODUK', 'NAMA', 'NAMABARANG', 'PRODUK', 'PRODUCTNAME'],
            'PABRIK' => ['PABRIK', 'MERK', 'MEREK', 'BRAND', 'PRODUCER', 'MANUFACTURER'],
            'BRAND' => ['BRAND', 'MERK', 'MEREK', 'PABRIK', 'PRODUCER', 'MANUFACTURER'],
            'HARGA' => ['HARGA', 'RETAIL', 'PRICE'],
            'STOK' => ['STOK', 'STOCK', 'STOCKQTY', 'QTY', 'JUMLAH'],
            'TERJUAL' => ['TERJUAL', 'SALES', 'TERJUALSALES', 'TOTALTERJUAL'],
            'GRADE' => ['GRADE', 'KELAS', 'CLASS'],
            'DESKRIPSI' => ['DESKRIPSI', 'DESCRIPTION'],
            'KOMPOSISI' => ['KOMPOSISI', 'COMPOSITION'],
            'INDIKASI' => ['INDIKASI', 'INDICATION', 'MANFAAT'],
            'KATEGORI' => ['KATEGORI', 'KATEGORIPRODUK', 'CATEGORY', 'TIPE', 'JENIS'],
            'SEDIAAN' => ['SEDIAAN', 'KEMASAN', 'PACKAGING'],
            'KELOMPOK' => ['KELOMPOK', 'GROUP', 'GRUP'],
        ];

        foreach ($aliases as $key => $values) {
            if (in_array($normalized, $values, true)) {
                return $key;
            }
        }

        return $normalized;
    }

    private function normalizeHeader($header): string
    {
        $value = strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', (string) $header)));
        return preg_replace('/[^A-Z0-9]+/', '', $value);
    }

    private function getValue(array $data, array $keys): string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $data) && $data[$key] !== null && $data[$key] !== '') {
                return (string) $data[$key];
            }
        }

        return '';
    }

    private function parseHarga($value): float
    {
        if (!$value) return 0;
        $value = str_replace(['Rp', 'rp', ' '], '', $value);
        if (str_contains($value, ',')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = str_replace('.', '', $value);
        }
        return (float) $value;
    }
}
