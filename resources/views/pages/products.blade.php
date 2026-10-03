@extends('layouts.app')

@section('title', 'Produk - Medikpedia')

@section('content')
<!-- Page Header -->
<div class="hero" style="padding: 40px 0;">
    <div class="container">
        <h1 style="font-size: 36px;">Produk Kami</h1>
        <p>Koleksi lengkap obat dan suplemen berkualitas</p>
    </div>
</div>

<!-- Products Section -->
<section class="py-5">
    <div class="container">
        <!-- Filter -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary">Semua Kategori</button>
                    <button class="btn btn-outline-primary">Obat Umum</button>
                    <button class="btn btn-outline-primary">Suplemen</button>
                    <button class="btn btn-outline-primary">Vitamin</button>
                    <button class="btn btn-outline-primary">Perawatan</button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row">
            <!-- Product 1 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Vitamin+C" alt="Vitamin C" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Suplemen</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Vitamin C 500mg</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Memperkuat sistem imun tubuh</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 45.000</span>
                            <small class="text-muted"><s>Rp 60.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <small class="text-muted">(124 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Vitamin%20C%20500mg%20-%20Rp%2045.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Paracetamol" alt="Paracetamol" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Obat Umum</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Paracetamol 500mg</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Obat demam dan sakit kepala</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 15.000</span>
                            <small class="text-muted"><s>Rp 20.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star-half" style="color: #fbbf24;"></i>
                            <small class="text-muted">(89 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Paracetamol%20500mg%20-%20Rp%2015.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Probiotik" alt="Probiotik" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Suplemen</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Probiotik Plus</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Kesehatan sistem pencernaan</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 65.000</span>
                            <small class="text-muted"><s>Rp 85.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <small class="text-muted">(156 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Probiotik%20Plus%20-%20Rp%2065.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Antiseptik" alt="Antiseptik" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Perawatan</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Antiseptik 100ml</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Pembersih dan desinfektan luka</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 25.000</span>
                            <small class="text-muted"><s>Rp 35.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #d1d5db;"></i>
                            <small class="text-muted">(67 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Antiseptik%20100ml%20-%20Rp%2025.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 5 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Multivitamin" alt="Multivitamin" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Vitamin</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Multivitamin Harian</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Suplemen nutrisi lengkap harian</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 95.000</span>
                            <small class="text-muted"><s>Rp 125.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <small class="text-muted">(203 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Multivitamin%20Harian%20-%20Rp%2095.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 6 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Plester" alt="Plester" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Perawatan</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Plester Antiair</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Perekat luka waterproof</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 18.000</span>
                            <small class="text-muted"><s>Rp 25.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star-half" style="color: #fbbf24;"></i>
                            <small class="text-muted">(98 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Plester%20Antiair%20-%20Rp%2018.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 7 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Obat+Tetes+Mata" alt="Obat Tetes Mata" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Obat Umum</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Obat Tetes Mata</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Pelega mata lelah</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 35.000</span>
                            <small class="text-muted"><s>Rp 48.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #d1d5db;"></i>
                            <small class="text-muted">(54 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Obat%20Tetes%20Mata%20-%20Rp%2035.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Product 8 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Zinc+Tablet" alt="Zinc" class="card-img-top">
                    <div class="card-body">
                        <span class="badge-custom" style="font-size: 12px;">Suplemen</span>
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 600;">Zinc Tablet</h5>
                        <p class="card-text text-muted" style="font-size: 14px;">Tingkatkan daya tahan tubuh</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: var(--primary-color); font-weight: 700; font-size: 18px;">Rp 55.000</span>
                            <small class="text-muted"><s>Rp 72.000</s></small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <small class="text-muted">(187 ulasan)</small>
                        </div>
                        <a href="https://wa.me/62812345678?text=Halo%2C%20saya%20ingin%20memesan%20Zinc%20Tablet%20-%20Rp%2055.000" target="_blank" class="btn btn-primary w-100" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #25D366;">💬 Pesan via WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Berikutnya</a></li>
                </ul>
            </nav>
        </div>
    </div>
</section>

@endsection
