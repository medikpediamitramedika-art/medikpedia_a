

<?php $__env->startSection('title', 'Import Produk - Admin Medikpedia'); ?>
<?php $__env->startSection('page-title', '📥 Import Produk'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .form-card { background:white; border-radius:0.75rem; box-shadow:0 1px 4px rgba(0,0,0,0.06); border:1px solid #f0f0f0; overflow:hidden; }
    .form-card-header { padding:1rem 1.5rem; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:0.6rem; }
    .form-card-header h3 { font-size:0.95rem; font-weight:700; color:#1f2937; margin:0; }
    .header-icon { width:32px; height:32px; background:#e3f2fd; border-radius:0.4rem; display:flex; align-items:center; justify-content:center; color:#1E88E5; font-size:0.9rem; }
    .form-body { padding:1.5rem; }
    .form-footer { padding:1rem 1.5rem; border-top:1px solid #f3f4f6; display:flex; gap:0.6rem; align-items:center; }
    .btn-save { display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1.5rem; background:#1E88E5; color:white; border:none; border-radius:0.5rem; font-size:0.9rem; font-weight:700; cursor:pointer; transition:all 0.2s; }
    .btn-save:hover { background:#1565C0; transform:translateY(-1px); }
    .btn-save:disabled { background:#d1d5db; cursor:not-allowed; transform:none; }
    .btn-cancel { display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1.25rem; background:white; color:#6b7280; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:0.9rem; font-weight:600; text-decoration:none; transition:all 0.2s; }
    .btn-cancel:hover { background:#f9fafb; color:#374151; }
    .upload-zone { border:2px dashed #d1d5db; border-radius:0.6rem; padding:2rem 1rem; text-align:center; background:#fafafa; cursor:pointer; transition:all 0.2s; }
    .upload-zone:hover, .upload-zone.drag-over { border-color:#1E88E5; background:#f0f7ff; }
    .upload-zone .upload-icon { font-size:2.5rem; margin-bottom:0.5rem; }
    .upload-zone p { font-size:0.85rem; color:#6b7280; margin:0 0 0.75rem; }
    .upload-zone small { font-size:0.75rem; color:#9ca3af; display:block; margin-top:0.5rem; }
    .btn-choose { display:inline-flex; align-items:center; gap:0.35rem; padding:0.45rem 1rem; background:white; border:1px solid #d1d5db; border-radius:0.4rem; font-size:0.82rem; font-weight:600; color:#374151; cursor:pointer; transition:all 0.2s; }
    .btn-choose:hover { background:#f3f4f6; border-color:#9ca3af; }
    .info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:0.75rem; padding:1.25rem 1.5rem; margin-bottom:1.5rem; }
    .info-box h3 { color:#1d4ed8; margin:0 0 0.75rem; font-size:0.95rem; font-weight:700; }
    .info-box ol { color:#1e40af; margin:0; padding-left:1.25rem; line-height:1.8; font-size:0.9rem; }
    .template-table { margin-top:1rem; background:#f9fafb; border-radius:0.5rem; padding:1rem; overflow-x:auto; }
    .template-table table { width:100%; border-collapse:collapse; font-size:0.8rem; }
    .template-table thead tr { background:#e5e7eb; }
    .template-table th { padding:0.4rem 0.75rem; text-align:left; border:1px solid #d1d5db; font-weight:700; color:#374151; }
    .template-table td { padding:0.4rem 0.75rem; border:1px solid #d1d5db; color:#6b7280; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;color:#9ca3af;margin-bottom:1.25rem;">
    <a href="<?php echo e(route('admin.produk.index')); ?>" style="color:#1E88E5;text-decoration:none;font-weight:600;">🛒 Produk Kami</a>
    <i class="fa-solid fa-chevron-right" style="font-size:0.65rem;"></i>
    <span style="color:#374151;font-weight:600;">Import Produk</span>
</div>

<div class="info-box">
    <h3><i class="fa-solid fa-circle-info"></i> Petunjuk Import</h3>
    <ol>
        <li>Download Template Excel (.xlsx) di bawah</li>
        <li>Isi data produk sesuai kolom yang tersedia</li>
        <li>Kolom <strong>KATEGORI</strong> harus salah satu dari: <strong>OBAT</strong>, <strong>SKINCARE & KOSMETIK</strong>, <strong>ALAT KESEHATAN</strong></li>
        <li>Upload file <strong>.csv</strong>, <strong>.xls</strong>, atau <strong>.xlsx</strong></li>
        <li>Data yang sudah ada (SKU atau nama produk sama) akan diperbarui, data baru akan ditambahkan</li>
        <li>Struktur file tidak harus persis sama; selama header yang dikenali ada, data tetap bisa dimasukkan</li>
    </ol>
    <p style="color:#059669;margin:0.75rem 0 0;font-size:0.875rem;font-weight:600;">
        ✅ Format: <strong>SKU | PABRIK | BRAND | NAMA PRODUK | SEDIAAN | DESKRIPSI | HARGA | STOK | TERJUAL | GRADE | KOMPOSISI | INDIKASI | KELOMPOK | KATEGORI</strong>
    </p>
</div>


<div class="form-card" style="margin-bottom:1.5rem;">
    <div class="form-card-header">
        <div class="header-icon"><i class="fa-solid fa-file-excel"></i></div>
        <h3>Template File</h3>
    </div>
    <div class="form-body">
        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
            <a href="<?php echo e(route('admin.produk.import.template')); ?>" class="btn-save" style="background:#10b981;text-decoration:none;">
                <i class="fa-solid fa-download"></i> Download Template Excel
            </a>
        </div>
        <div class="template-table">
            <table>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>PABRIK</th>
                        <th>BRAND</th>
                        <th>NAMA PRODUK</th>
                        <th>SEDIAAN</th>
                        <th>DESKRIPSI</th>
                        <th>HARGA</th>
                        <th>STOK</th>
                        <th>TERJUAL</th>
                        <th>GRADE</th>
                        <th>KOMPOSISI</th>
                        <th>INDIKASI</th>
                        <th>KELOMPOK</th>
                        <th>KATEGORI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SKU-001</td>
                        <td>KIMIA FARMA</td>
                        <td>KIMIA FARMA</td>
                        <td>Paracetamol 500mg</td>
                        <td>fls</td>
                        <td>Pereda demam dan nyeri ringan</td>
                        <td>5000</td>
                        <td>100</td>
                        <td>20</td>
                        <td>A</td>
                        <td>Paracetamol 500 mg</td>
                        <td>Demam & nyeri</td>
                        <td>PBF</td>
                        <td><span style="background:#e3f2fd;color:#1565C0;padding:0.15rem 0.5rem;border-radius:4px;font-weight:700;">OBAT</span></td>
                    </tr>
                    <tr>
                        <td>SKU-002</td>
                        <td>WARDAH</td>
                        <td>WARDAH</td>
                        <td>Pelembab Wajah SPF30</td>
                        <td>box</td>
                        <td>Moisturizer ringan untuk kulit sensitif</td>
                        <td>85000</td>
                        <td>50</td>
                        <td>12</td>
                        <td>B</td>
                        <td>Aqua, Glycerin, SPF30</td>
                        <td>Melembabkan & melindungi kulit</td>
                        <td>APOTEK</td>
                        <td><span style="background:#fce4ec;color:#c2185b;padding:0.15rem 0.5rem;border-radius:4px;font-weight:700;">SKINCARE & KOSMETIK</span></td>
                    </tr>
                    <tr>
                        <td>SKU-003</td>
                        <td>OMRON</td>
                        <td>OMRON</td>
                        <td>Tensimeter Digital</td>
                        <td>-</td>
                        <td>Alat pemeriksa tekanan darah portabel</td>
                        <td>350000</td>
                        <td>20</td>
                        <td>5</td>
                        <td>A</td>
                        <td>-</td>
                        <td>Mengukur tekanan darah</td>
                        <td>PBF</td>
                        <td><span style="background:#e8f5e9;color:#2e7d32;padding:0.15rem 0.5rem;border-radius:4px;font-weight:700;">ALAT KESEHATAN</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top:0.75rem;padding:0.75rem;background:#fef3c7;border-radius:0.5rem;border:1px solid #fde68a;">
            <p style="font-size:0.78rem;color:#92400e;margin:0;line-height:1.6;">
                <i class="fa-solid fa-lightbulb" style="margin-right:0.3rem;"></i>
                <strong>Kolom KATEGORI</strong> harus salah satu dari:
                <strong>OBAT</strong> &nbsp;|&nbsp;
                <strong>SKINCARE & KOSMETIK</strong> &nbsp;|&nbsp;
                <strong>ALAT KESEHATAN</strong>.
                Jika tidak diisi atau tidak sesuai, otomatis masuk ke <strong>OBAT</strong>.
            </p>
        </div>
        <div style="margin-top:0.75rem;padding:0.75rem;background:#f0fdf4;border-radius:0.5rem;border:1px solid #bbf7d0;">
            <p style="font-size:0.78rem;color:#14532d;margin:0;line-height:1.6;">
                <i class="fa-solid fa-lightbulb" style="margin-right:0.3rem;"></i>
                <strong>Kolom KELOMPOK:</strong> Gunakan <strong>PBF</strong> atau <strong>APOTEK</strong> (opsional, boleh dikosongkan)
            </p>
        </div>
    </div>
</div>


<div class="form-card">
    <div class="form-card-header">
        <div class="header-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
        <h3>Upload File</h3>
    </div>
    <div class="form-body">
        <?php if($errors->any()): ?>
            <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:0.5rem;padding:1rem;margin-bottom:1rem;color:#7f1d1d;font-size:0.875rem;">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($errors->first('file')); ?>

            </div>
        <?php endif; ?>
        <div id="dropZone" class="upload-zone" onclick="document.getElementById('fileInput').click()"
             ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
            <div class="upload-icon">📂</div>
            <p>Klik atau drag & drop file di sini</p>
            <button type="button" class="btn-choose" onclick="event.stopPropagation();document.getElementById('fileInput').click()">
                <i class="fa-solid fa-folder-open"></i> Pilih File
            </button>
            <small>Format: CSV, XLS, XLSX — Maks. 10MB</small>
            <p style="color:#9ca3af;font-size:0.875rem;margin:0.75rem 0 0;" id="fileLabel"></p>
        </div>
        <input type="file" id="fileInput" name="file" accept=".csv,.xls,.xlsx,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="display:none;" onchange="updateFileLabel(this)">
    </div>
    <div class="form-footer" style="justify-content:flex-end; flex-wrap:wrap;">
        <form action="<?php echo e(route('admin.produk.import.process')); ?>" method="POST" enctype="multipart/form-data" id="submitForm" style="display:none;">
            <?php echo csrf_field(); ?>
            <input type="file" id="fileInputHidden" name="file" style="display:none;">
        </form>
        <button type="button" class="btn-save" id="submitBtn" onclick="submitForm()" disabled style="opacity:0.5;cursor:not-allowed;">
            <i class="fa-solid fa-cloud-arrow-up"></i> Import Sekarang
        </button>
        <a href="<?php echo e(route('admin.produk.index')); ?>" class="btn-cancel">
            <i class="fa-solid fa-xmark"></i> Batal
        </a>
    </div>
</div>

<script>
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    const btn   = document.getElementById('submitBtn');
    const zone  = document.getElementById('dropZone');
    if (input.files && input.files[0]) {
        label.textContent = '✅ ' + input.files[0].name;
        label.style.color = '#059669';
        zone.style.borderColor = '#059669';
        zone.style.background  = '#f0fdf4';
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor  = 'pointer';
    }
}
function handleDragOver(e) { e.preventDefault(); document.getElementById('dropZone').classList.add('drag-over'); }
function handleDragLeave(e) { document.getElementById('dropZone').classList.remove('drag-over'); }
function handleDrop(e) {
    e.preventDefault();
    const input = document.getElementById('fileInput');
    if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; updateFileLabel(input); }
    document.getElementById('dropZone').classList.remove('drag-over');
}
function submitForm() {
    const fileInput = document.getElementById('fileInput');
    if (fileInput.files.length > 0) {
        const dt = new DataTransfer();
        dt.items.add(fileInput.files[0]);
        document.getElementById('fileInputHidden').files = dt.files;
        document.getElementById('submitForm').submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ali Attaziri\medikpedia_a-main\resources\views/admin/produk/import.blade.php ENDPATH**/ ?>