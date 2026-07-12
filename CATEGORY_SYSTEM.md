# Sistem Kategori Berlapis - Medikpedia

## Deskripsi
Sistem kategori berlapis yang mirip dengan GoApotik telah berhasil diintegrasikan ke dalam website Medikpedia. Sistem ini memiliki:

### Layer 1: Kategori Utama (Home Page)
Menampilkan 5 kategori utama dengan icon berwarna-warni:
- **OBAT** (Purple)
- **ALAT KESEHATAN** (Pink-Red)
- **KECANTIKAN** (Cyan)
- **NUTRISI** (Green)
- **JASA KONSULTAN** (Orange-Yellow)

Setiap kategori memiliki dropdown menu dengan sub-kategori.

### Layer 2: Halaman Sub-Kategori & Produk
Menampilkan:
- ✅ Search engine di bawah banner promo dengan keranjang
- ✅ Sidebar filter kategori
- ✅ Grid produk yang responsive
- ✅ Informasi stok (Tersedia, Sisa, Habis)
- ✅ Tombol "Lihat Detail" dan "Keranjang"
- ✅ Pagination

### Layer 3: Detail Produk
Menampilkan detail lengkap produk dengan:
- ✅ Gambar produk
- ✅ Informasi produk
- ✅ Harga dan stok
- ✅ Tombol tambah ke keranjang

---

## File yang Dibuat/Dimodifikasi

### 1. **Komponen Kategori** 
📁 `resources/views/components/category-selection.blade.php`
- Menampilkan 5 kategori utama dengan dropdown menu
- Background gradient orange yang eye-catching
- Dropdown smooth dengan animasi
- Responsive di semua ukuran layar

### 2. **Halaman Layer 2**
📁 `resources/views/category-layer2.blade.php`
- Halaman kategori dengan sub-kategori dan produk
- Search engine sticky di atas
- Sidebar filter kategori
- Grid produk 4-5 kolom (responsive)
- Pagination otomatis

### 3. **Controller Kategori**
📁 `app/Http/Controllers/CategoryController.php`
- Method `layer2()` untuk menampilkan halaman sub-kategori
- Filter produk berdasarkan kategori utama dan sub-kategori
- Search functionality
- Mapping kategori ke database fields

### 4. **Routes**
📁 `routes/web.php`
- Route tambahan untuk kategori: `/category/{main}/{sub}`
- Menggunakan `CategoryController@layer2`

### 5. **Home Page Update**
📁 `resources/views/home.blade.php`
- Menambahkan component kategori selection setelah promo
- Memindahkan search panel ke bawah banner promo

### 6. **Komponen Category Selection Update**
📁 `resources/views/components/category-selection.blade.php`
- Design mirip GoApotik dengan background orange
- 5 kategori dengan icon berwarna-warni
- Dropdown menu untuk sub-kategori
- Link ke halaman layer 2 untuk setiap sub-kategori

---

## Struktur Kategori

### OBAT
- Obat Oral → `/category/obat/oral`
- Obat Injeksi → `/category/obat/injeksi`
- Obat Luar → `/category/obat/luar`
- Obat OTC → `/category/obat/otc`

### ALAT KESEHATAN
- Alkes Ortopedi & Fisioterapi → `/category/alkes/ortopedi`
- Alkes Gigi → `/category/alkes/gigi`
- Alkes Electrical → `/category/alkes/electrical`
- Alkes Non Electrical → `/category/alkes/non-electrical`

### KECANTIKAN
- Skincare → `/category/kecantikan/skincare`
- Kosmetik → `/category/kecantikan/kosmetik`
- Material Klinik → `/category/kecantikan/material`

### NUTRISI
- Susu → `/category/nutrisi/susu`
- Suplemen → `/category/nutrisi/suplemen`
- Herbal → `/category/nutrisi/herbal`

### JASA KONSULTAN
- Konsultasi Produk → `/contact?type=konsultasi-produk`
- Konsultasi Bisnis → `/contact?type=konsultasi-bisnis`
- Kerjasama → `/contact?type=kerjasama`

---

## Fitur-Fitur Utama

### ✅ Kategori Interaktif
- Dropdown menu dengan animasi smooth
- Auto-close saat klik di luar
- Transisi yang halus

### ✅ Search Engine
- Sticky di atas halaman
- Integrated dengan keranjang belanja
- Responsive design

### ✅ Filter Sidebar
- Filter kategori aktif
- Quick navigation ke sub-kategori
- Highlight kategori aktif

### ✅ Product Grid
- Responsive: 4-5 kolom di desktop, 2-3 di tablet, 2 di mobile
- Hover effect yang menarik
- Informasi stok yang jelas
- Add to cart functionality

### ✅ Pagination
- Automatic pagination setiap 12 produk
- Keep search parameters in URL

### ✅ Responsive Design
- **Desktop**: Layout penuh dengan sidebar + produk
- **Tablet**: Grid 3 kolom, sidebar di atas
- **Mobile**: Grid 2 kolom, sidebar minimal

---

## Cara Menggunakan

### 1. **Navigasi dari Home Page**
- Scroll ke bagian "Kategori Pilihan"
- Klik salah satu kategori (contoh: OBAT)
- Pilih sub-kategori dari dropdown (contoh: Obat Oral)
- Akan redirect ke `/category/obat/oral`

### 2. **Search Produk**
- Di halaman kategori, gunakan search box di atas
- Produk akan difilter berdasarkan keyword
- Pagination otomatis

### 3. **Filter Sidebar**
- Klik kategori di sidebar untuk quick navigation
- Kategori aktif akan highlight
- Icon membantu identifikasi tipe produk

### 4. **Tambah ke Keranjang**
- Klik tombol "Keranjang" di product card
- Atau klik "Lihat Detail" untuk informasi lebih lengkap

---

## Customization

### Mengubah Warna Kategori
Edit di `resources/views/components/category-selection.blade.php`:

```css
.category-icon.obat {
    background: linear-gradient(135deg, #667eea, #764ba2); /* Ubah warna ini */
}
```

### Menambah Sub-Kategori Baru
1. Edit `app/Http/Controllers/CategoryController.php` di method `getValidCategories()`
2. Tambahkan mapping kategori baru
3. Edit `resources/views/components/category-selection.blade.php` untuk menambah item di dropdown

### Mengubah Grid Layout
Edit di `resources/views/category-layer2.blade.php`:

```css
.products-wrapper {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Ubah 200px untuk ukuran card */
}
```

---

## Testing

### Test Checklist
- [ ] Dropdown menu buka/tutup dengan smooth
- [ ] Search engine bekerja dengan keyword
- [ ] Filter sidebar berfungsi
- [ ] Add to cart berfungsi
- [ ] Pagination berfungsi
- [ ] Responsive di mobile/tablet
- [ ] Logo Shopee dan Tokopedia muncul dengan benar

### URLs untuk Testing
```
Home: http://localhost:8000
Kategori Obat - Oral: http://localhost:8000/category/obat/oral
Kategori Alkes - Ortopedi: http://localhost:8000/category/alkes/ortopedi
Kategori Kecantikan - Skincare: http://localhost:8000/category/kecantikan/skincare
Kategori Nutrisi - Suplemen: http://localhost:8000/category/nutrisi/suplemen
```

---

## Notes Teknis

- Search engine dipindahkan ke bawah banner promo dengan CSS `order: 3`
- Kategori komponen menggunakan Blade component system
- Filter kategori menggunakan database query dengan conditional WHERE
- Pagination menggunakan Laravel built-in `paginate()`
- Icons menggunakan Font Awesome 6

---

## Troubleshooting

### Produk tidak muncul
- Pastikan ada data di database `medicines` table
- Check apakah `kategori_produk` field terisi dengan benar
- Lihat CategoryController filter logic

### Route tidak ditemukan
- Verify CategoryController sudah di-import di `routes/web.php`
- Run `php artisan route:list` untuk melihat routes

### Search tidak bekerja
- Check input `name="search"` di form
- Verify CategoryController `$search` variable
- Check database field: `nama_obat`, `kategori`, `deskripsi`

---

## Future Enhancements

- [ ] Add price range filter
- [ ] Add sorting options (A-Z, Price, Newest)
- [ ] Add breadcrumb navigation
- [ ] Add "Related Products" section
- [ ] Add wishlist functionality
- [ ] Add advanced filters (Brand, Rating, etc.)
- [ ] Add product reviews/ratings
- [ ] Add product comparison

---

**Status**: ✅ Siap Digunakan
**Last Updated**: 2024
**Version**: 1.0
