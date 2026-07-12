<?php

namespace App\Http\Controllers;

use App\Models\MedicineGrosir;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPrescriptionImportController extends Controller
{
    private array $companies = Companies::LIST;

    public function showImportForm()
    {
        return view('admin.prescriptions.import', [
            'categories' => $this->companies
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:2048'],
        ]);

        return $this->importCsv($request->file('file'));
    }

    /**
     * =========================
     * IMPORT CSV FIX FINAL
     * =========================
     */
    private function importCsv($file)
    {
        $content = file_get_contents($file->getRealPath());

        // HANDLE UTF-16 (Excel)
        if (mb_detect_encoding($content, ['UTF-16', 'UTF-16LE', 'UTF-16BE'], true)) {
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16');
        }

        // CLEAN
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $lines = array_values(array_filter(explode("\n", $content)));

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File kosong']);
        }

        // ================= HEADER =================
        $rawHeader = explode(';', $lines[0]);

        // 🔥 NORMALISASI HEADER
        $header = array_map(function ($h) {
            $h = trim($h);
            $h = preg_replace('/[^\x20-\x7E]/', '', $h);
            $h = preg_replace('/\s+/', ' ', $h);
            return strtoupper($h);
        }, $rawHeader);

        // 🔥 MAPPING INDEX HEADER (INI KUNCI)
        $map = [
            'PABRIK' => null,
            'NAMA PRODUK' => null,
            'RETAIL' => null,
            'KOMPOSISI' => null,
            'INDIKASI' => null,
        ];

        foreach ($header as $i => $h) {
            foreach ($map as $key => $val) {
                if (str_contains($h, $key)) {
                    $map[$key] = $i;
                }
            }
        }

        // VALIDASI HEADER
        if (in_array(null, $map, true)) {
            return back()->withErrors([
                'file' => 'Header tidak cocok. Terbaca: ' . implode(' | ', $header)
            ]);
        }

        return $this->processRows($map, array_slice($lines, 1));
    }

    /**
     * =========================
     * PROCESS ROWS
     * =========================
     */
    private function processRows($map, $lines)
    {
        $imported = 0;
        $skipped  = 0;

        DB::beginTransaction();

        try {

            foreach ($lines as $i => $line) {

                $cols = explode(';', $line);

                // ambil berdasarkan posisi header
                $data = [
                    'PABRIK'       => $cols[$map['PABRIK']] ?? '',
                    'NAMA PRODUK'  => $cols[$map['NAMA PRODUK']] ?? '',
                    'RETAIL'       => $cols[$map['RETAIL']] ?? '',
                    'KOMPOSISI'    => $cols[$map['KOMPOSISI']] ?? '',
                    'INDIKASI'     => $cols[$map['INDIKASI']] ?? '',
                ];

                $data = array_map(function ($v) {
                    $v = trim($v);
                    $v = preg_replace('/[^\x20-\x7E]/', '', $v);
                    return $v;
                }, $data);

                if (empty($data['NAMA PRODUK'])) {
                    $skipped++;
                    continue;
                }

                MedicineGrosir::updateOrCreate(
    ['nama_obat' => $data['NAMA PRODUK']],
    [
        'kategori'  => $data['PABRIK'],
        'harga'     => $this->parseHarga($data['RETAIL']),
        'stok'      => (int) $data['STOK'],
        'deskripsi' => ($data['KOMPOSISI'] ?? '') . ' | ' . ($data['INDIKASI'] ?? ''),
        'is_resep'  => false,
    ]

                );

                $imported++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'file' => 'Error: ' . $e->getMessage()
            ]);
        }

        return redirect()->route('admin.prescriptions.index')
            ->with('success', "Import berhasil: {$imported}, skip: {$skipped}");
    }

    /**
     * =========================
     * PARSE HARGA
     * =========================
     */
    private function parseHarga($value)
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