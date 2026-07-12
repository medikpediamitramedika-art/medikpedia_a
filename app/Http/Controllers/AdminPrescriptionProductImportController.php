<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;

class AdminPrescriptionProductImportController extends Controller
{
    private array $companies = Companies::LIST;

    /**
     * Download template XLSX — terbuka rapi di Excel tanpa warning
     */
    public function downloadTemplate()
    {
        $columns = ['PABRIK', 'NAMA PRODUK', 'SEDIAAN', 'RETAIL', 'KOMPOSISI', 'INDIKASI'];
        $widths  = [18, 30, 10, 12, 25, 30];

        $rows = [
            ['KALBE',     'Amoxicillin 500mg',   'fls', '15000', 'Amoxicillin 500 mg',   'Infeksi bakteri'],
            ['BERNOFARM', 'Ciprofloxacin 500mg', 'box', '25000', 'Ciprofloxacin 500 mg', 'Infeksi bakteri'],
            ['DEXA',      'Metformin 500mg',     '',    '12000', 'Metformin 500 mg',     'Diabetes tipe 2'],
        ];

        return \App\Helpers\XlsxWriter::download('template_import_produk_resep.xlsx', $columns, $rows, $widths);
    }

    /**
     * Tampilkan form import
     */
    public function showImportForm()
    {
        return view('admin.prescriptions.products.import', ['categories' => $this->companies]);
    }

    /**
     * Proses import file CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:2048'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Validasi ekstensi manual (lebih reliable dari MIME check)
        if (! in_array($extension, ['csv', 'xls', 'xlsx', 'txt'])) {
            return back()->withErrors(['file' => 'Format file harus CSV atau Excel (.xls/.xlsx). File yang diupload: .' . $extension]);
        }

        if (in_array($extension, ['xlsx', 'xls'])) {
            return $this->importExcel($file);
        }

        return $this->importCsv($file);
    }

    /**
     * Import dari file CSV
     */
    private function importCsv($file)
    {
        $path    = $file->getRealPath();
        $content = file_get_contents($path);

        // Hapus BOM jika ada
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        $lines = array_filter(explode("\n", $content), fn($l) => trim($l) !== '');
        $lines = array_values($lines);

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File CSV kosong atau hanya berisi header.']);
        }

        $header = $this->parseCsvLine($lines[0]);
        $header = array_map('trim', $header);

        $required = ['NAMA PRODUK', 'RETAIL'];
        $missing  = array_diff($required, $header);
        $hasPabrik   = in_array('PABRIK', $header);
        $hasKomposisi = in_array('KOMPOSISI', $header);
        $hasIndikasi = in_array('INDIKASI', $header);

        if (!empty($missing) || !$hasPabrik) {
            $allRequired = array_merge($required, ['PABRIK']);
            return back()->withErrors([
                'file' => 'Kolom tidak lengkap. Kolom yang kurang: ' . implode(', ', array_diff($allRequired, $header)),
            ]);
        }

        return $this->processRows($header, array_slice($lines, 1));
    }

    /**
     * Import dari file Excel (.xls XML SpreadsheetML atau .xlsx yang disimpan sebagai CSV)
     */
    private function importExcel($file)
    {
        $content = file_get_contents($file->getRealPath());

        // Kasus 1: File adalah Excel XML (SpreadsheetML) — format yang kita generate
        if (strpos($content, 'urn:schemas-microsoft-com:office:spreadsheet') !== false
            || strpos($content, '<Workbook') !== false) {
            return $this->importExcelXml($content);
        }

        // Kasus 2: File .xlsx (ZIP-based) — tidak bisa diparse tanpa ext-zip
        if (strpos($content, 'PK') === 0) {
            return back()->withErrors([
                'file' => 'Format .xlsx tidak didukung langsung. Silakan buka file di Excel → Save As → pilih "CSV (Comma delimited)" → upload file CSV tersebut.',
            ]);
        }

        // Kasus 3: File .xls yang sebenarnya berisi teks/CSV
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $lines   = array_filter(explode("\n", $content), fn($l) => trim($l) !== '');
        $lines   = array_values($lines);

        if (count($lines) >= 2) {
            $header = $this->parseCsvLine($lines[0]);
            $header = array_map('trim', $header);
            $required = ['nama_obat', 'kategori', 'retail', 'stok'];
            if (empty(array_diff($required, $header))) {
                return $this->processRows($header, array_slice($lines, 1));
            }
        }

        return back()->withErrors([
            'file' => 'Format file tidak dikenali. Gunakan template yang sudah disediakan dan simpan sebagai CSV sebelum upload.',
        ]);
    }

    /**
     * Parse Excel XML (SpreadsheetML) — format yang dihasilkan downloadTemplate()
     */
    private function importExcelXml(string $content): mixed
    {
        // Suppress XML errors, parse dengan libxml
        libxml_use_internal_errors(true);

        // Hapus namespace agar SimpleXML mudah parse
        $content = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $content);
        $content = preg_replace('/<\?mso-application[^?]*\?>/i', '', $content);

        $xml = simplexml_load_string($content);

        if ($xml === false) {
            return back()->withErrors(['file' => 'File Excel tidak bisa dibaca. Pastikan file tidak rusak.']);
        }

        // Cari worksheet pertama (sheet "Data Produk Resep")
        $worksheet = null;
        foreach ($xml->Worksheet as $ws) {
            $name = (string) $ws->attributes()['Name'] ?? '';
            // Ambil sheet pertama yang bukan "Petunjuk"
            if (strtolower($name) !== 'petunjuk') {
                $worksheet = $ws;
                break;
            }
        }

        if (!$worksheet || !isset($worksheet->Table)) {
            return back()->withErrors(['file' => 'Sheet "Data Produk Resep" tidak ditemukan di file Excel.']);
        }

        $rows = [];
        foreach ($worksheet->Table->Row as $row) {
            $rowData = [];
            foreach ($row->Cell as $cell) {
                $rowData[] = trim((string) $cell->Data);
            }
            $rows[] = $rowData;
        }

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File Excel kosong atau hanya berisi header.']);
        }

        // Baris pertama = header
        $header   = array_map('trim', $rows[0]);
        $required = ['NAMA PRODUK', 'RETAIL'];
        $hasPabrik   = in_array('PABRIK', $header);
        $hasKomposisi = in_array('KOMPOSISI', $header);
        $hasIndikasi = in_array('INDIKASI', $header);
        $missing  = array_diff($required, $header);

        if (!empty($missing) || !$hasPabrik) {
            return back()->withErrors([
                'file' => 'Kolom tidak lengkap. Kolom yang kurang: ' . implode(', ', array_merge($missing, !$hasPabrik ? ['PABRIK'] : [])),
            ]);
        }

        // Konversi array rows ke format string lines untuk processRows
        $dataRows = array_slice($rows, 1);
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($dataRows as $lineNum => $row) {
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $data = array_combine($header, $row);
            $data = array_map('trim', $data);

            if (empty($data['nama_obat'])) {
                $skipped++;
                continue;
            }

            $rowErrors = $this->validateRow($data, $lineNum + 2);
            if (!empty($rowErrors)) {
                $errors  = array_merge($errors, $rowErrors);
                $skipped++;
                continue;
            }

            Medicine::create([
                'nama_obat' => $data['nama_obat'],
                'kategori'  => $data['pabrik'] ?? $data['kategori'] ?? '',
                'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['retail']),
                'stok'      => (int) preg_replace('/[^0-9]/', '', $data['stok']),
                'deskripsi' => $data['komposisi'] ?? '',
                'is_resep'  => true,
            ]);

            $imported++;
        }

        if ($imported === 0 && !empty($errors)) {
            return back()->withErrors(['file' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $message = "Berhasil mengimpor {$imported} produk resep.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('admin.prescriptions.products.index')->with('success', $message);
    }

    /**
     * Proses baris data dan simpan ke database
     */
    private function processRows(array $header, array $dataLines)
    {
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($dataLines as $lineNum => $line) {
            $row = $this->parseCsvLine($line);

            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $data = array_combine($header, $row);
            $data = array_map('trim', $data);

            // Skip baris kosong
            if (empty($data['NAMA PRODUK'])) {
                $skipped++;
                continue;
            }

            // Validasi per baris
            $rowErrors = $this->validateRow($data, $lineNum + 2);
            if (!empty($rowErrors)) {
                $errors = array_merge($errors, $rowErrors);
                $skipped++;
                continue;
            }

            // Sediaan: fls atau box
            $sediaan = null;
            if (isset($data['SEDIAAN']) && !empty($data['SEDIAAN'])) {
                $sediaan = strtolower(trim($data['SEDIAAN']));
                if (!in_array($sediaan, ['fls', 'box'])) {
                    $sediaan = null;
                }
            }

            Medicine::create([
                'nama_obat' => $data['NAMA PRODUK'],
                'sediaan'   => $sediaan,
                'kategori'  => $data['PABRIK'] ?? '',
                'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['RETAIL']),
                'stok'      => 0,
                'deskripsi' => ($data['KOMPOSISI'] ?? '') . ' | ' . ($data['INDIKASI'] ?? ''),
                'is_resep'  => true,
            ]);

            $imported++;
        }

        if ($imported === 0 && !empty($errors)) {
            return back()->withErrors(['file' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $message = "Berhasil mengimpor {$imported} produk resep.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('admin.prescriptions.products.index')->with('success', $message);
    }

    /**
     * Validasi satu baris data
     */
    private function validateRow(array $data, int $lineNum): array
    {
        $errors = [];

        if (empty($data['PABRIK'])) {
            $errors[] = "Baris {$lineNum}: PABRIK kosong.";
        }

        if (empty($data['KOMPOSISI'])) {
            $errors[] = "Baris {$lineNum}: KOMPOSISI kosong.";
        }

        if (empty($data['INDIKASI'])) {
            $errors[] = "Baris {$lineNum}: INDIKASI kosong.";
        }

        if (!is_numeric(preg_replace('/[^0-9.]/', '', $data['RETAIL'] ?? ''))) {
            $errors[] = "Baris {$lineNum}: RETAIL tidak valid ({$data['RETAIL']}).";
        }

        return $errors;
    }

    /**
     * Parse satu baris CSV dengan benar (handle quoted fields)
     */
    private function parseCsvLine(string $line): array
    {
        $line = rtrim($line, "\r\n");
        $result = str_getcsv($line, ',', '"');
        return $result;
    }
}
