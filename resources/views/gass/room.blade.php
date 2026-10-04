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
    .gass-dropzone { position: relative; display: grid; justify-items: center; gap: .35rem; padding: 1.5rem 1rem; border: 2px dashed #94a3b8; border-radius: 12px; color: #334155; text-align: center; cursor: pointer; }
    .gass-dropzone i { color: #1565c0; font-size: 1.5rem; }
    .gass-dropzone small { color: #64748b; font-weight: 400; }
    .gass-dropzone input[type="file"] { position: absolute; inset: 0; width: 100%; height: 100%; padding: 0; border: 0; opacity: 0; cursor: pointer; }
    .gass-dropzone:focus-within { outline: 3px solid rgba(21, 101, 192, .35); outline-offset: 3px; }
    .gass-dropzone.is-dragging { border-color: #1565c0; background: #eff6ff; }
    .gass-upload-summary { margin: 0; color: #475569; }
    .gass-selected-files { display: grid; gap: .4rem; margin: 0; padding: 0; list-style: none; }
    .gass-selected-file { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: .55rem .7rem; border: 1px solid #e2e8f0; border-radius: 8px; }
    .gass-selected-file-name { min-width: 0; overflow-wrap: anywhere; }
    .gass-selected-file-size { display: block; color: #64748b; font-size: .85rem; font-weight: 400; }
    .gass-remove-file { flex: 0 0 auto; padding: .35rem .5rem; border: 0; background: transparent; color: #b91c1c; font-weight: 700; cursor: pointer; }
    .gass-remove-file:hover { color: #991b1b; text-decoration: underline; }
    .gass-button:disabled { opacity: .55; cursor: not-allowed; }
    .gass-error[hidden] { display: none; }
    .gass-search { display: flex; flex-wrap: wrap; gap: .5rem; width: 100%; margin-top: 1.5rem; }
    .gass-search input, .gass-search select { flex: 1 1 180px; min-width: 0; padding: .8rem; border: 1px solid #cbd5e1; border-radius: 10px; background: #fff; }
    .gass-button { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; width: fit-content; padding: .8rem 1.1rem; border: 0; border-radius: 10px; background: #1565c0; color: #fff; font-weight: 700; text-decoration: none; }
    .gass-button:hover { background: #0d47a1; }
    .gass-button-secondary { background: #475569; }
    .gass-error { color: #b91c1c; }
    .gass-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
    .gass-files { width: 100%; margin-top: 1rem; border-collapse: collapse; }
    .gass-files th, .gass-files td { padding: .8rem .5rem; border-bottom: 1px solid #e2e8f0; text-align: left; overflow-wrap: anywhere; }
    .gass-files th:first-child, .gass-files td:first-child { width: 3rem; white-space: nowrap; }
    .gass-files th { color: #475569; font-size: .85rem; }
    .gass-file-actions { display: flex; flex-wrap: wrap; gap: .4rem; }
    .gass-delete-form { margin: 0; }
    .gass-button-danger { background: #b91c1c; }
    .gass-button-danger:hover { background: #991b1b; }
    .gass-empty { margin-top: 1rem; padding: 1.25rem; border: 1px dashed #cbd5e1; border-radius: 12px; color: #64748b; text-align: center; }
    @media (max-width: 560px) {
        .gass-files th:nth-child(4), .gass-files td:nth-child(4) { display: none; }
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
            <label class="gass-dropzone" id="gass-dropzone" for="files">
                <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                <span>Tarik file ke sini atau klik untuk memilih</span>
                <small>Pilih maksimal 5 file sekaligus</small>
                <input id="files" name="files[]" type="file" multiple required aria-describedby="gass-file-help">
            </label>
            <p class="gass-note" id="gass-file-help">Semua jenis file diterima. Batas ukuran upload mengikuti konfigurasi server hosting.</p>
            <p class="gass-upload-summary" id="gass-file-summary" role="status" aria-live="polite">Belum ada file dipilih.</p>
            <ul class="gass-selected-files" id="gass-selected-files" aria-label="File yang akan diunggah"></ul>
            <p class="gass-error" id="gass-file-limit-error" role="alert" hidden></p>
            @error('files')
                <span class="gass-error">{{ $message }}</span>
            @enderror
            @error('files.*')
                <span class="gass-error">{{ $message }}</span>
            @enderror
            <button class="gass-button" id="gass-upload-button" type="submit"><i class="fa-solid fa-upload"></i> Unggah File</button>
        </form>

        <form class="gass-search" action="{{ route('gass.room') }}" method="GET" role="search">
            <label class="sr-only" for="gass-file-search">Cari nama file</label>
            <input id="gass-file-search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama file...">
            <label class="sr-only" for="gass-upload-date">Tanggal unggah</label>
            <input id="gass-upload-date" type="date" name="uploaded_on" value="{{ $uploadedOn }}">
            <label class="sr-only" for="gass-file-sort">Urutkan file</label>
            <select id="gass-file-sort" name="sort">
                <option value="newest" @selected($sort === 'newest')>Terbaru</option>
                <option value="name_asc" @selected($sort === 'name_asc')>Nama A-Z</option>
                <option value="size_desc" @selected($sort === 'size_desc')>Ukuran terbesar</option>
            </select>
            <button class="gass-button" type="submit"><i class="fa-solid fa-filter"></i> Terapkan</button>
            @if ($search !== '' || $uploadedOn !== '' || $sort !== 'newest')
                <a class="gass-button gass-button-secondary" href="{{ route('gass.room') }}">Hapus filter</a>
            @endif
        </form>

        <div class="gass-toolbar">
            <h2>{{ $search !== '' || $uploadedOn !== '' ? 'Hasil filter' : 'File di ruang ini' }} ({{ $files->count() }})</h2>
            <form action="{{ route('gass.leave') }}" method="POST">
                @csrf
                <button class="gass-button gass-button-secondary" type="submit">Keluar</button>
            </form>
        </div>

        @if ($files->isEmpty())
            <p class="gass-empty">{{ $search !== '' || $uploadedOn !== '' ? 'Tidak ada file yang cocok dengan filter.' : 'Belum ada file. Unggah file pertama ke ruang ini.' }}</p>
        @else
            <table class="gass-files">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th>Nama file</th>
                        <th>Tanggal unggah</th>
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
                            $uploadedAt = $file->created_at->timezone(config('gass.timezone'));
                            $shareMessage = implode("\n", [
                                'File Ruang GASS',
                                'Nama file: '.$file->original_name,
                                'Tanggal unggah: '.$uploadedAt->format('d/m/Y H:i'),
                                'Kode akses: '.config('gass.access_code'),
                                'Buka ruang: '.route('gass.room'),
                            ]);
                        @endphp
                        <tr>
                            <td class="gass-file-number">{{ $loop->iteration }}</td>
                            <td>{{ $file->original_name }}</td>
                            <td>{{ $uploadedAt->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($fileSize, $fileSizeUnit === 0 ? 0 : 1) }} {{ $fileSizeUnits[$fileSizeUnit] }}</td>
                            <td>
                                <div class="gass-file-actions">
                                    <a class="gass-button" href="{{ route('gass.files.view', $file) }}" target="_blank" rel="noopener">Lihat</a>
                                    <a class="gass-button gass-button-secondary" href="{{ route('gass.files.download', $file) }}">Unduh</a>
                                    <a class="gass-button" href="https://wa.me/?text={{ rawurlencode($shareMessage) }}" target="_blank" rel="noopener" aria-label="Bagikan {{ $file->original_name }} melalui WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
                                    <form class="gass-delete-form" action="{{ route('gass.files.destroy', $file) }}" method="POST" onsubmit="return confirm('Hapus file ini? Tindakan ini tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        @if ($search !== '')
                                            <input type="hidden" name="search" value="{{ $search }}">
                                        @endif
                                        @if ($uploadedOn !== '')
                                            <input type="hidden" name="uploaded_on" value="{{ $uploadedOn }}">
                                        @endif
                                        @if ($sort !== 'newest')
                                            <input type="hidden" name="sort" value="{{ $sort }}">
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

<script>
    (() => {
        const fileInput = document.getElementById('files');
        const dropzone = document.getElementById('gass-dropzone');
        const fileList = document.getElementById('gass-selected-files');
        const summary = document.getElementById('gass-file-summary');
        const uploadButton = document.getElementById('gass-upload-button');
        const limitError = document.getElementById('gass-file-limit-error');
        const maxFiles = 5;

        if (!fileInput || !dropzone || !fileList || !summary || !uploadButton || !limitError) return;

        let selectedFiles = [];

        const formatSize = (bytes) => {
            if (bytes === 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const unitIndex = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
            const size = bytes / (1024 ** unitIndex);

            return `${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 }).format(size)} ${units[unitIndex]}`;
        };

        const syncInput = () => {
            if (typeof DataTransfer === 'undefined') return false;

            const transfer = new DataTransfer();
            selectedFiles.forEach((file) => transfer.items.add(file));
            fileInput.files = transfer.files;

            return true;
        };

        const renderFiles = () => {
            fileList.replaceChildren();
            selectedFiles.forEach((file, index) => {
                const item = document.createElement('li');
                const name = document.createElement('span');
                const size = document.createElement('small');
                const remove = document.createElement('button');

                item.className = 'gass-selected-file';
                name.className = 'gass-selected-file-name';
                name.textContent = file.name;
                size.className = 'gass-selected-file-size';
                size.textContent = formatSize(file.size);
                name.append(size);
                remove.className = 'gass-remove-file';
                remove.type = 'button';
                remove.textContent = 'Hapus';
                remove.setAttribute('aria-label', `Hapus ${file.name}`);
                remove.disabled = typeof DataTransfer === 'undefined';
                remove.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    syncInput();
                    renderFiles();
                });

                item.append(name, remove);
                fileList.append(item);
            });

            const totalSize = selectedFiles.reduce((total, file) => total + file.size, 0);
            summary.textContent = selectedFiles.length
                ? `${selectedFiles.length} file dipilih · ${formatSize(totalSize)} total`
                : 'Belum ada file dipilih.';
            uploadButton.disabled = selectedFiles.length === 0;
        };

        const addFiles = (files) => {
            const availableSlots = maxFiles - selectedFiles.length;
            const acceptedFiles = files.slice(0, availableSlots);
            selectedFiles.push(...acceptedFiles);

            if (acceptedFiles.length < files.length) {
                const rejectedCount = files.length - acceptedFiles.length;
                limitError.textContent = `Maksimal ${maxFiles} file. ${rejectedCount} file tambahan tidak ditambahkan.`;
                limitError.hidden = false;
            } else {
                limitError.textContent = '';
                limitError.hidden = true;
            }

            syncInput();
            renderFiles();
        };

        fileInput.addEventListener('change', () => addFiles(Array.from(fileInput.files)));

        ['dragenter', 'dragover'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropzone.classList.add('is-dragging');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropzone.classList.remove('is-dragging');
            });
        });

        dropzone.addEventListener('drop', (event) => addFiles(Array.from(event.dataTransfer.files)));
        renderFiles();
    })();
</script>
@endsection
