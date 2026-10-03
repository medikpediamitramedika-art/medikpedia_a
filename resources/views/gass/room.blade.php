@extends('layouts.frontend')

@section('title', 'Ruang File GASS - Medikpedia')

@section('styles')
<style>
    .gass-room { max-width: 900px; margin: 0 auto; padding: calc(var(--navbar-height, 65px) + 1.5rem) 1rem 3rem; }
    .gass-card { padding: clamp(1.25rem, 4vw, 2.25rem); background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 12px 36px rgba(31, 41, 55, .1); }
    .gass-heading { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .gass-heading img { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; }
    .gass-heading h1 { color: #1565c0; font-size: clamp(1.4rem, 4vw, 2rem); }
    .gass-heading p, .gass-note { color: #64748b; }
    .gass-form { display: grid; gap: .75rem; margin-top: 1.5rem; }
    .gass-form label { font-weight: 700; color: #334155; }
    .gass-form input[type="password"], .gass-form input[type="file"] { width: 100%; padding: .8rem; border: 1px solid #cbd5e1; border-radius: 10px; background: #fff; }
    .gass-search { display: flex; flex-wrap: wrap; gap: .5rem; width: 100%; margin-top: 1.5rem; }
    .gass-search input { flex: 1 1 220px; min-width: 0; padding: .8rem; border: 1px solid #cbd5e1; border-radius: 10px; }
    .gass-button { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; width: fit-content; padding: .8rem 1.1rem; border: 0; border-radius: 10px; background: #1565c0; color: #fff; font-weight: 700; text-decoration: none; }
    .gass-button:hover { background: #0d47a1; }
    .gass-button-secondary { background: #475569; }
    .gass-error { color: #b91c1c; }
    .gass-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
    .gass-files { width: 100%; margin-top: 1rem; border-collapse: collapse; }
    .gass-files th, .gass-files td { padding: .8rem .5rem; border-bottom: 1px solid #e2e8f0; text-align: left; overflow-wrap: anywhere; }
    .gass-files th { color: #475569; font-size: .85rem; }
    .gass-file-actions { display: flex; flex-wrap: wrap; gap: .4rem; }
    .gass-delete-form { margin: 0; }
    .gass-button-danger { background: #b91c1c; }
    .gass-button-danger:hover { background: #991b1b; }
    .gass-empty { margin-top: 1rem; padding: 1.25rem; border: 1px dashed #cbd5e1; border-radius: 12px; color: #64748b; text-align: center; }
    @media (max-width: 560px) {
        .gass-files th:nth-child(2), .gass-files td:nth-child(2) { display: none; }
        .gass-heading img { width: 52px; height: 52px; }
    }
</style>
@endsection

@section('content')
<section class="gass-room">
    <div class="gass-card">
        <header class="gass-heading">
            <img src="{{ asset('logo gass.jpeg') }}" alt="Logo GASS">
            <div>
                <h1>Ruang File GASS</h1>
                <p>Unggah dan unduh file bersama.</p>
            </div>
        </header>

        <form class="gass-form" action="{{ route('gass.files.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="files">Pilih satu atau beberapa file</label>
            <input id="files" name="files[]" type="file" multiple required>
            <p class="gass-note">Semua jenis file diterima. Batas ukuran upload mengikuti konfigurasi server hosting.</p>
            @error('files')
                <span class="gass-error">{{ $message }}</span>
            @enderror
            @error('files.*')
                <span class="gass-error">{{ $message }}</span>
            @enderror
            <button class="gass-button" type="submit"><i class="fa-solid fa-upload"></i> Unggah File</button>
        </form>

        <form class="gass-search" action="{{ route('gass.room') }}" method="GET" role="search">
            <label class="sr-only" for="gass-file-search">Cari nama file</label>
            <input id="gass-file-search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama file...">
            <button class="gass-button" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            @if ($search !== '')
                <a class="gass-button gass-button-secondary" href="{{ route('gass.room') }}">Hapus pencarian</a>
            @endif
        </form>

        <div class="gass-toolbar">
            <h2>{{ $search !== '' ? 'Hasil pencarian' : 'File di ruang ini' }} ({{ $files->count() }})</h2>
            <form action="{{ route('gass.leave') }}" method="POST">
                @csrf
                <button class="gass-button gass-button-secondary" type="submit">Keluar</button>
            </form>
        </div>

        @if ($files->isEmpty())
            <p class="gass-empty">{{ $search !== '' ? 'Tidak ada file yang cocok dengan pencarian.' : 'Belum ada file. Unggah file pertama ke ruang ini.' }}</p>
        @else
            <table class="gass-files">
                <thead>
                    <tr>
                        <th>Nama file</th>
                        <th>Ukuran</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        @php
                            $fileSize = $file->size_bytes;
                            $fileSizeUnits = ['B', 'KB', 'MB', 'GB', 'TB'];
                            $fileSizeUnit = 0;
                            while ($fileSize >= 1024 && $fileSizeUnit < count($fileSizeUnits) - 1) {
                                $fileSize /= 1024;
                                $fileSizeUnit++;
                            }
                        @endphp
                        <tr>
                            <td>{{ $file->original_name }}</td>
                            <td>{{ number_format($fileSize, $fileSizeUnit === 0 ? 0 : 1) }} {{ $fileSizeUnits[$fileSizeUnit] }}</td>
                            <td>
                                <div class="gass-file-actions">
                                    <a class="gass-button" href="{{ route('gass.files.view', $file) }}" target="_blank" rel="noopener">Lihat</a>
                                    <a class="gass-button gass-button-secondary" href="{{ route('gass.files.download', $file) }}">Unduh</a>
                                    <form class="gass-delete-form" action="{{ route('gass.files.destroy', $file) }}" method="POST" onsubmit="return confirm('Hapus file ini? Tindakan ini tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        @if ($search !== '')
                                            <input type="hidden" name="search" value="{{ $search }}">
                                        @endif
                                        <button class="gass-button gass-button-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</section>
@endsection
