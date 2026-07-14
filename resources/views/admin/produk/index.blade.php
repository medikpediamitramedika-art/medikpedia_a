@extends('layouts.admin')

@section('title', 'Produk Kami - Admin Medikpedia')
@section('page-title', '🛒 Produk Kami')

@section('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
    .page-header-left h2 { font-size:1.1rem; font-weight:700; color:#1f2937; margin:0 0 0.25rem; }
    .page-header-left p  { font-size:0.85rem; color:#6b7280; margin:0; }
    .page-header-actions { display:flex; gap:0.6rem; flex-wrap:wrap; }
    .btn-icon { display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1.1rem; border-radius:0.5rem; font-size:0.875rem; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
    .btn-icon-primary { background:#1E88E5; color:white; }
    .btn-icon-primary:hover { background:#1565C0; color:white; transform:translateY(-1px); }
    .btn-icon-outline { background:white; color:#374151; border:1px solid #d1d5db; }
    .btn-icon-outline:hover { background:#f9fafb; border-color:#9ca3af; color:#374151; }

    /* Kategori tabs */
    .kat-tabs { display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .kat-tab { padding:0.45rem 1rem; border-radius:20px; font-size:0.82rem; font-weight:700; text-decoration:none; border:2px solid #e5e7eb; color:#6b7280; background:white; transition:all 0.2s; }
    .kat-tab:hover { border-color:#1E88E5; color:#1E88E5; }
    .kat-tab.active { background:#1E88E5; color:white; border-color:#1E88E5; }
    .kat-count { background:rgba(255,255,255,0.25); border-radius:20px; padding:0.05rem 0.45rem; font-size:0.72rem; margin-left:0.3rem; }
    .kat-tab:not(.active) .kat-count { background:#f3f4f6; color:#9ca3af; }

    .search-card { background:white; border-radius:0.75rem; padding:1rem 1.25rem; margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(0,0,0,0.06); border:1px solid #f0f0f0; }
    .search-row { display:flex; gap:0.75rem; align-items:flex-end; flex-wrap:wrap; }
    .search-field { flex:1; min-width:180px; }
    .search-field label { display:block; font-size:0.75rem; font-weight:600; color:#6b7280; margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.04em; }
    .search-input-wrap { position:relative; }
    .search-input-wrap i { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:0.85rem; }
    .search-input-wrap input, .search-input-wrap select { width:100%; padding:0.55rem 0.75rem 0.55rem 2.1rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:0.875rem; background:#fafafa; transition:all 0.2s; color:#1f2937; }
    .search-input-wrap select { padding-left:0.75rem; }
    .search-input-wrap input:focus, .search-input-wrap select:focus { outline:none; border-color:#1E88E5; background:white; box-shadow:0 0 0 3px rgba(30,136,229,0.08); }
    .search-actions { display:flex; gap:0.5rem; align-items:flex-end; }
    .btn-search { padding:0.55rem 1.25rem; background:#1E88E5; color:white; border:none; border-radius:0.5rem; font-size:0.875rem; font-weight:600; cursor:pointer; transition:background 0.2s; display:inline-flex; align-items:center; gap:0.4rem; }
    .btn-search:hover { background:#1565C0; }
    .btn-reset { padding:0.55rem 0.9rem; background:white; color:#6b7280; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:0.875rem; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; transition:all 0.2s; }
    .btn-reset:hover { background:#f9fafb; color:#374151; }

    .data-table-wrap { background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.06); border:1px solid #f0f0f0; overflow:hidden; }
    .data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
    .data-table thead tr { background:#f8faff; border-bottom:2px solid #e5e7eb; }
    .data-table th { padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap; }
    .data-table td { padding:0.85rem 1rem; border-bottom:1px solid #f3f4f6; color:#374151; vertical-align:middle; }
    .data-table tbody tr:last-child td { border-bottom:none; }
    .data-table tbody tr:hover { background:#fafbff; }

    .med-img { width:88px; height:88px; border-radius:0.5rem; object-fit:cover; border:1px solid #e5e7eb; display:block; }
    .med-img-placeholder { width:88px; height:88px; border-radius:0.5rem; background:linear-gradient(135deg,#e3f2fd,#bbdefb); display:flex; align-items:center; justify-content:center; font-size:1.25rem; border:1px solid #e5e7eb; }
    .med-name { font-weight:600; color:#1f2937; }
    .med-desc { color:#374151; font-size:0.82rem; margin-top:0.45rem; max-width:240px; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
    .med-meta { font-size:0.76rem; color:#6b7280; margin-top:0.25rem; max-width:240px; }

    .kat-badge { display:inline-block; padding:0.2rem 0.6rem; border-radius:20px; font-size:0.72rem; font-weight:700; }
    .kat-lengkap   { background:#e3f2fd; color:#1565C0; }
    .kat-skincare  { background:#fce4ec; color:#c2185b; }
    .kat-alkes     { background:#e8f5e9; color:#2e7d32; }

    .stock-badge { display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:20px; font-size:0.78rem; font-weight:700; }
    .stock-ok    { background:#d1fae5; color:#065f46; }
    .stock-low   { background:#fef3c7; color:#92400e; }
    .stock-empty { background:#fee2e2; color:#991b1b; }
    .price-text  { font-weight:600; color:#1E88E5; }

    .action-wrap { display:flex; gap:0.4rem; }
    .btn-edit, .btn-del { display:inline-flex; align-items:center; gap:0.3rem; padding:0.35rem 0.75rem; border-radius:0.4rem; font-size:0.78rem; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:all 0.2s; }
    .btn-edit { background:#e3f2fd; color:#1565C0; }
    .btn-edit:hover { background:#1E88E5; color:white; }
    .btn-del  { background:#fee2e2; color:#991b1b; }
    .btn-del:hover  { background:#ef4444; color:white; }

    /* Modal konfirmasi hapus */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:white; border-radius:1rem; padding:2rem; max-width:380px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2); text-align:center; }
    .modal-icon { font-size:2.5rem; margin-bottom:0.75rem; }
    .modal-box h3 { font-size:1rem; font-weight:700; color:#1f2937; margin:0 0 0.5rem; }
    .modal-box p  { font-size:0.875rem; color:#6b7280; margin:0 0 1.5rem; }
    .modal-actions { display:flex; gap:0.6rem; justify-content:center; }
    .btn-modal-cancel { padding:0.6rem 1.5rem; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:0.5rem; font-size:0.875rem; font-weight:600; cursor:pointer; transition:all 0.2s; }
    .btn-modal-cancel:hover { background:#f9fafb; }
    .btn-modal-confirm { padding:0.6rem 1.5rem; background:#ef4444; color:white; border:none; border-radius:0.5rem; font-size:0.875rem; font-weight:700; cursor:pointer; transition:all 0.2s; }
    .btn-modal-confirm:hover { background:#dc2626; }

    .pagination-wrap { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-top:1px solid #f3f4f6; flex-wrap:wrap; gap:0.75rem; }
    .pagination-info { font-size:0.8rem; color:#6b7280; }
    .pagination-pages { display:flex; gap:0.3rem; }
    .page-btn { min-width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:0.4rem; font-size:0.8rem; font-weight:600; text-decoration:none; border:1px solid #e5e7eb; color:#374151; background:white; transition:all 0.2s; padding:0 0.5rem; }
    .page-btn:hover { background:#1E88E5; color:white; border-color:#1E88E5; }
    .page-btn.active { background:#1E88E5; color:white; border-color:#1E88E5; }
    .page-btn.disabled { background:#f9fafb; color:#d1d5db; cursor:not-allowed; pointer-events:none; }

    .empty-state { padding:4rem 2rem; text-align:center; background:white; border-radius:0.75rem; border:1px solid #f0f0f0; }
    .empty-icon { font-size:3rem; margin-bottom:1rem; }
    .empty-state h3 { font-size:1rem; font-weight:700; color:#1f2937; margin-bottom:0.5rem; }
    .empty-state p { font-size:0.875rem; color:#6b7280; margin-bottom:1.5rem; }

    @media (max-width:768px) {
        .page-header { flex-direction:column; }
        .data-table-wrap { overflow-x:auto; }
        .data-table { min-width:600px; }
        .search-row { flex-direction:column; }
        .search-field { min-width:100%; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h2>🛒 Daftar Produk</h2>
        <p>Total <strong>{{ $total }}</strong> produk terdaftar</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.produk.import') }}" class="btn-icon btn-icon-outline">
            <i class="fa-solid fa-file-import"></i> Import Excel
        </a>
        <a href="{{ route('admin.produk.create') }}" class="btn-icon btn-icon-primary">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>

{{-- Kategori Tabs --}}
<div class="kat-tabs">
    <a href="{{ route('admin.produk.index', array_merge(request()->except('kategori_produk'), [])) }}"
       class="kat-tab {{ !$kategori_produk ? 'active' : '' }}">
        🛒 Semua
        <span class="kat-count">{{ $total }}</span>
    </a>
    @foreach($kategoriOptions as $kat)
        @php
            $count = \App\Models\Medicine::where('kategori_produk', $kat)->count();
            $icon  = match($kat) {
                'OBAT'                => '💊',
                'SKINCARE & KOSMETIK' => '✨',
                'ALAT KESEHATAN'      => '🩺',
                default               => '📦',
            };
        @endphp
        <a href="{{ route('admin.produk.index', array_merge(request()->except('kategori_produk'), ['kategori_produk' => $kat])) }}"
           class="kat-tab {{ $kategori_produk === $kat ? 'active' : '' }}">
            {{ $icon }} {{ $kat }}
            <span class="kat-count">{{ $count }}</span>
        </a>
    @endforeach
</div>

{{-- Search --}}
<div class="search-card">
    <form method="GET" action="{{ route('admin.produk.index') }}">
        @if($kategori_produk)
            <input type="hidden" name="kategori_produk" value="{{ $kategori_produk }}">
        @endif
        <div class="search-row">
            <div class="search-field">
                <label>Cari Produk</label>
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama produk, pabrik, deskripsi...">
                </div>
            </div>
            <div class="search-actions">
                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                @if($search || $pabrik)
                    <a href="{{ route('admin.produk.index', $kategori_produk ? ['kategori_produk' => $kategori_produk] : []) }}" class="btn-reset">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

@if($medicines->count() > 0)
    <div class="data-table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:140px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Sediaan</th>
                    <th>Kelompok</th>
                    <th>Kategori</th>
                    <th>Pabrik/Merek</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Ditambahkan</th>
                    <th style="width:130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                <tr>
                    <td style="vertical-align:top;">
                        @if($medicine->gambar)
                            <img src="{{ url('storage/' . $medicine->gambar) }}" alt="{{ $medicine->nama_obat }}" class="med-img">
                        @else
                            <div class="med-img-placeholder">
                                {{ match($medicine->kategori_produk) { 'SKINCARE & KOSMETIK' => '✨', 'ALAT KESEHATAN' => '🩺', default => '💊' } }}
                            </div>
                        @endif
                    </td>
                    <td><div class="med-name">{{ $medicine->nama_obat }}</div></td>
                    <td>
                        @if($medicine->sediaan)
                            <span style="display:inline-block;padding:0.25rem 0.5rem;background:#e0f2fe;color:#0369a1;border-radius:4px;font-size:0.75rem;font-weight:600;text-transform:uppercase;">
                                {{ $medicine->sediaan }}
                            </span>
                        @else
                            <span style="font-size:0.75rem;color:#9ca3af;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($medicine->kelompok === 'PBF')
                            <span style="display:inline-block;padding:0.25rem 0.6rem;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.75rem;font-weight:700;">PBF</span>
                        @elseif($medicine->kelompok === 'APOTEK')
                            <span style="display:inline-block;padding:0.25rem 0.6rem;background:#d1fae5;color:#065f46;border-radius:4px;font-size:0.75rem;font-weight:700;">APOTEK</span>
                        @else
                            <span style="font-size:0.75rem;color:#9ca3af;">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $cls = match($medicine->kategori_produk) {
                                'SKINCARE & KOSMETIK' => 'kat-skincare',
                                'ALAT KESEHATAN'      => 'kat-alkes',
                                default               => 'kat-lengkap',
                            };
                        @endphp
                        <span class="kat-badge {{ $cls }}">{{ $medicine->kategori_produk }}</span>
                    </td>
                    <td><span style="font-size:0.82rem;color:#6b7280;">{{ $medicine->kategori }}</span></td>
                    <td><span class="price-text">{{ $medicine->getFormattedPrice() }}</span></td>
                    <td>
                        @if($medicine->stok > 10)
                            <span class="stock-badge stock-ok">{{ $medicine->stok }}</span>
                        @elseif($medicine->stok > 0)
                            <span class="stock-badge stock-low">{{ $medicine->stok }}</span>
                        @else
                            <span class="stock-badge stock-empty">Habis</span>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;color:#9ca3af;">{{ $medicine->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-wrap">
                            <a href="{{ route('admin.produk.edit', ['produk' => $medicine->id, 'search' => $search, 'kategori_produk' => $kategori_produk, 'pabrik' => $pabrik, 'page' => request('page')]) }}" class="btn-edit">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <button type="button" class="btn-del"
                                onclick="confirmDelete({{ $medicine->id }}, '{{ addslashes($medicine->nama_obat) }}')">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }} dari {{ $medicines->total() }} produk
            </div>
            <div class="pagination-pages">
                @if($medicines->onFirstPage())
                    <span class="page-btn disabled">‹</span>
                @else
                    <a href="{{ $medicines->previousPageUrl() }}" class="page-btn">‹</a>
                @endif
                @foreach($medicines->getUrlRange(1, $medicines->lastPage()) as $page => $url)
                    @if(abs($page - $medicines->currentPage()) <= 2 || $page == 1 || $page == $medicines->lastPage())
                        @if($page == $medicines->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @elseif(abs($page - $medicines->currentPage()) == 3)
                        <span class="page-btn disabled" style="border:none;background:none;">…</span>
                    @endif
                @endforeach
                @if($medicines->hasMorePages())
                    <a href="{{ $medicines->nextPageUrl() }}" class="page-btn">›</a>
                @else
                    <span class="page-btn disabled">›</span>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <h3>Belum ada produk</h3>
        <p>Mulai tambahkan produk atau import dari file Excel/CSV.</p>
        <div style="display:flex;gap:0.6rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('admin.produk.import') }}" class="btn-icon btn-icon-outline">
                <i class="fa-solid fa-file-import"></i> Import Excel
            </a>
            <a href="{{ route('admin.produk.create') }}" class="btn-icon btn-icon-primary">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>
@endif

@endsection

@section('scripts')
{{-- Modal Konfirmasi Hapus --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <h3>Hapus Produk?</h3>
        <p id="deleteModalText">Produk ini akan dihapus permanen dan tidak bisa dikembalikan.</p>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button class="btn-modal-confirm" onclick="submitDelete()">
                <i class="fa-solid fa-trash"></i> Ya, Hapus
            </button>
        </div>
    </div>
</div>

{{-- Hidden form untuk DELETE --}}
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
const deleteRoutes = {
    @foreach($medicines as $medicine)
    {{ $medicine->id }}: '{{ route('admin.produk.destroy', $medicine->id) }}',
    @endforeach
};

function confirmDelete(id, name) {
    document.getElementById('deleteModalText').textContent =
        'Produk "' + name + '" akan dihapus permanen dan tidak bisa dikembalikan.';
    document.getElementById('deleteForm').action = deleteRoutes[id];
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

function submitDelete() {
    document.getElementById('deleteForm').submit();
}

// Tutup modal jika klik overlay
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endsection
