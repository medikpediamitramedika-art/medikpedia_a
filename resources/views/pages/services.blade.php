@extends('layouts.app')

@section('title', 'Layanan - Medikpedia')

@section('content')
<!-- Page Header -->
<div class="hero" style="padding: 40px 0;">
    <div class="container">
        <h1 style="font-size: 36px;">Layanan Kami</h1>
        <p>Berbagai layanan kesehatan untuk kemudahan Anda</p>
    </div>
</div>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card p-4" style="border-left: 4px solid var(--primary-color); border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <div class="card-icon me-3">
                            <i class="fas fa-video"></i>
                        </div>
                        <div>
                            <h4 class="card-title" style="color: var(--text-dark); font-weight: 600;">Konsultasi Online</h4>
                            <p class="card-text text-muted mb-3">Konsultasi langsung dengan apoteker profesional kami melalui video call atau chat. Dapatkan saran kesehatan terpercaya tanpa harus keluar rumah.</p>
                            <small style="color: var(--primary-color); font-weight: 600;">Gratis untuk member premium</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card p-4" style="border-left: 4px solid var(--primary-color); border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <div class="card-icon me-3">
                            <i class="fas fa-pill"></i>
                        </div>
                        <div>
                            <h4 class="card-title" style="color: var(--text-dark); font-weight: 600;">Resep Digital</h4>
                            <p class="card-text text-muted mb-3">Upload resep dokter Anda secara digital dan pesan obat langsung melalui aplikasi kami. Proses cepat, aman, dan terpercaya.</p>
                            <small style="color: var(--primary-color); font-weight: 600;">Tersedia 24/7</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card p-4" style="border-left: 4px solid var(--primary-color); border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <div class="card-icon me-3">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <h4 class="card-title" style="color: var(--text-dark); font-weight: 600;">Pengiriman Ekspres</h4>
                            <p class="card-text text-muted mb-3">Pengiriman gratis ke seluruh Jakarta untuk pembelian minimal Rp 100.000. Paket sampai dalam 2-4 jam dengan driver profesional dan ramah.</p>
                            <small style="color: var(--primary-color); font-weight: 600;">Garansi uang kembali</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card p-4" style="border-left: 4px solid var(--primary-color); border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <div class="card-icon me-3">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div>
                            <h4 class="card-title" style="color: var(--text-dark); font-weight: 600;">Paket Langganan</h4>
                            <p class="card-text text-muted mb-3">Berlangganan obat rutin Anda dan dapatkan harga khusus. Pengiriman otomatis setiap bulan tanpa perlu repot memesan ulang.</p>
                            <small style="color: var(--primary-color); font-weight: 600;">Hemat hingga 20%</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card p-4" style="border-left: 4px solid var(--primary-color); border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <div class="card-icon me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="card-title" style="color: var(--text-dark); font-weight: 600;">Program Loyalitas</h4>
                            <p class="card-text text-muted mb-3">Kumpulkan poin setiap pembelian dan tukarkan dengan voucher, diskon, atau produk gratis. Semakin banyak belanja, semakin besar keuntungan.</p>
                            <small style="color: var(--primary-color); font-weight: 600;">1 poin = Rp 1.000</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card p-4" style="border-left: 4px solid var(--primary-color); border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <div class="card-icon me-3">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h4 class="card-title" style="color: var(--text-dark); font-weight: 600;">Health Check-up</h4>
                            <p class="card-text text-muted mb-3">Kami menyediakan layanan pemeriksaan kesehatan dasar gratis untuk membantu Anda memahami kondisi kesehatan saat ini.</p>
                            <small style="color: var(--primary-color); font-weight: 600;">Gratis untuk semua member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Membership Plans -->
<section class="py-5" style="background-color: #f9fafb;">
    <div class="container">
        <h2 class="section-title">Paket Keanggotaan</h2>
        <p class="section-subtitle">Pilih paket yang sesuai dengan kebutuhan Anda</p>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100" style="border: 2px solid var(--border-color);">
                    <div class="card-body p-5">
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 700; margin-bottom: 20px;">Silver</h5>
                        <h3 style="color: var(--primary-color); font-weight: 700; margin-bottom: 20px;">Gratis</h3>
                        <ul style="list-style: none; padding: 0; margin-bottom: 30px;">
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Akses katalog produk
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Kumpul poin
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-times" style="color: #d1d5db;"></i> Konsultasi online
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-times" style="color: #d1d5db;"></i> Gratis ongkir
                            </li>
                        </ul>
                        <button class="btn btn-primary w-100">Daftar Gratis</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-center h-100" style="border: 3px solid var(--primary-color); position: relative;">
                    <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary-color); color: white; padding: 5px 20px; border-radius: 50px; font-weight: 600;">
                        Populer
                    </div>
                    <div class="card-body p-5">
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 700; margin-bottom: 20px; margin-top: 10px;">Gold</h5>
                        <h3 style="color: var(--primary-color); font-weight: 700; margin-bottom: 5px;">Rp 99.000</h3>
                        <small style="color: var(--text-light);">per tahun</small>
                        <ul style="list-style: none; padding: 0; margin: 30px 0;">
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Semua fitur Silver
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Konsultasi unlimited
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Gratis ongkir
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Diskon 10%
                            </li>
                        </ul>
                        <button class="btn btn-primary w-100">Upgrade Sekarang</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-center h-100" style="border: 2px solid var(--border-color);">
                    <div class="card-body p-5">
                        <h5 class="card-title" style="color: var(--text-dark); font-weight: 700; margin-bottom: 20px;">Platinum</h5>
                        <h3 style="color: var(--primary-color); font-weight: 700; margin-bottom: 5px;">Rp 299.000</h3>
                        <small style="color: var(--text-light);">per tahun</small>
                        <ul style="list-style: none; padding: 0; margin: 30px 0;">
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Semua fitur Gold
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Prioritas customer service
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Diskon 20%
                            </li>
                            <li style="margin-bottom: 10px; color: var(--text-light);">
                                <i class="fas fa-check" style="color: var(--primary-color);"></i> Akses early bird
                            </li>
                        </ul>
                        <button class="btn btn-primary w-100">Upgrade Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
