@extends('layouts.app')

@section('title', 'Kontak - Medikpedia')

@section('content')
<!-- Page Header -->
<div class="hero" style="padding: 40px 0;">
    <div class="container">
        <h1 style="font-size: 36px;">Hubungi Kami</h1>
        <p>Kami siap membantu Anda 24/7</p>
    </div>
</div>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <div class="mb-5">
                    <h4 style="color: var(--text-dark); font-weight: 700; margin-bottom: 15px;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary-color); margin-right: 10px;"></i>Alamat
                    </h4>
                    <p class="text-muted" style="line-height: 1.8;">
                        Jl. Letjen Suprapto No.1, Sumur Batu,<br>
                        Kec. Kemayoran, Kota Jakarta Pusat,<br>
                        Daerah Khusus Ibukota Jakarta 10640
                    </p>
                </div>

                <div class="mb-5">
                    <h4 style="color: var(--text-dark); font-weight: 700; margin-bottom: 15px;">
                        <i class="fas fa-phone" style="color: var(--primary-color); margin-right: 10px;"></i>Telepon
                    </h4>
                    <p class="text-muted">
                        <a href="tel:+6285890007359" style="color: var(--primary-color); text-decoration: none;">0858 9000 7359</a>
                    </p>
                    <p class="text-muted">
                        <small>Senin - Jumat: 08:00 - 18:00</small><br>
                        <small>Sabtu - Minggu: 09:00 - 17:00</small>
                    </p>
                </div>

                <div class="mb-5">
                    <h4 style="color: var(--text-dark); font-weight: 700; margin-bottom: 15px;">
                        <i class="fas fa-envelope" style="color: var(--primary-color); margin-right: 10px;"></i>Email
                    </h4>
                    <p class="text-muted">
                        <a href="mailto:medikpedia.mitramedika@gmail.com" style="color: var(--primary-color); text-decoration: none;">medikpedia.mitramedika@gmail.com</a>
                    </p>
                </div>

                <div>
                    <h4 style="color: var(--text-dark); font-weight: 700; margin-bottom: 15px;">
                        <i class="fas fa-share-alt" style="color: var(--primary-color); margin-right: 10px;"></i>Ikuti Kami
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; padding: 0;">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; padding: 0;">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; padding: 0;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; padding: 0;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card p-5 border-0" style="background-color: #f9fafb;">
                    <h3 style="color: var(--text-dark); font-weight: 700; margin-bottom: 30px;">Kirim Pesan Kepada Kami</h3>
                    
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label" style="color: var(--text-dark); font-weight: 600;">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label" style="color: var(--text-dark); font-weight: 600;">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telepon" class="form-label" style="color: var(--text-dark); font-weight: 600;">Telepon</label>
                                <input type="tel" class="form-control" id="telepon" placeholder="Masukkan nomor telepon">
                            </div>
                            <div class="col-md-6">
                                <label for="subjek" class="form-label" style="color: var(--text-dark); font-weight: 600;">Subjek</label>
                                <select class="form-select" id="subjek" required>
                                    <option value="">Pilih subjek</option>
                                    <option value="pemesanan">Pertanyaan Pemesanan</option>
                                    <option value="produk">Pertanyaan Produk</option>
                                    <option value="pengiriman">Masalah Pengiriman</option>
                                    <option value="kerjasama">Kerjasama</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pesan" class="form-label" style="color: var(--text-dark); font-weight: 600;">Pesan</label>
                            <textarea class="form-control" id="pesan" rows="6" placeholder="Tuliskan pesan Anda di sini" required></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="setuju" required>
                                <label class="form-check-label text-muted" for="setuju">
                                    Saya setuju dengan kebijakan privasi dan syarat & ketentuan
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-weight: 600;">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5" style="background-color: #f9fafb;">
    <div class="container">
        <h3 style="color: var(--text-dark); font-weight: 700; margin-bottom: 30px;">Lokasi Kami</h3>
        <div style="width: 100%; height: 400px; border-radius: 12px; overflow: hidden;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.8!2d106.8574!3d-6.1627!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e1d1f!2sITC%20Cempaka%20Mas!5e0!3m2!1sid!2sid!4v1"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
        
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Bagaimana cara memesan produk?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Anda dapat memesan produk melalui website kami atau aplikasi mobile. Pilih produk yang diinginkan, masukkan ke keranjang, dan lakukan checkout. Pembayaran dapat dilakukan melalui transfer bank, e-wallet, atau cicilan.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Berapa lama waktu pengiriman?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Waktu pengiriman tergantung dari pilihan layanan pengiriman. Untuk pengiriman regular ke Jakarta membutuhkan 2-4 hari kerja. Sementara untuk pengiriman express, produk dapat sampai dalam 2-4 jam.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Apakah semua produk original?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Ya, semua produk yang kami jual adalah original dan tersertifikasi. Kami bermitra langsung dengan produsen dan distributor resmi untuk memastikan kualitas dan keaslian produk.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Bagaimana jika produk rusak saat pengiriman?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Jika produk rusak atau tidak sesuai saat diterima, segera hubungi customer service kami dengan foto bukti. Kami akan mengganti atau mengembalikan dana Anda tanpa pertanyaan.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Bagaimana sistem pembayaran?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Kami menerima berbagai metode pembayaran termasuk transfer bank, e-wallet (GoPay, OVO, Dana), cicilan (cicilan 0%), dan cash on delivery di area tertentu.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
