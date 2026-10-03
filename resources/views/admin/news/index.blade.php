@extends('layouts.admin')

@section('title', 'Manajemen Produk Promo - Admin Medikpedia')
@section('page-title', '🏷️ Manajemen Produk Promo')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <p style="color: #6b7280;">Total: <strong>{{ $news->total() }} produk promo</strong>
            @if($search || $tipe || $status)
                <span style="color: #1d4ed8;"> — hasil pencarian</span>
            @endif
        </p>
    </div>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
        ➕ Tambah Produk Promo
    </a>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('admin.news.index') }}"
      style="background: white; padding: 1rem 1.25rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

    <div style="flex: 1; min-width: 200px;">
        <label style="font-size: 0.8rem; color: #6b7280; display: block; margin-bottom: 0.3rem;">Cari Produk Promo</label>
        <div style="position: relative;">
            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;">🔍</span>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Judul atau deskripsi produk promo..."
                   style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; outline: none;"
                   onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
        </div>
    </div>

    <div style="min-width: 140px;">
        <label style="font-size: 0.8rem; color: #6b7280; display: block; margin-bottom: 0.3rem;">Tipe</label>
        <select name="tipe"
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; background: white; outline: none;"
                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
            <option value="">Semua Tipe</option>
            <option value="diskon"        {{ $tipe === 'diskon'        ? 'selected' : '' }}>🏷️ Diskon</option>
            <option value="flash_sale"    {{ $tipe === 'flash_sale'    ? 'selected' : '' }}>⚡ Flash Sale</option>
            <option value="bundling"      {{ $tipe === 'bundling'      ? 'selected' : '' }}>📦 Bundling</option>
            <option value="promo_spesial" {{ $tipe === 'promo_spesial' ? 'selected' : '' }}>🎁 Promo Spesial</option>
        </select>
    </div>

    <div style="min-width: 140px;">
        <label style="font-size: 0.8rem; color: #6b7280; display: block; margin-bottom: 0.3rem;">Status</label>
        <select name="status"
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; background: white; outline: none;"
                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
            <option value="">Semua Status</option>
            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>✓ Dipublikasi</option>
            <option value="draft"     {{ $status === 'draft'     ? 'selected' : '' }}>✕ Draft</option>
        </select>
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">
            Cari
        </button>
        @if($search || $tipe || $status)
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                ✕ Reset
            </a>
        @endif
    </div>
</form>

@if($news->count() > 0)
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Judul Berita</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                    <tr>
                        <td style="width: 50px;">
                            @if($item->thumbnail)
                                <img src="{{ url('storage/' . $item->thumbnail) }}" 
                                     alt="{{ $item->judul }}" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 0.25rem;">
                            @else
                                <div style="width: 40px; height: 40px; background: #f3f4f6; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                    @switch($item->tipe)
                                        @case('flash_sale')
                                            ⚡
                                            @break
                                        @case('bundling')
                                            📦
                                            @break
                                        @case('promo_spesial')
                                            🎁
                                            @break
                                        @default
                                            🏷️
                                    @endswitch
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ \Str::limit($item->judul, 30) }}</strong>
                        </td>
                        <td>
                            @if($item->tipe === 'diskon')
                                <span style="background: #FEF3C7; color: #92400E; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">🏷️ Diskon</span>
                            @elseif($item->tipe === 'flash_sale')
                                <span style="background: #FCE7F3; color: #9F1239; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">⚡ Flash Sale</span>
                            @elseif($item->tipe === 'bundling')
                                <span style="background: #E0E7FF; color: #3730A3; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">📦 Bundling</span>
                            @else
                                <span style="background: #F0FDF4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">🎁 Promo Spesial</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_published)
                                <span style="background: #D1FAE5; color: #065F46; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">✓ Dipublikasi</span>
                            @else
                                <span style="background: #FEE2E2; color: #7F1D1D; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">✕ Draft</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item->views }}</strong>
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td style="width: 200px;">
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-secondary btn-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="color: #6b7280; font-size: 0.875rem;">
            Menampilkan {{ $news->firstItem() }}–{{ $news->lastItem() }} dari {{ $news->total() }} produk promo
        </div>
        <div style="display: flex; gap: 0.35rem; align-items: center;">
            @if($news->onFirstPage())
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: #f3f4f6; color: #d1d5db; font-size: 0.875rem; cursor: not-allowed;">‹</span>
            @else
                <a href="{{ $news->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">‹</a>
            @endif

            @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                @if($page == $news->currentPage())
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: #1E88E5; color: white; font-size: 0.875rem; font-weight: 600; min-width: 36px; text-align: center;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb; min-width: 36px; text-align: center;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">{{ $page }}</a>
                @endif
            @endforeach

            @if($news->hasMorePages())
                <a href="{{ $news->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">›</a>
            @else
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: #f3f4f6; color: #d1d5db; font-size: 0.875rem; cursor: not-allowed;">›</span>
            @endif
        </div>
    </div>
@else
    <div style="background: white; padding: 3rem; border-radius: 0.75rem; text-align: center; color: #6b7280;">
        @if($search || $tipe || $status)
            <div style="font-size: 2rem; margin-bottom: 1rem;">🔍</div>
            <p>Tidak ada produk promo yang cocok dengan filter yang dipilih.</p>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">
                ✕ Hapus Filter
            </a>
        @else
            <div style="font-size: 2rem; margin-bottom: 1rem;">📭</div>
            <p>Belum ada produk promo.</p>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                ➕ Tambah Produk Promo Pertama
            </a>
        @endif
    </div>
@endif
@endsection
