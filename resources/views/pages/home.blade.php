@extends('layouts.frontend')

@section('title', 'Beranda - Medikpedia')

@section('styles')
<style>
    /* ===== HERO SECTION ===== */
    .hero-section {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 40%, #1976D2 70%, #1E88E5 100%);
        min-height: 90vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: 4rem 0;
    }

    /* Soft decorative blobs - tidak pusing */
    .hero-section::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(124,179,66,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(124,179,66,0.2);
        border: 1px solid rgba(124,179,66,0.4);
        color: #a5d65a;
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .welcome-text {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.6);
        font-weight: 300;
        margin-bottom: 1rem;
        text-align: left;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .hero-title {
        font-size: clamp(4rem, 8vw, 6rem);
        font-weight: 900;
        color: white;
        line-height: 0.9;
        margin-bottom: 1.5rem;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
    }

    .hero-title span {
        text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: rgba(255,255,255,0.9);
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle span {
        color: #7CB342;
    }

    .medik-blue {
        color: #1E88E5 !important;
        text-shadow: 3px 3px 6px rgba(30,136,229,0.4);
    }

    .medik-green {
        color: #7CB342 !important;
        text-shadow: 3px 3px 6px rgba(124,179,66,0.4);
    }

    .hero-desc {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.85);
        line-height: 1.8;
        margin-bottom: 2rem;
        max-width: 500px;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        background: #7CB342;
        color: white;
        padding: 0.85rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(124,179,66,0.4);
    }

    .btn-hero-primary:hover {
        background: #689F38;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(124,179,66,0.5);
        color: white;
    }

    .btn-hero-outline {
        background: transparent;
        color: white;
        padding: 0.85rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        border: 2px solid rgba(255,255,255,0.5);
        transition: all 0.3s;
    }

    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.15);
        border-color: white;
        color: white;
        transform: translateY(-3px);
    }

    /* ===== DISTRIBUTOR GALLERY ===== */
    .distributor-gallery {
        position: relative;
        height: 500px;
        display: grid;
        grid-template-columns: 1fr 2fr;
        grid-template-rows: 1fr 1fr;
        gap: 1rem;
        padding: 1rem;
    }

    .main-photo {
        grid-column: 2;
        grid-row: 1 / 3;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        transition: transform 0.3s;
    }

    .main-photo:hover {
        transform: scale(1.02);
    }

    .main-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        padding: 2rem 1.5rem 1.5rem;
    }

    .overlay-content h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #7CB342;
    }

    .overlay-content p {
        font-size: 0.9rem;
        margin: 0;
        opacity: 0.9;
    }

    .small-photos {
        grid-column: 1;
        grid-row: 1 / 3;
        display: grid;
        grid-template-rows: repeat(4, 1fr);
        gap: 0.75rem;
    }

    .small-photo {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: all 0.3s;
        cursor: pointer;
    }

    .small-photo:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.2);
    }

    .small-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .small-photo:hover img {
        transform: scale(1.1);
    }

    .small-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(30,136,229,0.8), rgba(124,179,66,0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .small-photo:hover .small-overlay {
        opacity: 1;
    }

    .small-overlay span {
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
    }

    .small-overlay i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    /* Floating badges */
    .distributor-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 10;
        animation: floatUp 3s ease-in-out infinite;
    }

    .distributor-badge-2 {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 10;
        animation: floatUp 3s ease-in-out infinite;
        animation-delay: 1.5s;
    }

    .badge-content {
        text-align: center;
    }

    .badge-content strong {
        display: block;
        font-size: 1.2rem;
        font-weight: 800;
        color: #1E88E5;
        line-height: 1;
    }

    .badge-content span {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
    }

    @keyframes floatUp {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* ===== STATS BAR ===== */
    .stats-bar {
        background: white;
        padding: 2rem 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .stat-item {
        text-align: center;
        padding: 0.5rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #1E88E5;
        display: block;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* ===== FEATURES SECTION ===== */
    .features-section {
        padding: 5rem 0;
        background: #f8faff;
    }

    .section-label {
        display: inline-block;
        background: #e3f2fd;
        color: #1E88E5;
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .section-heading {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .section-sub {
        color: #6b7280;
        font-size: 1rem;
        line-height: 1.7;
        max-width: 500px;
    }

    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        height: 100%;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1E88E5, #7CB342);
        transform: scaleX(0);
        transition: transform 0.3s;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(30,136,229,0.12);
        border-color: transparent;
    }

    .feature-card:hover::before {
        transform: scaleX(1);
    }

    .feature-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .icon-blue { background: #e3f2fd; }
    .icon-green { background: #f1f8e9; }
    .icon-purple { background: #f3e5f5; }
    .icon-orange { background: #fff3e0; }

    .feature-card h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .feature-card p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.7;
        margin: 0;
    }

    /* ===== NEWS PREVIEW ===== */
    .news-preview-section {
        padding: 5rem 0;
        background: white;
    }

    .news-preview-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }

    .news-preview-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        color: inherit;
    }

    .news-thumb {
        height: 180px;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        overflow: hidden;
    }

    .news-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .news-preview-body {
        padding: 1.25rem;
    }

    .news-preview-date {
        font-size: 0.8rem;
        color: #7CB342;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .news-preview-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-preview-excerpt {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ===== CTA SECTION ===== */
    .cta-section {
        background: linear-gradient(135deg, #0D47A1 0%, #1E88E5 50%, #7CB342 100%);
        padding: 5rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .cta-section h2 {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
        position: relative;
    }

    .cta-section p {
        color: rgba(255,255,255,0.85);
        font-size: 1.1rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .btn-cta {
        background: white;
        color: #1E88E5;
        padding: 0.9rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        display: inline-block;
        position: relative;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.25);
        color: #1565C0;
    }

    @media (max-width: 768px) {
        .distributor-gallery {
            height: 400px;
            grid-template-columns: 1fr;
            grid-template-rows: 2fr 1fr;
            gap: 0.75rem;
        }
        
        .main-photo {
            grid-column: 1;
            grid-row: 1;
        }
        
        .small-photos {
            grid-column: 1;
            grid-row: 2;
            grid-template-rows: none;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
        
        .distributor-badge,
        .distributor-badge-2 {
            display: none;
        }
        
        .hero-section { 
            min-height: auto; 
            padding: 3rem 0; 
        }
    }
</style>
@endsection

@section('content')

<!-- ===== HERO ===== -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-content">
                <div class="welcome-text">
                    Selamat Datang di
                </div>
                
                <h1 class="hero-title">
                    <span style="color:#1E88E5;">Medik</span><span style="color:#7CB342;">pedia</span>
                </h1>
                <p class="hero-subtitle">
                    Distributor Obat & Suplemen Terpercaya
                </p>
                <p class="hero-desc">
                    Medikpedia adalah distributor resmi obat-obatan dan suplemen kesehatan dari 76+ perusahaan farmasi terkemuka. Melayani apotek, klinik, dan rumah sakit di seluruh Indonesia dengan jaminan kualitas dan harga kompetitif.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('products.index') }}" class="btn-hero-primary">📋 Katalog Produk</a>
                    <a href="{{ route('contact') }}" class="btn-hero-outline"><i class="fa-solid fa-handshake"></i> Jadi Mitra</a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="distributor-gallery">
                    <!-- Foto utama besar di kanan -->
                    <div class="main-photo">
                        <img src="{{ asset('page3.jpeg') }}" alt="Medikpedia - Distributor Obat Terpercaya">
                        <div class="photo-overlay">
                            <div class="overlay-content">
                                <h3><i class="fas fa-pills"></i> Distributor Obat Terpercaya</h3>
                                <p>Melayani kebutuhan farmasi seluruh Indonesia</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Foto-foto kecil di sebelah kiri -->
                    <div class="small-photos">
                        <div class="small-photo">
                            <img src="{{ asset('page3.jpeg') }}" alt="Gudang Obat">
                            <div class="small-overlay">
                                <span><i class="fas fa-warehouse"></i> Gudang</span>
                            </div>
                        </div>
                        <div class="small-photo">
                            <img src="{{ asset('page3.jpeg') }}" alt="Tim Farmasi">
                            <div class="small-overlay">
                                <span><i class="fas fa-user-md"></i> Tim Ahli</span>
                            </div>
                        </div>
                        <div class="small-photo">
                            <img src="{{ asset('page3.jpeg') }}" alt="Pengiriman">
                            <div class="small-overlay">
                                <span><i class="fas fa-truck"></i> Distribusi</span>
                            </div>
                        </div>
                        <div class="small-photo">
                            <img src="{{ asset('page3.jpeg') }}" alt="Kualitas Terjamin">
                            <div class="small-overlay">
                                <span><i class="fas fa-certificate"></i> Sertifikat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Badge floating -->
                    <div class="distributor-badge">
                        <div class="badge-content">
                            <strong>500+</strong>
                            <span>Produk Farmasi</span>
                        </div>
                    </div>
                    
                    <div class="distributor-badge-2">
                        <div class="badge-content">
                            <strong>76+</strong>
                            <span>Mitra Farmasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS BAR ===== -->
<div class="stats-bar">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Pelanggan Puas</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Produk Tersedia</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">99%</span>
                    <span class="stat-label">Kepuasan Pelanggan</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Layanan Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FEATURES ===== -->
<section class="features-section">
    <div class="container">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-5">
                <span class="section-label">Keunggulan Kami</span>
                <h2 class="section-heading">Mengapa Pilih Medikpedia?</h2>
                <p class="section-sub">Kami berkomitmen memberikan layanan terbaik dengan standar farmasi internasional untuk kesehatan Anda dan keluarga.</p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="feature-card">
                            <div class="feature-icon-wrap icon-blue">✅</div>
                            <h4>Produk Berkualitas</h4>
                            <p>Semua produk tersertifikasi dan melewati standar kualitas farmasi internasional.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-card">
                            <div class="feature-icon-wrap icon-green">🚚</div>
                            <h4>Pengiriman Cepat</h4>
                            <p>Pengiriman gratis ke seluruh Jakarta dan pengiriman kilat tersedia.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-card">
                            <div class="feature-icon-wrap icon-purple">🎧</div>
                            <h4>Layanan 24/7</h4>
                            <p>Tim customer service siap membantu kapan saja melalui berbagai channel.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-card">
                            <div class="feature-icon-wrap icon-orange">🔒</div>
                            <h4>Transaksi Aman</h4>
                            <p>Sistem pembayaran terenkripsi dan data pribadi Anda terlindungi penuh.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<section class="cta-section">
    <div class="container">
        <h2>Dapatkan Penawaran Spesial Hari Ini</h2>
        <p>Daftar sekarang dan dapatkan diskon 10% untuk pembelian pertama Anda</p>
        <a href="{{ route('products.index') }}" class="btn-cta">🛒 Mulai Belanja</a>
    </div>
</section>

@endsection
