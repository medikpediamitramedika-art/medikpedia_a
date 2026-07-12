@extends('layouts.frontend')

@section('title', 'Tentang Kami - Medikpedia')

@section('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .about-header {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .about-header::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(124,179,66,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .about-header h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
        position: relative;
    }

    .about-header p {
        color: rgba(255,255,255,0.8);
        font-size: 1.1rem;
        position: relative;
    }

    .breadcrumb-custom {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .breadcrumb-custom a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .breadcrumb-custom a:hover { color: white; }
    .breadcrumb-custom span { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .breadcrumb-custom .current { color: #a5d65a; font-size: 0.9rem; font-weight: 600; }

    /* ===== ABOUT CONTENT ===== */
    .about-section {
        padding: 5rem 0;
        background: #f8faff;
    }

    .about-image-stack {
        position: relative;
        height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .about-img-back {
        position: absolute;
        width: 260px;
        height: 300px;
        border-radius: 20px;
        overflow: hidden;
        left: 0;
        top: 20px;
        transform: rotate(-6deg);
        opacity: 0.55;
        z-index: 1;
        background: linear-gradient(135deg, #1565C0, #0D47A1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .about-img-front {
        position: absolute;
        width: 300px;
        height: 340px;
        border-radius: 20px;
        overflow: hidden;
        right: 0;
        top: 0;
        transform: rotate(3deg);
        z-index: 2;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        box-shadow: 0 20px 50px rgba(30,136,229,0.3);
    }

    .about-img-back img,
    .about-img-front img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .about-img-badge {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: white;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .about-img-badge .badge-icon { font-size: 1.5rem; }
    .about-img-badge strong { display: block; font-size: 0.95rem; color: #1f2937; }
    .about-img-badge span { font-size: 0.75rem; color: #6b7280; }

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

    .about-text h2 {
        font-size: clamp(1.75rem, 3vw, 2.25rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 1.25rem;
    }

    .about-text p {
        color: #4b5563;
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .check-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }

    .check-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        color: #374151;
        font-size: 0.95rem;
    }

    .check-list li::before {
        content: '';
        flex-shrink: 0;
        margin-top: 1px;
    }

    .check-list li {
        padding-left: 0;
    }

    .check-list li .fa-circle-check {
        color: #7CB342;
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ===== VISION MISSION ===== */
    .visi-misi-section {
        padding: 5rem 0;
        background: white;
    }

    .vm-card {
        border-radius: 20px;
        padding: 2.5rem;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .vm-card-visi {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 1px solid #90caf9;
    }

    .vm-card-misi {
        background: linear-gradient(135deg, #f1f8e9 0%, #dcedc8 100%);
        border: 1px solid #aed581;
    }

    .vm-card h3 {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .vm-card-visi h3 { color: #1565C0; }
    .vm-card-misi h3 { color: #558B2F; }

    .vm-card p {
        color: #374151;
        line-height: 1.8;
        font-size: 1rem;
    }

    .vm-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    /* ===== VALUES ===== */
    .values-section {
        padding: 5rem 0;
        background: #f8faff;
    }

    .value-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        height: 100%;
    }

    .value-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(30,136,229,0.1);
        border-color: #1E88E5;
    }

    .value-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .value-card h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .value-card p {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.7;
        margin: 0;
    }

    /* ===== STATS ===== */
    .stats-section {
        background: linear-gradient(135deg, #0D47A1 0%, #1E88E5 100%);
        padding: 4rem 0;
    }

    .stats-section .stat-item {
        text-align: center;
        padding: 1rem;
    }

    .stats-section .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        display: block;
    }

    .stats-section .stat-label {
        color: rgba(255,255,255,0.8);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .stats-divider {
        width: 1px;
        background: rgba(255,255,255,0.2);
        height: 60px;
        margin: auto;
    }

    /* ===== TEAM ===== */
    .team-section {
        padding: 5rem 0;
        background: white;
    }

    .team-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        text-align: center;
    }

    .team-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    }

    .team-photo {
        height: 200px;
        background: linear-gradient(135deg, #1E88E5, #0D47A1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
    }

    .team-body {
        padding: 1.5rem;
    }

    .team-body h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .team-role {
        color: #1E88E5;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .team-body p {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0;
    }

    .section-heading {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .section-sub {
        color: #6b7280;
        font-size: 1rem;
        line-height: 1.7;
    }

    @media (max-width: 768px) {
        .about-image-stack { height: 280px; }
        .about-img-front { width: 200px; height: 230px; }
        .about-img-back { width: 170px; height: 200px; }
        .about-img-badge { display: none; }
    }
</style>
@endsection

@section('content')

<!-- ===== HEADER ===== -->
<div class="about-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Tentang Kami</span>
        </div>
        <h1>Tentang Medikpedia</h1>
        <p>Mengenal lebih jauh visi, misi, dan tim profesional kami</p>
    </div>
</div>

<!-- ===== SIAPA KAMI ===== -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="about-image-stack">
                    <div class="about-img-back">
                        <img src="{{ asset('page3.jpeg') }}" alt="Medikpedia">
                    </div>
                    <div class="about-img-front">
                        <img src="{{ asset('page3.jpeg') }}" alt="Medikpedia">
                    </div>
                    <div class="about-img-badge">
                        <span class="badge-icon">🏆</span>
                        <div>
                            <strong>Terpercaya</strong>
                            <span>Sejak 2020</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 about-text">
                <span class="section-label">Siapa Kami</span>
                <h2>Apotik Online Terpercaya untuk Seluruh Indonesia</h2>
                <p>
                    Medikpedia adalah apotik online yang berdiri sejak tahun 2020 dengan komitmen untuk menyediakan produk kesehatan berkualitas tinggi kepada masyarakat Indonesia.
                </p>
                <p>
                    Kami memahami bahwa kesehatan adalah hak setiap orang, dan berkomitmen untuk membuat obat dan suplemen berkualitas dapat diakses oleh semua kalangan dengan harga yang terjangkau.
                </p>
                <ul class="check-list">
                    <li><i class="fa-solid fa-circle-check"></i> Tim apoteker profesional bersertifikasi internasional</li>
                    <li><i class="fa-solid fa-circle-check"></i> Produk asli dengan jaminan kualitas farmasi</li>
                    <li><i class="fa-solid fa-circle-check"></i> Pengiriman cepat ke seluruh Indonesia</li>
                    <li><i class="fa-solid fa-circle-check"></i> Layanan konsultasi kesehatan 24/7</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ===== VISI MISI ===== -->
<section class="visi-misi-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Arah Kami</span>
            <h2 class="section-heading">Visi & Misi</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="vm-card vm-card-visi">
                    <span class="vm-icon"><i class="fa-solid fa-eye"></i></span>
                    <h3>Visi Kami</h3>
                    <p>
                        Menjadi apotik online terpercaya dan terdepan dalam memberikan solusi kesehatan yang terjangkau dan berkualitas untuk seluruh Indonesia.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="vm-card vm-card-misi">
                    <span class="vm-icon"><i class="fa-solid fa-bullseye"></i></span>
                    <h3>Misi Kami</h3>
                    <p>
                        Menyediakan produk kesehatan berkualitas tinggi dengan layanan terbaik, harga kompetitif, dan kemudahan akses melalui platform digital yang user-friendly.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-0">
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
                    <span class="stat-number">5+</span>
                    <span class="stat-label">Tahun Pengalaman</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== VALUES ===== -->
<section class="values-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Prinsip Kami</span>
            <h2 class="section-heading">Nilai-Nilai Kami</h2>
            <p class="section-sub">Landasan yang mendasari setiap langkah dan keputusan kami</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <span class="value-icon"><i class="fa-solid fa-heart" style="color:#ef4444;"></i></span>
                    <h4>Kepercayaan</h4>
                    <p>Kami memprioritaskan kepercayaan pelanggan dengan selalu memberikan produk asli dan layanan yang jujur.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <span class="value-icon"><i class="fa-solid fa-star" style="color:#f59e0b;"></i></span>
                    <h4>Kualitas</h4>
                    <p>Semua produk kami telah melalui proses seleksi ketat dan memiliki sertifikasi farmasi yang lengkap.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <span class="value-icon"><i class="fa-solid fa-handshake" style="color:#1E88E5;"></i></span>
                    <h4>Kemanusiaan</h4>
                    <p>Kami percaya bahwa kesehatan adalah hak setiap orang dan berkomitmen menjangkau semua kalangan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TEAM ===== -->
<section class="team-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Tim Kami</span>
            <h2 class="section-heading">Tim Profesional Kami</h2>
            <p class="section-sub">Dipimpin oleh para ahli di bidang farmasi dan kesehatan</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <div class="team-card">
                    <div class="team-photo"><i class="fa-solid fa-user-nurse" style="color:white;font-size:4rem;"></i></div>
                    <div class="team-body">
                        <h5>dr. Rina Wijaya</h5>
                        <p class="team-role">Kepala Apoteker</p>
                        <p>Berpengalaman lebih dari 15 tahun di industri farmasi dengan sertifikasi internasional.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="team-card">
                    <div class="team-photo"><i class="fa-solid fa-user-tie" style="color:white;font-size:4rem;"></i></div>
                    <div class="team-body">
                        <h5>Budi Hartono</h5>
                        <p class="team-role">Manager Layanan Pelanggan</p>
                        <p>Memastikan setiap pelanggan mendapatkan layanan terbaik dengan respons cepat dan solusi tepat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
