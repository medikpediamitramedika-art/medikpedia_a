<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMedicineController;
use App\Http\Controllers\AdminMedicineImportController;
use App\Http\Controllers\AdminPrescriptionController;
use App\Http\Controllers\AdminPrescriptionImportController;
use App\Http\Controllers\AdminPrescriptionProductController;
use App\Http\Controllers\AdminPrescriptionProductImportController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminProdukImportController;
use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\AdminPromoProductController;
use App\Http\Controllers\PurchaseHistoryController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Serve uploaded images langsung dari storage/ (banners, promos & medicines)
// Dipakai saat symlink tidak tersedia di hosting
Route::get('/storage/{folder}/{filename}', function (string $folder, string $filename) {
    $allowed = ['banners', 'medicines', 'promos'];
    if (!in_array($folder, $allowed)) abort(404);

    $path = storage_path($folder . '/' . $filename);
    if (!file_exists($path)) abort(404);

    $mime = mime_content_type($path) ?: 'application/octet-stream';
    return response()->file($path, ['Content-Type' => $mime]);
})->where(['folder' => 'banners|medicines|promos', 'filename' => '.+'])->name('storage.image');

// Products routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products-pbf', [ProductController::class, 'pbf'])->name('products.pbf');
Route::post('/products-pbf/verify', [ProductController::class, 'pbfVerify'])->name('products.pbf.verify');
Route::post('/products-pbf/logout', [ProductController::class, 'pbfLogout'])->name('products.pbf.logout');
Route::get('/products-apotek', [ProductController::class, 'apotek'])->name('products.apotek');
Route::post('/orders/history', [PurchaseHistoryController::class, 'store'])->name('orders.history.store');

// Category routes (Layer 2 & 3)
Route::get('/category/{main}/{sub}', [CategoryController::class, 'layer2'])->name('category.layer2');

// Medicine detail (public)
Route::get('/medicines/{id}', [HomeController::class, 'show'])->name('medicines.show');

// Prescriptions routes
Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

// Auth routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/customer/logout', [AuthController::class, 'customerLogout'])->name('customer.logout');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/purchase-history', [AdminDashboardController::class, 'purchaseHistory'])->name('purchase-history.index');
    Route::get('/purchase-history/export', [AdminDashboardController::class, 'exportPurchaseHistory'])->name('purchase-history.export');
    Route::post('/purchase-history/{order}/approval', [AdminDashboardController::class, 'updateApprovalStatus'])->name('purchase-history.approval');
    Route::delete('/purchase-history/{order}', [AdminDashboardController::class, 'destroy'])->name('purchase-history.destroy');
    Route::delete('/purchase-history', [AdminDashboardController::class, 'destroyAll'])->name('purchase-history.destroyAll');

    // Medicines management
    Route::resource('medicines', AdminMedicineController::class);
    Route::post('medicines/{medicine}/update-stock', [AdminMedicineController::class, 'updateStock'])->name('medicines.update-stock');
    Route::get('medicines-import', [AdminMedicineImportController::class, 'showImportForm'])->name('medicines.import');
    Route::post('medicines-import', [AdminMedicineImportController::class, 'import'])->name('medicines.import.process');
    Route::get('medicines-import/template', [AdminMedicineImportController::class, 'downloadTemplate'])->name('medicines.import.template');

    // Prescriptions management
    Route::resource('prescriptions', AdminPrescriptionController::class);
    Route::post('prescriptions/{prescription}/update-stock', [AdminPrescriptionController::class, 'updateStock'])->name('prescriptions.update-stock');
    Route::get('prescriptions-import', [AdminPrescriptionImportController::class, 'showImportForm'])->name('prescriptions.import');
    Route::post('prescriptions-import', [AdminPrescriptionImportController::class, 'import'])->name('prescriptions.import.process');

    // Prescription Products management
    Route::resource('prescription-products', AdminPrescriptionProductController::class);
    Route::post('prescription-products/{prescriptionProduct}/update-stock', [AdminPrescriptionProductController::class, 'updateStock'])->name('prescription-products.update-stock');
    Route::get('prescription-products-import', [AdminPrescriptionProductImportController::class, 'showImportForm'])->name('prescription-products.import');
    Route::post('prescription-products-import', [AdminPrescriptionProductImportController::class, 'import'])->name('prescription-products.import.process');
    Route::get('prescription-products-import/template', [AdminPrescriptionProductImportController::class, 'downloadTemplate'])->name('prescription-products.import.template');

    // Produk management
    Route::delete('produk/bulk-delete', [AdminProdukController::class, 'destroyMany'])->name('produk.destroyMany');
    Route::resource('produk', AdminProdukController::class);
    Route::post('produk/{produk}/update-stock', [AdminProdukController::class, 'updateStock'])->name('produk.update-stock');
    Route::get('produk-import', [AdminProdukImportController::class, 'showImportForm'])->name('produk.import');
    Route::post('produk-import', [AdminProdukImportController::class, 'import'])->name('produk.import.process');
    Route::get('produk-import/template', [AdminProdukImportController::class, 'downloadTemplate'])->name('produk.import.template');

    // Banner / Promo Slideshow management
    Route::resource('banners', AdminBannerController::class);
    Route::post('banners/{banner}/toggle', [AdminBannerController::class, 'toggleAktif'])->name('banners.toggle');

    // Promo Produk management
    Route::resource('promo-products', AdminPromoProductController::class);
    Route::post('promo-products/{promoProduct}/toggle', [AdminPromoProductController::class, 'toggleAktif'])->name('promo-products.toggle');
});
