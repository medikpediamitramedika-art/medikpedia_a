@extends('layouts.frontend')

@section('title', 'Akses Produk PBF - Medikpedia')

@section('styles')
<style>
.pbf-gate-wrap {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
}
.pbf-gate-card {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 8px 40px rgba(13,71,161,0.13);
    border: 1px solid #e5e7eb;
    width: 100%;
    max-width: 500px;
    overflow: hidden;
}
.pbf-gate-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    padding: 2.5rem 2rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.pbf-gate-header::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
    border-radius: 50%;
}
.pbf-gate-header .lock-icon {
    width: 72px; height: 72px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem; color: #fff;
    backdrop-filter: blur(4px);
    border: 2px solid rgba(255,255,255,0.3);
}
.pbf-gate-header h1 {
    font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0 0 0.4rem;
}
.pbf-gate-header p {
    color: rgba(255,255,255,0.85); font-size: 0.9rem; margin: 0;
}
.pbf-gate-body {
    padding: 2rem;
}
.pbf-section-title {
    font-size: 0.72rem; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: 0.8px;
    margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.pbf-section-title::before, .pbf-section-title::after {
    content: ''; flex: 1; height: 1px; background: #e5e7eb;
}

/* Panduan steps */
.pbf-steps {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.75rem;
}
.pbf-steps h4 {
    font-size: 0.88rem; font-weight: 800; color: #92400e;
    margin: 0 0 0.85rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.pbf-step {
    display: flex; gap: 0.75rem; align-items: flex-start;
    margin-bottom: 0.75rem;
}
.pbf-step:last-child { margin-bottom: 0; }
.pbf-step-num {
    width: 22px; height: 22px; flex-shrink: 0;
    background: #f59e0b; color: #fff;
    border-radius: 50%; font-size: 0.72rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin-top: 1px;
}
.pbf-step p {
    margin: 0; font-size: 0.85rem; color: #78350f; line-height: 1.5;
}
.pbf-step strong { color: #92400e; }

/* WA Button */
.btn-wa-request {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    width: 100%; padding: 0.8rem 1rem;
    background: linear-gradient(135deg, #25D366, #1ebe5d);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: 0.9rem; cursor: pointer;
    text-decoration: none; transition: all 0.25s;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 14px rgba(37,211,102,0.35);
}
.btn-wa-request:hover {
    background: linear-gradient(135deg, #1ebe5d, #17a34a);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37,211,102,0.45);
    color: #fff;
}

/* Form kode */
.code-input-wrap {
    position: relative;
}
.code-input {
    width: 100%;
    padding: 0.85rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase;
    outline: none; transition: border-color 0.2s;
    color: #1f2937;
    text-align: center;
}
.code-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }
.code-input.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
.code-input-label {
    display: block; font-size: 0.8rem; font-weight: 700;
    color: #374151; margin-bottom: 0.5rem;
}
.btn-verify {
    width: 100%; padding: 0.85rem;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: 1rem; cursor: pointer;
    margin-top: 0.75rem; transition: all 0.25s;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    box-shadow: 0 4px 14px rgba(217,119,6,0.3);
}
.btn-verify:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(180,83,9,0.35);
}
.alert-error {
    background: #fee2e2; color: #7f1d1d;
    border: 1px solid #fca5a5; border-radius: 10px;
    padding: 0.7rem 1rem; font-size: 0.85rem; font-weight: 600;
    margin-bottom: 0.75rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.alert-success {
    background: #d1fae5; color: #065f46;
    border: 1px solid #6ee7b7; border-radius: 10px;
    padding: 0.7rem 1rem; font-size: 0.85rem; font-weight: 600;
    margin-bottom: 0.75rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.pbf-info-note {
    font-size: 0.78rem; color: #9ca3af;
    text-align: center; margin-top: 1rem;
    line-height: 1.5;
}
.pbf-info-note a { color: #d97706; text-decoration: none; }
.pbf-info-note a:hover { text-decoration: underline; }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="products-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%); padding: 3rem 0; position: relative; overflow: hidden;">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Produk PBF</span>
        </div>
        <h1 style="font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 800; color: white; margin: 0;">
            <i class="fa-solid fa-box"></i> Katalog Produk PBF
        </h1>
        <p style="color: rgba(255,255,255,0.8); font-size: 1rem; margin-top: 0.4rem;">
            Halaman khusus untuk mitra & distributor farmasi
        </p>
    </div>
    <i class="fa-solid fa-pills" style="position:absolute;color:rgba(255,255,255,0.08);bottom:10px;right:12%;font-size:4rem;pointer-events:none;"></i>
    <i class="fa-solid fa-capsules" style="position:absolute;color:rgba(255,255,255,0.08);top:15px;right:28%;font-size:3rem;pointer-events:none;"></i>
</div>

<div class="pbf-gate-wrap">
    <div class="pbf-gate-card">

        {{-- Header kartu --}}
        <div class="pbf-gate-header">
            <div class="lock-icon"><i class="fa-solid fa-lock"></i></div>
            <h1>Akses Terbatas</h1>
            <p>Halaman ini hanya untuk mitra & distributor resmi Medikpedia</p>
        </div>

        <div class="pbf-gate-body">

            {{-- Alert error --}}
            @if ($errors->has('kode'))
                <div class="alert-error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ $errors->first('kode') }}
                </div>
            @endif

            {{-- Panduan minta kode --}}
            <div class="pbf-section-title">Cara mendapatkan kode akses</div>

            <div class="pbf-steps">
                <h4><i class="fa-brands fa-whatsapp"></i> Hubungi Admin via WhatsApp</h4>
                <div class="pbf-step">
                    <span class="pbf-step-num">1</span>
                    <p>Klik tombol di bawah — pesan sudah disiapkan otomatis untuk Anda.</p>
                </div>
                <div class="pbf-step">
                    <span class="pbf-step-num">2</span>
                    <p>Kirim pesan ke admin, sertakan <strong>nama perusahaan / apotek</strong> dan <strong>nomor SIPA/SIA</strong> Anda.</p>
                </div>
                <div class="pbf-step">
                    <span class="pbf-step-num">3</span>
                    <p>Admin akan memverifikasi data dan mengirimkan <strong>kode akses</strong> ke WhatsApp Anda.</p>
                </div>
                <div class="pbf-step">
                    <span class="pbf-step-num">4</span>
                    <p>Masukkan kode akses di kolom di bawah untuk membuka katalog PBF.</p>
                </div>
            </div>

            {{-- Tombol WA --}}
            <a href='https://wa.me/6285890007359?text={{ urlencode("Halo Medikpedia, saya ingin meminta kode akses untuk halaman Produk PBF.\n\nData saya:\n- Nama Perusahaan/Apotek: \n- Nomor SIA/SIPA: \n- Nama PIC: \n\nMohon informasi kode aksesnya. Terima kasih.") }}'
               target="_blank"
               class="btn-wa-request">
                <i class="fa-brands fa-whatsapp" style="font-size:1.3rem;"></i>
                Minta Kode Akses via WhatsApp
            </a>

            {{-- Divider --}}
            <div class="pbf-section-title">Masukkan kode akses</div>

            {{-- Form kode --}}
            <form action="{{ route('products.pbf.verify') }}" method="POST">
                @csrf
                <label class="code-input-label" for="kode">
                    <i class="fa-solid fa-key" style="color:#f59e0b;"></i>
                    Kode Akses
                </label>
                <input
                    type="text"
                    id="kode"
                    name="kode"
                    class="code-input {{ $errors->has('kode') ? 'is-invalid' : '' }}"
                    placeholder="Masukkan kode di sini..."
                    autocomplete="off"
                    autofocus
                    value="{{ old('kode') }}"
                    maxlength="30"
                >
                <button type="submit" class="btn-verify">
                    <i class="fa-solid fa-unlock"></i>
                    Buka Katalog PBF
                </button>
            </form>

            <p class="pbf-info-note">
                Kode akses bersifat rahasia dan hanya untuk mitra resmi.<br>
                Butuh bantuan? <a href="https://wa.me/6285890007359" target="_blank">Chat Admin</a>
                atau kunjungi <a href="{{ route('contact') }}">halaman kontak</a>.
            </p>

        </div>
    </div>
</div>

@endsection
