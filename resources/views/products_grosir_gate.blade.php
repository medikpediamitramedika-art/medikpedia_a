@extends('products_pbf_gate')

@section('title', 'Akses Belanja Grosir - Medikpedia')

@section('styles')
    @parent
    <style>
        .pbf-gate-header .lock-icon img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
    </style>
@endsection

@section('content')
<div class="pbf-gate-wrap">
    <div class="pbf-gate-card">
        <div class="pbf-gate-header">
            <div class="lock-icon"><img src="{{ asset('logo1.png') }}" alt="Logo Medikpedia"></div>
            <h1>Belanja Grosir</h1>
            <p>Masukkan kode akses untuk melihat harga grosir.</p>
        </div>
        <div class="pbf-gate-body">
            @if($errors->any())<div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i>{{ $errors->first() }}</div>@endif
            <a class="btn-wa-request" href="https://wa.me/6285890007359?text={{ urlencode('Halo Apotek Medikpedia, saya ingin meminta kode akses Belanja Grosir.') }}" target="_blank" rel="noopener">
                <i class="fa-brands fa-whatsapp"></i> Minta Akses via WhatsApp
            </a>
            <form method="POST" action="{{ route('products.grosir.verify') }}">
                @csrf
                <label class="code-input-label" for="kode">Kode Akses</label>
                <input class="code-input" id="kode" name="kode" value="{{ old('kode') }}" placeholder="Masukkan kode" required autofocus>
                <button class="btn-verify" type="submit"><i class="fa-solid fa-unlock"></i> Masuk Belanja Grosir</button>
            </form>
        </div>
    </div>
</div>
@endsection