@extends('layouts.frontend')

@section('title', 'Farmakologi - Medikpedia')

@section('styles')
<style>
    /* ===== HEADER ===== */
    .farma-header {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }
    .farma-header::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(124,179,66,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .farma-header .header-deco-icon {
        position: absolute;
        color: rgba(255,255,255,0.08);
        pointer-events: none;
        animation: headerIconFloat 6s ease-in-out infinite;
    }
    .farma-header .header-deco-icon-1 { bottom: 10px; right: 12%; font-size: 4rem; animation-delay: 0s; }
    .farma-header .header-deco-icon-2 { top: 15px;   right: 28%; font-size: 3rem; animation-delay: 2s; }
    .farma-header .header-deco-icon-3 { bottom: 20px; right: 40%; font-size: 2.5rem; animation-delay: 4s; }
    @keyframes headerIconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.08; }
        50%       { transform: translateY(-12px) rotate(8deg); opacity: 0.15; }
    }
    .farma-header h1 { font-size: clamp(2rem,4vw,3rem); font-weight: 800; color: white; margin-bottom: 0.5rem; position: relative; }
    .farma-header p  { color: rgba(255,255,255,0.8); font-size: 1rem; position: relative; }
    .breadcrumb-custom { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem; position: relative; }
    .breadcrumb-custom a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
    .breadcrumb-custom a:hover { color: white; }
    .breadcrumb-custom span { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .breadcrumb-custom .current { color: #a5d65a; font-size: 0.9rem; font-weight: 600; }

    /* ===== MAIN LAYOUT ===== */
    .farma-main {
        background: transparent;
        padding: 2.5rem 0 5rem;
        min-height: 60vh;
    }

    .farma-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 1.75rem;
        align-items: start;
    }

    /* ===== SIDEBAR ===== */
    .farma-sidebar {
        position: sticky;
        top: 80px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(30,136,229,0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .sidebar-header {
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        padding: 1rem 1.25rem;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-search {
        padding: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .sidebar-search input {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .sidebar-search input:focus {
        border-color: #1E88E5;
        box-shadow: 0 0 0 3px rgba(30,136,229,0.1);
    }

    .sidebar-list {
        max-height: 65vh;
        overflow-y: auto;
        padding: 0.5rem 0;
    }

    .sidebar-list::-webkit-scrollbar { width: 4px; }
    .sidebar-list::-webkit-scrollbar-track { background: #f9fafb; }
    .sidebar-list::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 1.25rem;
        cursor: pointer;
        font-size: 0.82rem;
        color: #374151;
        transition: all 0.2s;
        border-left: 3px solid transparent;
        line-height: 1.4;
    }

    .sidebar-item:hover {
        background: #eff6ff;
        color: #1E88E5;
        border-left-color: #1E88E5;
    }

    .sidebar-item.active {
        background: #eff6ff;
        color: #1E88E5;
        border-left-color: #1E88E5;
        font-weight: 600;
    }

    .sidebar-item i {
        font-size: 0.75rem;
        flex-shrink: 0;
        color: #7CB342;
    }

    /* ===== CONTENT AREA ===== */
    .farma-content {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* ===== DISEASE CARD ===== */
    .disease-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: box-shadow 0.3s, transform 0.3s;
    }

    .disease-card:hover {
        box-shadow: 0 8px 30px rgba(30,136,229,0.12);
        transform: translateY(-2px);
    }

    .disease-card-header {
        background: linear-gradient(135deg, #1E88E5 0%, #1565C0 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        user-select: none;
    }

    .disease-card-header:hover {
        background: linear-gradient(135deg, #1565C0 0%, #0D47A1 100%);
    }

    .disease-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: white;
        flex-shrink: 0;
    }

    .disease-title {
        flex: 1;
        font-size: 0.95rem;
        font-weight: 700;
        color: white;
        line-height: 1.3;
    }

    .disease-toggle {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        transition: transform 0.3s;
    }

    .disease-card.open .disease-toggle {
        transform: rotate(180deg);
    }

    .disease-body {
        display: none;
        padding: 0;
    }

    .disease-card.open .disease-body {
        display: block;
    }

    /* ===== SYMPTOM ROW ===== */
    .symptom-row {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
        align-items: start;
    }

    .symptom-row:last-child { border-bottom: none; }
    .symptom-row:nth-child(even) { background: #fafbff; }

    .symptom-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: flex-start;
        gap: 0.4rem;
        line-height: 1.5;
    }

    .symptom-label i { color: #f59e0b; margin-top: 2px; flex-shrink: 0; }

    .komposisi-text {
        font-size: 0.78rem;
        color: #6b7280;
        line-height: 1.6;
        font-style: italic;
    }

    .obat-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
    }

    .obat-tag {
        display: inline-block;
        background: #e3f2fd;
        color: #1565C0;
        padding: 0.2rem 0.55rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .obat-tag:hover {
        background: #1E88E5;
        color: white;
        cursor: default;
    }

    .dosis-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #f0fdf4;
        color: #166534;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #bbf7d0;
        white-space: nowrap;
    }

    /* ===== COLUMN HEADERS ===== */
    .symptom-header {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
        padding: 0.6rem 1.5rem;
        background: #f8faff;
        border-bottom: 2px solid #e5e7eb;
    }

    .symptom-header span {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ===== SEARCH RESULT HIGHLIGHT ===== */
    .highlight { background: #fef08a; border-radius: 2px; }

    /* ===== EMPTY STATE ===== */
    .farma-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .farma-empty i { font-size: 3rem; color: #d1d5db; margin-bottom: 1rem; display: block; }
    .farma-empty h3 { font-size: 1.2rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem; }
    .farma-empty p { color: #9ca3af; font-size: 0.9rem; }

    /* ===== STATS BAR ===== */
    .farma-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .farma-stat-card {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .farma-stat-card .num {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1E88E5;
        display: block;
    }

    .farma-stat-card .lbl {
        font-size: 0.78rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .farma-layout { grid-template-columns: 220px 1fr; gap: 1.25rem; }
    }

    @media (max-width: 768px) {
        .farma-main { padding: 1.75rem 0 3rem; }
        .farma-layout { grid-template-columns: 1fr; gap: 1rem; }
        .farma-sidebar { position: static !important; top: auto !important; }
        .sidebar-list { max-height: 180px; }
        .symptom-row { grid-template-columns: 1fr; gap: 0.5rem; }
        .symptom-header { display: none; }
        .farma-stats { grid-template-columns: repeat(3, 1fr); gap: 0.6rem; }
        .farma-stat-card { padding: 0.75rem; }
        .farma-stat-card .num { font-size: 1.3rem; }
        .disease-card-header { padding: 0.85rem 1rem; }
        .symptom-row { padding: 0.85rem 1rem; }
    }

    @media (max-width: 480px) {
        .farma-stats { grid-template-columns: repeat(3, 1fr); gap: 0.4rem; }
        .farma-stat-card { padding: 0.6rem 0.5rem; }
        .farma-stat-card .num { font-size: 1.1rem; }
        .farma-stat-card .lbl { font-size: 0.68rem; }
        .obat-tag { font-size: 0.68rem; padding: 0.15rem 0.45rem; }
        .dosis-badge { font-size: 0.7rem; padding: 0.2rem 0.5rem; }
    }
</style>
@endsection

@section('content')

<div class="farma-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Farmakologi</span>
        </div>
        <h1><i class="fa-solid fa-book-medical"></i> Informasi Farmakologi</h1>
        <p>Panduan penyakit, gejala, komposisi obat, dan dosis penggunaan</p>
    </div>
    <i class="fa-solid fa-book-medical header-deco-icon header-deco-icon-1"></i>
    <i class="fa-solid fa-pills header-deco-icon header-deco-icon-2"></i>
    <i class="fa-solid fa-stethoscope header-deco-icon header-deco-icon-3"></i>
</div>

<div class="farma-main">
    <div class="container">

        {{-- Stats --}}
        <div class="farma-stats">
            <div class="farma-stat-card">
                <span class="num">{{ count($diseases) }}</span>
                <span class="lbl">Jenis Penyakit</span>
            </div>
            <div class="farma-stat-card">
                <span class="num">{{ collect($diseases)->sum(fn($d) => count($d['symptoms'])) }}</span>
                <span class="lbl">Total Gejala</span>
            </div>
            <div class="farma-stat-card">
                <span class="num">500+</span>
                <span class="lbl">Nama Obat</span>
            </div>
        </div>

        {{-- Search bar atas --}}
        <div style="margin-bottom:1.25rem;">
            <div style="position:relative; max-width:500px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#9ca3af;"></i>
                <input type="text" id="mainSearch" placeholder="Cari penyakit, gejala, atau nama obat..."
                    style="width:100%;padding:0.8rem 1rem 0.8rem 2.75rem;border:1.5px solid #e5e7eb;border-radius:12px;font-size:0.95rem;outline:none;background:white;box-shadow:0 2px 8px rgba(0,0,0,0.06);"
                    oninput="filterDiseases(this.value)">
            </div>
        </div>

        <div class="farma-layout">

            {{-- Sidebar --}}
            <aside class="farma-sidebar">
                <div class="sidebar-header">
                    <i class="fa-solid fa-list-ul"></i> Daftar Penyakit
                </div>
                <div class="sidebar-search">
                    <input type="text" id="sidebarSearch" placeholder="Filter penyakit..." oninput="filterSidebar(this.value)">
                </div>
                <div class="sidebar-list" id="sidebarList">
                    @foreach($diseases as $i => $disease)
                    <div class="sidebar-item" onclick="scrollToDisease({{ $i }})" id="sidebar-{{ $i }}">
                        <i class="fa-solid fa-circle-dot"></i>
                        {{ $disease['name'] }}
                    </div>
                    @endforeach
                </div>
            </aside>

            {{-- Content --}}
            <div class="farma-content" id="diseaseList">
                @foreach($diseases as $i => $disease)
                <div class="disease-card" id="disease-{{ $i }}" data-name="{{ strtolower($disease['name']) }}">
                    <div class="disease-card-header" onclick="toggleCard({{ $i }})">
                        <div class="disease-icon">
                            <i class="{{ $disease['icon'] }}"></i>
                        </div>
                        <div class="disease-title">{{ $disease['name'] }}</div>
                        <div style="background:rgba(255,255,255,0.2);padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;color:white;margin-right:0.5rem;">
                            {{ count($disease['symptoms']) }} gejala
                        </div>
                        <i class="fa-solid fa-chevron-down disease-toggle"></i>
                    </div>
                    <div class="disease-body">
                        <div class="symptom-header">
                            <span><i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;margin-right:0.3rem;"></i> Gejala</span>
                            <span><i class="fa-solid fa-flask" style="color:#6366f1;margin-right:0.3rem;"></i> Komposisi / Generik</span>
                            <span><i class="fa-solid fa-pills" style="color:#1E88E5;margin-right:0.3rem;"></i> Nama Obat & Dosis</span>
                        </div>
                        @foreach($disease['symptoms'] as $symptom)
                        <div class="symptom-row">
                            <div class="symptom-label">
                                <i class="fa-solid fa-chevron-right"></i>
                                {{ $symptom['gejala'] }}
                            </div>
                            <div class="komposisi-text">{{ $symptom['komposisi'] }}</div>
                            <div>
                                <div class="obat-list" style="margin-bottom:0.4rem;">
                                    @foreach(explode('/', $symptom['obat']) as $obat)
                                        @if(trim($obat))
                                        <span class="obat-tag">{{ trim($obat) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                                @if($symptom['dosis'])
                                <div class="dosis-badge">
                                    <i class="fa-solid fa-clock"></i>
                                    {{ $symptom['dosis'] }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="farma-empty" id="emptyState" style="display:none;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h3>Tidak ditemukan</h3>
                    <p>Coba kata kunci lain</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleCard(i) {
    const card = document.getElementById('disease-' + i);
    card.classList.toggle('open');
    // Update sidebar active
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    document.getElementById('sidebar-' + i)?.classList.add('active');
}

function scrollToDisease(i) {
    const card = document.getElementById('disease-' + i);
    if (!card) return;
    card.classList.add('open');
    card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    document.getElementById('sidebar-' + i)?.classList.add('active');
}

function filterDiseases(q) {
    q = q.toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('.disease-card').forEach(card => {
        const text = card.innerText.toLowerCase();
        const match = !q || text.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) { visible++; if (q) card.classList.add('open'); }
    });
    document.getElementById('emptyState').style.display = visible === 0 ? '' : 'none';
    // Sync sidebar search
    document.getElementById('sidebarSearch').value = q;
    filterSidebar(q);
}

function filterSidebar(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('.sidebar-item').forEach(item => {
        const match = !q || item.innerText.toLowerCase().includes(q);
        item.style.display = match ? '' : 'none';
    });
}

// Buka card pertama by default
document.addEventListener('DOMContentLoaded', () => {
    const first = document.querySelector('.disease-card');
    if (first) first.classList.add('open');
    const firstSidebar = document.querySelector('.sidebar-item');
    if (firstSidebar) firstSidebar.classList.add('active');
});
</script>

@endsection
