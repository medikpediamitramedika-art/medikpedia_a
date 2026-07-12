@extends('layouts.admin')

@section('title', 'Dashboard - Admin Medikpedia')
@section('page-title', '📊 Dashboard')

@section('content')

<div class="stats-grid" style="margin-top:1rem;">
    <div class="stat-card">
        <div class="stat-label">Total Produk</div>
        <div class="stat-value" id="stat-total">{{ $totalProduk }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Stok</div>
        <div class="stat-value" style="color:#1E88E5;" id="stat-stok">{{ number_format($totalStok) }}</div>
    </div>
    <div class="stat-card" style="border-top-color:#f59e0b;">
        <div class="stat-label">Stok Rendah (&lt;5)</div>
        <div class="stat-value" style="color:#f59e0b;" id="stat-lowstok">{{ $lowStok }}</div>
    </div>
    <div class="stat-card" style="border-top-color:#10b981;">
        <div class="stat-label">Total Banner</div>
        <div class="stat-value" style="color:#10b981;">{{ $totalBanners }}</div>
    </div>
    <div class="stat-card" style="border-top-color:#8b5cf6;">
        <div class="stat-label">Total Omzet</div>
        <div class="stat-value" style="color:#8b5cf6;">{{ 'Rp ' . number_format($totalOmzet, 0, ',', '.') }}</div>
    </div>
</div>

{{-- Per Kategori --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
    @foreach($perKategori as $kat => $jumlah)
        @php
            $icon  = match($kat) { 'OBAT' => '💊', 'SKINCARE & KOSMETIK' => '✨', 'ALAT KESEHATAN' => '🩺', default => '📦' };
            $color = match($kat) { 'OBAT' => '#1E88E5', 'SKINCARE & KOSMETIK' => '#c2185b', 'ALAT KESEHATAN' => '#2e7d32', default => '#6b7280' };
        @endphp
        <div class="stat-card" style="border-top-color:{{ $color }};">
            <div class="stat-label">{{ $icon }} {{ $kat }}</div>
            <div class="stat-value" style="color:{{ $color }};">{{ $jumlah }}</div>
            <div style="font-size:0.75rem;color:#9ca3af;margin-top:0.25rem;">produk</div>
        </div>
    @endforeach
</div>

{{-- Realtime indicator --}}
<div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;font-size:0.8rem;color:#6b7280;">
    <span id="realtime-dot" style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;animation:pulse 2s infinite;"></span>
    <span id="realtime-status">Memuat data realtime...</span>
    <span id="realtime-time" style="margin-left:auto;"></span>
</div>
<style>
@keyframes pulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(1.3); } }
</style>

{{-- Produk Terbaru --}}
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div class="card-title" style="margin:0;">🆕 Produk Terbaru</div>
        <a href="{{ route('admin.produk.index') }}" style="font-size:0.8rem;color:#1E88E5;text-decoration:none;">Lihat semua →</a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Pabrik/Merek</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="produk-tbody">
                @forelse($latestProduk as $p)
                    <tr>
                        <td><strong>{{ $p->nama_obat }}</strong></td>
                        <td>
                            @php $icon = match($p->kategori_produk) { 'SKINCARE & KOSMETIK' => '✨', 'ALAT KESEHATAN' => '🩺', default => '💊' }; @endphp
                            <span style="font-size:0.82rem;">{{ $icon }} {{ $p->kategori_produk }}</span>
                        </td>
                        <td style="font-size:0.82rem;color:#6b7280;">{{ $p->kategori }}</td>
                        <td>{{ $p->getFormattedPrice() }}</td>
                        <td>
                            @if($p->stok > 10)
                                <span style="color:#10b981;font-weight:700;">{{ $p->stok }}</span>
                            @elseif($p->stok > 0)
                                <span style="color:#f59e0b;font-weight:700;">{{ $p->stok }}</span>
                            @else
                                <span style="color:#ef4444;font-weight:700;">Habis</span>
                            @endif
                        </td>
                        <td style="font-size:0.82rem;color:#9ca3af;">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td><a href="{{ route('admin.produk.edit', $p->id) }}" class="btn btn-secondary btn-sm">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:#6b7280;padding:2rem;">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Riwayat Pembelian Terbaru --}}
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div class="card-title" style="margin:0;">🧾 Riwayat Pembelian</div>
        <a href="{{ route('admin.purchase-history.index') }}" style="font-size:0.8rem;color:#1E88E5;text-decoration:none;">Lihat semua →</a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pembeli</th>
                    <th>Jenis</th>
                    <th>Total</th>
                    <th>Nomor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td style="font-size:0.82rem;color:#6b7280;">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td><strong>{{ $order->buyer_name }}</strong></td>
                        <td>{{ $order->buyer_type === 'apotik' ? 'Apotik' : 'Umum' }}</td>
                        <td>{{ 'Rp ' . number_format($order->total, 0, ',', '.') }}</td>
                        <td style="font-size:0.82rem;color:#6b7280;">{{ $order->sia ?? $order->sipa ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:#6b7280;padding:2rem;">Belum ada pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Data Live Banner --}}
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div class="card-title" style="margin:0;">🖼️ Data Live Banner</div>
        <a href="{{ route('admin.banners.index') }}" style="font-size:0.8rem;color:#1E88E5;text-decoration:none;">Kelola banner →</a>
    </div>
    @if($totalBanners === 0)
        <p style="text-align:center;color:#6b7280;padding:1.5rem 0;">Belum ada banner. <a href="{{ route('admin.banners.create') }}" style="color:#1E88E5;">Tambah sekarang →</a></p>
    @else
        <div style="margin-bottom:0.75rem;font-size:0.82rem;color:#6b7280;">
            <span style="color:#10b981;font-weight:600;">{{ $activeBanners }}</span> aktif &nbsp;/&nbsp;
            <span style="color:#6b7280;">{{ $totalBanners - $activeBanners }}</span> nonaktif
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Judul</th>
                        <th>Subjudul</th>
                        <th>URL Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestBanners as $banner)
                        <tr>
                            <td style="text-align:center;font-weight:700;color:#6b7280;">{{ $banner->urutan }}</td>
                            <td><strong>{{ $banner->judul }}</strong></td>
                            <td style="font-size:0.82rem;color:#6b7280;">{{ $banner->subjudul ?? '-' }}</td>
                            <td style="font-size:0.82rem;color:#6b7280;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $banner->url_tujuan ?? '-' }}
                            </td>
                            <td>
                                @if($banner->aktif)
                                    <span style="background:#d1fae5;color:#065f46;padding:2px 10px;border-radius:999px;font-size:0.75rem;font-weight:600;">Aktif</span>
                                @else
                                    <span style="background:#f3f4f6;color:#6b7280;padding:2px 10px;border-radius:999px;font-size:0.75rem;font-weight:600;">Nonaktif</span>
                                @endif
                            </td>
                            <td><a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-secondary btn-sm">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Quick Actions --}}
<div class="card">
    <div class="card-title">⚡ Aksi Cepat</div>
    <div class="quick-actions-grid" style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
        <a href="{{ route('admin.produk.import') }}" class="btn btn-secondary">
            <i class="fa-solid fa-file-import"></i> Import Excel
        </a>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-list"></i> Semua Produk
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script>
async function fetchStats() {
    try {
        const res  = await fetch('{{ route("admin.dashboard.stats") }}');
        const data = await res.json();
        document.getElementById('stat-total').textContent   = data.total;
        document.getElementById('stat-lowstok').textContent = data.lowStok;
        document.getElementById('realtime-status').textContent = 'Realtime aktif';
        document.getElementById('realtime-dot').style.background = '#10b981';
        const now = new Date();
        document.getElementById('realtime-time').textContent = 'Update: ' + now.toLocaleTimeString('id-ID');
    } catch (e) {
        document.getElementById('realtime-status').textContent = 'Gagal memuat data';
        document.getElementById('realtime-dot').style.background = '#ef4444';
    }
}
fetchStats();
setInterval(fetchStats, 15000);
</script>
@endsection
