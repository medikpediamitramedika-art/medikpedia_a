# Feature: Kolom SEDIAAN

## Summary
Telah ditambahkan kolom baru **SEDIAAN** di tabel medicines untuk menyimpan informasi jenis kemasan produk.

## Nilai Sediaan
- **fls** - FLS (Fles/Botol)
- **box** - BOX (Kotak)
- **null/empty** - Tidak ada sediaan (opsional)

## Changes Made

### 1. Database
**Migration**: `2026_07_03_085826_add_sediaan_to_medicines_table.php`
```php
Schema::table('medicines', function (Blueprint $table) {
    $table->string('sediaan')->nullable()->after('nama_obat');
});
```

**Executed**: ✅ Migration sudah dijalankan

### 2. Model
**File**: `app/Models/Medicine.php`
- Added `'sediaan'` to `$fillable` array

### 3. Admin Forms

#### Create Form
**File**: `resources/views/admin/medicines/create.blade.php`
- Added dropdown select untuk SEDIAAN setelah Nama Obat
- Options: FLS, BOX, atau kosong

#### Edit Form  
**File**: `resources/views/admin/medicines/edit.blade.php`
- Added dropdown select untuk SEDIAAN
- Pre-selected value dari database

### 4. Index/List View
**File**: `resources/views/admin/medicines/index.blade.php`
- Added kolom **SEDIAAN** setelah kolom **Nama Obat**
- Display: Badge biru dengan uppercase text (FLS/BOX)
- Empty state: Tampil tanda "-"

### 5. Import Feature
**File**: `app/Http/Controllers/AdminMedicineImportController.php`

**Template Excel Updated**:
- Kolom baru: `SEDIAAN` (posisi ke-3 setelah NAMA PRODUK)
- Width: 10
- Contoh value: `fls`, `box`, atau kosong

**Import Logic**:
- Validasi: hanya menerima `fls` atau `box` (case-insensitive)
- Jika nilai lain atau kosong, akan di-set null
- Tidak wajib diisi (nullable)

## Display Format

### Tabel Admin
```html
<span style="display:inline-block;padding:0.25rem 0.5rem;
      background:#e0f2fe;color:#0369a1;border-radius:4px;
      font-size:0.75rem;font-weight:600;text-transform:uppercase;">
    FLS
</span>
```

### Empty State
```html
<span style="font-size:0.75rem;color:#9ca3af;">-</span>
```

## Usage

### Manual Entry (Admin Panel)
1. Go to Admin > Medicines > Create/Edit
2. Field "Sediaan" sekarang tersedia setelah "Nama Obat"
3. Pilih FLS, BOX, atau biarkan kosong

### Import Excel
1. Download template baru (`template_medicines.xlsx`)
2. Kolom SEDIAAN sekarang ada di posisi ke-3
3. Isi dengan: `fls`, `box`, atau biarkan kosong
4. Upload file

**Example Excel Row**:
```
PABRIK       | NAMA PRODUK         | SEDIAAN | RETAIL | STOK | ...
KIMIA FARMA  | Paracetamol 500mg   | fls     | 5000   | 100  | ...
BERNOFARM    | Aspirin 80mg        | box     | 12000  | 80   | ...
OMRON        | Tensimeter Digital  |         | 350000 | 20   | ...
```

## Testing

### Test Manual Entry
```
1. Login ke Admin Panel
2. Go to Medicines > Create
3. Isi form dengan sediaan "fls"
4. Save
5. Lihat di index - badge FLS muncul
```

### Test Import
```
1. Download template baru
2. Edit Excel, tambah data dengan sediaan
3. Import file
4. Check di index - sediaan muncul
```

### Test Database
```bash
php artisan tinker

# Update existing medicine
App\Models\Medicine::first()->update(['sediaan' => 'fls']);

# Check
App\Models\Medicine::whereNotNull('sediaan')->count();
```

## Notes
- Field ini OPTIONAL (nullable)
- Tidak mempengaruhi data existing (akan null jika belum diisi)
- Case-insensitive saat import (FLS, fls, Fls semua valid)
- Display selalu uppercase (FLS, BOX)

## Future Improvements
1. Tambahkan filter berdasarkan sediaan di index page
2. Tambahkan sediaan di halaman public product listing
3. Tambahkan lebih banyak pilihan sediaan jika diperlukan
