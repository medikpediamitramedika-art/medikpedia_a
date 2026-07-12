{{--
  =====================================================================
  SHARED CART PARTIAL - resources/views/partials/cart.blade.php
  Include ini di @section('scripts') semua halaman yang butuh keranjang:
      @include('partials.cart')
  =====================================================================
--}}

{{-- ===== CART CSS ===== --}}
<style>
.cart-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 2000; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
.cart-overlay.open { opacity: 1; pointer-events: all; }
.cart-drawer { position: fixed; top: 0; right: -420px; width: 420px; max-width: 100vw; height: 100vh; background: #fff; z-index: 2001; display: flex; flex-direction: column; box-shadow: -8px 0 40px rgba(0,0,0,0.15); transition: right 0.35s cubic-bezier(.4,0,.2,1); }
.cart-drawer.open { right: 0; }
.cart-head { background: linear-gradient(135deg, #0D47A1, #1E88E5); padding: 1.25rem 1.5rem; color: #fff; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
.cart-head h2 { font-size: 1.1rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
.cart-close-btn { background: rgba(255,255,255,0.2); border: none; color: #fff; width: 34px; height: 34px; border-radius: 50%; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
.cart-close-btn:hover { background: rgba(255,255,255,0.35); }
.cart-body { flex: 1; overflow-y: auto; padding: 1rem 1.25rem; }
.cart-empty-msg { text-align: center; padding: 3rem 1rem; color: #9ca3af; }
.cart-empty-msg i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }
.cart-item-row { display: flex; gap: 0.75rem; align-items: flex-start; padding: 0.85rem 0; border-bottom: 1px solid #f3f4f6; }
.cart-item-thumb { width: 52px; height: 52px; border-radius: 10px; flex-shrink: 0; background: linear-gradient(135deg,#e3f2fd,#bbdefb); display: flex; align-items: center; justify-content: center; overflow: hidden; }
.cart-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.cart-item-info { flex: 1; min-width: 0; }
.cart-item-name { font-size: 0.84rem; font-weight: 700; color: #1f2937; margin-bottom: 0.2rem; line-height: 1.3; }
.cart-item-price { font-size: 0.8rem; color: #1E88E5; font-weight: 700; }
.cart-qty-row { display: flex; align-items: center; gap: 0.4rem; margin-top: 0.4rem; }
.qty-btn { width: 26px; height: 26px; border-radius: 6px; border: 1.5px solid #e5e7eb; background: #fff; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #374151; transition: all 0.2s; }
.qty-btn:hover { border-color: #1E88E5; color: #1E88E5; }
.qty-num { font-size: 0.85rem; font-weight: 700; min-width: 20px; text-align: center; }
.cart-item-del { background: none; border: none; color: #d1d5db; cursor: pointer; font-size: 0.9rem; padding: 0.2rem; flex-shrink: 0; transition: color 0.2s; }
.cart-item-del:hover { color: #ef4444; }
.cart-foot { padding: 1.25rem 1.5rem; border-top: 2px solid #f3f4f6; flex-shrink: 0; background: #fafbff; }
.cart-total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.cart-total-row span { font-size: 0.9rem; color: #6b7280; }
.cart-total-row strong { font-size: 1.2rem; color: #1E88E5; font-weight: 800; }
.btn-order-wa { display: flex; align-items: center; justify-content: center; gap: 0.6rem; width: 100%; padding: 0.85rem; background: #25D366; color: #fff; border: none; border-radius: 12px; cursor: pointer; font-weight: 700; font-size: 1rem; transition: all 0.3s; }
.btn-order-wa:hover { background: #1ebe5d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,211,102,0.4); }
.btn-clear-cart { display: block; width: 100%; padding: 0.5rem; background: none; border: none; color: #9ca3af; font-size: 0.8rem; cursor: pointer; margin-top: 0.5rem; transition: color 0.2s; }
.btn-clear-cart:hover { color: #ef4444; }
/* ORDER MODAL */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 3000; }
.modal-box { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 92%; max-width: 480px; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 20px; z-index: 3001; box-shadow: 0 25px 60px rgba(0,0,0,0.25); }
.modal-head { background: linear-gradient(135deg,#1565C0,#1E88E5); padding: 1.25rem 1.5rem; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center; }
.modal-head h3 { color: #fff; margin: 0; font-size: 1rem; font-weight: 700; }
.modal-head p { color: rgba(255,255,255,0.8); margin: 0; font-size: 0.75rem; }
.modal-close { background: rgba(255,255,255,0.2); border: none; color: #fff; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 1rem; }
.modal-summary { padding: 1rem 1.5rem; background: #f8faff; border-bottom: 1px solid #e5e7eb; font-size: 0.85rem; color: #374151; }
.modal-form { padding: 1.25rem 1.5rem 1.5rem; }
.form-lbl { display: block; font-size: 0.78rem; font-weight: 700; color: #374151; margin-bottom: 0.3rem; }
.form-inp { width: 100%; padding: 0.6rem 0.85rem; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.2s; margin-bottom: 0.75rem; }
.form-inp:focus { border-color: #1E88E5; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.form-error { display: none; background: #fee2e2; color: #7f1d1d; padding: 0.6rem; border-radius: 8px; font-size: 0.8rem; margin-bottom: 0.75rem; }
.btn-submit-wa { width: 100%; padding: 0.85rem; background: linear-gradient(135deg,#25D366,#1ebe5d); color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.btn-cart { display: block; width: 100%; padding: 0.55rem; background: white; color: #1E88E5; border: 2px solid #1E88E5; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 0.82rem; text-align: center; text-decoration: none; transition: all 0.3s; margin-top: 0.5rem; }
.btn-cart:hover { background: #e3f2fd; transform: translateY(-1px); }
.btn-cart.added { background: #d1fae5; color: #065f46; border-color: #34d399; }
@media (max-width: 768px) {
  .cart-drawer { width: 100%; max-width: 100%; right: -100%; }
  .cart-drawer.open { right: 0; }
  .form-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
  .cart-head { padding: 0.95rem 1rem; }
  .cart-head h2 { font-size: 0.98rem; }
  .cart-close-btn { width: 30px; height: 30px; font-size: 0.9rem; }
  .cart-body { padding: 0.75rem 0.9rem; }
  .cart-item-row { gap: 0.6rem; padding: 0.7rem 0; }
  .cart-item-thumb { width: 44px; height: 44px; }
  .cart-item-name { font-size: 0.78rem; }
  .qty-btn { width: 24px; height: 24px; font-size: 0.82rem; }
  .cart-foot { padding: 0.9rem 1rem; }
  .cart-total-row strong { font-size: 1rem; }
  .btn-order-wa { padding: 0.8rem; font-size: 0.92rem; }
}
</style>

{{-- ===== CART DRAWER HTML ===== --}}
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-head">
    <h2><i class="fa-solid fa-cart-shopping"></i> Keranjang</h2>
    <button class="cart-close-btn" onclick="closeCart()"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <div class="cart-body" id="cartBody">
    <div class="cart-empty-msg"><i class="fa-solid fa-cart-shopping"></i><p>Keranjang masih kosong</p></div>
  </div>
  <div class="cart-foot" id="cartFoot" style="display:none;">
    <div class="cart-total-row"><span>Total Pesanan</span><strong id="cartTotalAmt">Rp 0</strong></div>
    <button class="btn-order-wa" onclick="openOrder()">Lanjutkan Pesanan</button>
    <button class="btn-clear-cart" onclick="clearCart()">Kosongkan keranjang</button>
  </div>
</div>

{{-- ===== ORDER MODAL ===== --}}
<div class="modal-overlay" id="orderOverlay" onclick="closeOrder()"></div>
<div class="modal-box" id="orderModal">
  <div class="modal-head">
    <div><h3><i class="fa-solid fa-file-invoice"></i> Form Pemesanan</h3><p>Lengkapi data untuk melanjutkan</p></div>
    <button class="modal-close" onclick="closeOrder()">&#x2715;</button>
  </div>
  <div class="modal-summary">
    <p style="font-size:0.72rem;font-weight:700;color:#6b7280;margin:0 0 0.5rem;text-transform:uppercase;">Ringkasan Pesanan</p>
    <div id="orderSummary"></div>
    <div style="display:flex;justify-content:space-between;margin-top:0.5rem;padding-top:0.5rem;border-top:1px solid #e5e7eb;">
      <span style="font-weight:700;color:#374151;font-size:0.85rem;">Total</span>
      <span id="orderTotal" style="font-weight:800;color:#1E88E5;font-size:1rem;"></span>
    </div>
  </div>
  <div class="modal-form" id="orderFormPanel">
    <label class="form-lbl">Jenis Pembeli <span style="color:#ef4444;">*</span></label>
    <select id="f_jenis" class="form-inp" onchange="toggleBuyerType()">
      <option value="umum">Umum</option>
      <option value="apotik">Apotik</option>
    </select>
    <div id="buyerFieldsUmum">
      <label class="form-lbl">Nama Pemesan <span style="color:#ef4444;">*</span></label>
      <input id="f_nama" type="text" class="form-inp" placeholder="Nama lengkap">
      <label class="form-lbl">No. HP / WA <span style="color:#ef4444;">*</span></label>
      <input id="f_hp" type="tel" class="form-inp" placeholder="08xxxxxxxxxx">
      <label class="form-lbl">Alamat Lengkap <span style="color:#ef4444;">*</span></label>
      <textarea id="f_alamat" rows="3" class="form-inp" style="resize:vertical;" placeholder="Jl. nama jalan, no. rumah, RT/RW..."></textarea>
      <div class="form-row">
        <div><label class="form-lbl">Kecamatan</label><input id="f_kec" type="text" class="form-inp" placeholder="Kecamatan"></div>
        <div><label class="form-lbl">Kota / Kab</label><input id="f_kota" type="text" class="form-inp" placeholder="Kota / Kabupaten"></div>
      </div>
    </div>
    <div id="buyerFieldsApotik" style="display:none;">
      <label class="form-lbl">Nama Apotik <span style="color:#ef4444;">*</span></label>
      <input id="f_nama_apotik" type="text" class="form-inp" placeholder="Nama apotik">
      <label class="form-lbl">Nama Pemilik / Penanggung Jawab <span style="color:#ef4444;">*</span></label>
      <input id="f_penanggung_jawab" type="text" class="form-inp" placeholder="Nama pemilik / penanggung jawab">
      <label class="form-lbl">No. HP / WA <span style="color:#ef4444;">*</span></label>
      <input id="f_hp_apotik" type="tel" class="form-inp" placeholder="08xxxxxxxxxx">
      <label class="form-lbl">Alamat Apotik <span style="color:#ef4444;">*</span></label>
      <textarea id="f_alamat_apotik" rows="3" class="form-inp" style="resize:vertical;" placeholder="Alamat lengkap apotik"></textarea>
      <div class="form-row">
        <div><label class="form-lbl">Kecamatan</label><input id="f_kec_apotik" type="text" class="form-inp" placeholder="Kecamatan"></div>
        <div><label class="form-lbl">Kota / Kab</label><input id="f_kota_apotik" type="text" class="form-inp" placeholder="Kota / Kabupaten"></div>
      </div>
      <label class="form-lbl">Nomor SIA <span style="color:#ef4444;">*</span></label>
      <input id="f_sia" type="text" class="form-inp" placeholder="Nomor SIA">
      <label class="form-lbl">Nomor SIPA <span style="color:#ef4444;">*</span></label>
      <input id="f_sipa" type="text" class="form-inp" placeholder="Nomor SIPA">
    </div>
    <label class="form-lbl">Metode Pembayaran <span style="color:#ef4444;">*</span></label>
    <select id="f_payment" class="form-inp">
      <option value="">Pilih metode pembayaran</option>
      <option value="Transfer Bank BCA">Transfer Bank BCA</option>
      <option value="Transfer Bank BRI">Transfer Bank BRI</option>
      <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
      <option value="Transfer Bank BNI">Transfer Bank BNI</option>
      <option value="QRIS">QRIS</option>
      <option value="COD (Bayar di Tempat)">COD (Bayar di Tempat)</option>
      <option value="Tunai">Tunai</option>
    </select>
    <div class="form-error" id="formErr"></div>
    <button class="btn-submit-wa" onclick="submitOrder()">
      <i class="fa-solid fa-paper-plane"></i> Lanjutkan
    </button>
  </div>
  <div class="modal-form" id="nextActionPanel" style="display:none;">
    <p style="color:#374151;font-size:0.9rem;margin-bottom:1rem;">Pesanan berhasil dicatat! Pilih tindakan selanjutnya:</p>
    <button onclick="downloadReceiptAndClose()" style="width:100%;padding:0.75rem;background:#e3f2fd;color:#1565C0;border:1.5px solid #90caf9;border-radius:10px;font-weight:700;font-size:0.9rem;cursor:pointer;margin-bottom:0.75rem;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
      <i class="fa-solid fa-file-pdf"></i> Download Struk PDF
    </button>
    <button onclick="openWhatsAppOrder()" style="width:100%;padding:0.75rem;background:#25D366;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:0.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
      <i class="fa-brands fa-whatsapp" style="font-size:1.2rem;"></i> Chat WhatsApp
    </button>
  </div>
</div>

{{-- ===== CART JAVASCRIPT ===== --}}
<script>
// Satu keranjang untuk semua halaman - key: medikpedia_cart
const WA = '6285890007359';
let cart = JSON.parse(localStorage.getItem('medikpedia_cart') || '[]');

function save() {
  localStorage.setItem('medikpedia_cart', JSON.stringify(cart));
  syncBadge();
}

function syncBadge() {
  const tot = cart.reduce((s, i) => s + i.qty, 0);
  // Update semua elemen badge (navbar, home, dll)
  document.querySelectorAll('.cart-badge, #cartBadgeNav, #cartBadgeHome, #cartBadgeHeader').forEach(b => {
    if (!b) return;
    b.textContent = tot;
    b.style.display = tot > 0 ? 'flex' : 'none';
  });
  const navBtn = document.getElementById('cartNavBtn');
  if (navBtn) navBtn.style.display = tot > 0 ? 'flex' : 'none';
  const cartBtnHome = document.querySelector('.cart-btn-home');
  if (cartBtnHome) {
    const badge = cartBtnHome.querySelector('.cart-badge-home');
    if (badge) { badge.textContent = tot; badge.style.display = tot > 0 ? 'inline-flex' : 'none'; }
  }
}

function rp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

function addToCart(id, name, price, img, btn) {
  const ex = cart.find(i => i.id === id);
  if (ex) ex.qty++; else cart.push({ id, name, price, img, qty: 1, note: '' });
  save(); render();
  if (btn) {
    btn.classList.add('added');
    btn.innerHTML = '<i class="fa-solid fa-check"></i> Ditambahkan!';
    setTimeout(() => {
      btn.classList.remove('added');
      btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang';
    }, 1500);
  }
  openCart();
}

function removeItem(id) { cart = cart.filter(i => i.id !== id); save(); render(); }
// Alias for compatibility
function removeFromCart(id) { removeItem(id); }

function changeQty(id, d) {
  const it = cart.find(i => i.id === id);
  if (!it) return;
  it.qty += d;
  if (it.qty <= 0) removeItem(id); else { save(); render(); }
}

function setQty(id, value) {
  const it = cart.find(i => i.id === id);
  if (!it) return;
  it.qty = Math.max(1, parseInt(value, 10) || 1);
  save(); render();
}

function setNote(id, value) {
  const it = cart.find(i => i.id === id);
  if (!it) return;
  it.note = String(value || '').trim();
  save();
}

function setDiscount(id, value) {
  const it = cart.find(i => i.id === id);
  if (!it) return;
  const raw = String(value).replace(/[^0-9]/g, '');
  it.discount = Math.min(parseInt(raw, 10) || 0, it.price * it.qty);
  save();
  // update live preview
  const previewEl = document.getElementById('disc-preview-' + id);
  if (previewEl) {
    const subtotal   = it.price * it.qty;
    const afterDisc  = subtotal - it.discount;
    if (it.discount > 0) {
      previewEl.innerHTML = `<span style="text-decoration:line-through;color:#9ca3af;">${rp(subtotal)}</span>
        <span style="color:#ef4444;font-weight:700;font-size:.78rem;">-${rp(it.discount)}</span>
        <span style="color:#10b981;font-weight:800;">${rp(afterDisc)}</span>`;
    } else {
      previewEl.innerHTML = '';
    }
    // update total di footer
    const totalAfter = cart.reduce((s, i) => s + (i.price * i.qty) - (i.discount || 0), 0);
    const tot = document.getElementById('cartTotalAmt');
    if (tot) tot.textContent = rp(totalAfter);
  }
}

function clearCart() { cart = []; save(); render(); }

function render() {
  const body = document.getElementById('cartBody');
  const foot = document.getElementById('cartFoot');
  const tot  = document.getElementById('cartTotalAmt');
  if (!body || !foot || !tot) return;

  if (!cart.length) {
    foot.style.display = 'none';
    body.innerHTML = '<div class="cart-empty-msg"><i class="fa-solid fa-cart-shopping"></i><p>Keranjang masih kosong</p></div>';
    return;
  }
  foot.style.display = '';
  let html = '', total = 0;
  cart.forEach(it => {
    total += (it.price * it.qty) - (it.discount || 0);
    const imgHtml = it.img
      ? `<img src="${it.img}" alt="">`
      : '<i class="fa-solid fa-pills" style="color:#90caf9;font-size:1.4rem;"></i>';
    html += `<div class="cart-item-row">
      <div class="cart-item-thumb">${imgHtml}</div>
      <div class="cart-item-info">
        <div class="cart-item-name">${it.name}</div>
        <div class="cart-item-price">${rp(it.price)}</div>
        <div class="cart-qty-row">
          <button class="qty-btn" onclick="changeQty(${it.id},-1)">−</button>
          <input type="number" class="qty-num" min="1" value="${it.qty}" onchange="setQty(${it.id}, this.value)"
            style="width:4rem;text-align:center;border:1px solid #d1d5db;border-radius:8px;padding:0.4rem;" />
          <button class="qty-btn" onclick="changeQty(${it.id},1)">+</button>
        </div>
        <textarea class="cart-note" placeholder="Catatan produk..." onchange="setNote(${it.id}, this.value)"
          style="width:100%;margin-top:0.75rem;padding:0.65rem 0.85rem;border:1px solid #e5e7eb;border-radius:12px;font-size:0.9rem;min-height:72px;resize:vertical;">${it.note || ''}</textarea>
        <div style="margin-top:.6rem;">
          <label style="font-size:.78rem;font-weight:700;color:#6b7280;display:block;margin-bottom:.3rem;">
            <i class="fa-solid fa-tag" style="color:#ef4444;margin-right:.25rem;"></i>Ajuan Potongan Harga
          </label>
          <div style="display:flex;align-items:center;gap:.5rem;">
            <span style="font-size:.82rem;color:#374151;flex-shrink:0;">Rp</span>
            <input type="text" inputmode="numeric"
              placeholder="0"
              value="${it.discount > 0 ? it.discount : ''}"
              oninput="setDiscount(${it.id}, this.value)"
              style="flex:1;padding:.45rem .7rem;border:1.5px solid #fca5a5;border-radius:10px;font-size:.88rem;outline:none;color:#374151;background:#fff9f9;"
            />
          </div>
          <div id="disc-preview-${it.id}" style="margin-top:.4rem;display:flex;gap:.4rem;align-items:center;flex-wrap:wrap;min-height:1.2rem;font-size:.84rem;">
            ${it.discount > 0 ? `<span style="text-decoration:line-through;color:#9ca3af;">${rp(it.price * it.qty)}</span><span style="color:#ef4444;font-weight:700;font-size:.78rem;">-${rp(it.discount)}</span><span style="color:#10b981;font-weight:800;">${rp(it.price * it.qty - it.discount)}</span>` : ''}
          </div>
        </div>
      </div>
      <button class="cart-item-del" onclick="removeItem(${it.id})"><i class="fa-solid fa-trash"></i></button>
    </div>`;
  });
  body.innerHTML = html;
  tot.textContent = rp(total);
}

function openCart() {
  document.getElementById('cartDrawer').classList.add('open');
  document.getElementById('cartOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
  render();
}

function closeCart() {
  document.getElementById('cartDrawer').classList.remove('open');
  document.getElementById('cartOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

function escapeHtml(text) {
  return String(text || '')
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function openOrder() {
  if (!cart.length) return;
  let html = '', total = 0;
  cart.forEach((it, i) => {
    const sub = it.price * it.qty;
    const disc = it.discount || 0;
    const afterDisc = sub - disc;
    total += afterDisc;
    html += `<div style="padding:0.35rem 0;border-bottom:1px solid #f3f4f6;font-size:0.83rem;">
      <div style="display:flex;justify-content:space-between;gap:0.75rem;">
        <span>${i+1}. ${it.name} <span style="color:#9ca3af;">&times;${it.qty}</span></span>
        <span style="font-weight:700;color:#1E88E5;">${disc > 0 ? `<span style="text-decoration:line-through;color:#9ca3af;font-size:.78rem;">${rp(sub)}</span> ${rp(afterDisc)}` : rp(sub)}</span>
      </div>`;
    if (disc > 0) html += `<div style="color:#ef4444;font-size:.78rem;">Potongan: -${rp(disc)}</div>`;
    if (it.note)  html += `<div style="margin-top:0.35rem;color:#6b7280;font-size:0.8rem;">Catatan: ${escapeHtml(it.note)}</div>`;
    html += `</div>`;
  });
  document.getElementById('orderSummary').innerHTML = html;
  document.getElementById('orderTotal').textContent = rp(total);
  document.getElementById('orderFormPanel').style.display = '';
  document.getElementById('nextActionPanel').style.display = 'none';
  document.getElementById('formErr').style.display = 'none';
  document.getElementById('orderOverlay').style.display = 'block';
  document.getElementById('orderModal').style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function closeOrder() {
  document.getElementById('orderOverlay').style.display = 'none';
  document.getElementById('orderModal').style.display = 'none';
  document.body.style.overflow = '';
}

function toggleBuyerType() {
  const type = document.getElementById('f_jenis').value;
  document.getElementById('buyerFieldsUmum').style.display   = type === 'umum'   ? '' : 'none';
  document.getElementById('buyerFieldsApotik').style.display = type === 'apotik' ? '' : 'none';
}

function escapePdfText(text) {
  return String(text || '').replace(/\\/g, '\\\\').replace(/\(/g, '\\(').replace(/\)/g, '\\)');
}

function buildReceiptPdf(orderData) {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ unit: 'pt', format: 'a4', orientation: 'portrait' });
  const pageWidth = doc.internal.pageSize.getWidth();
  // use full printable width with comfortable margins
  const leftMargin = 40;
  const receiptWidth = pageWidth - leftMargin * 2;
  const labelWidth = 120; // width reserved for labels like 'Pembeli'
  const valueX = leftMargin + labelWidth;
  // Columns for product rows
  const rightX = leftMargin + receiptWidth;
  // reserve columns: qty column, unit price column, and subtotal at far right
  const qtyColWidth = 60; // small width for qty
  const unitPriceColWidth = 100; // width for unit price
  const subtotalColWidth = 80; // width for subtotal
  const subtotalX = rightX; // position for subtotal (right aligned)
  const unitPriceX = rightX - subtotalColWidth - 8; // right edge for unit price column
  const qtyX = unitPriceX - unitPriceColWidth; // right edge for qty column
  doc.setFont('helvetica', 'normal');
  doc.setFontSize(12);
  let y = 80;
  const line = (text, options = {}) => {
    const align = options.align || 'left';
    const x = align === 'center' ? leftMargin + receiptWidth / 2 : align === 'right' ? rightX : leftMargin;
    doc.text(text, x, y, { align });
    y += (options.spacing || 18);
  };
  const drawLine = () => { doc.setDrawColor(180,180,180); doc.line(leftMargin, y, rightX, y); y += 8; };
  doc.setFont('helvetica', 'bold'); doc.setFontSize(20);
  line('MEDIKPEDIA', { align: 'center', spacing: 20 });
  doc.setFont('helvetica', 'normal'); doc.setFontSize(12);
  line('Jl. Letjen Suprapto No.1, Sumur Batu', { align: 'center', spacing: 12 });
  line('Kec. Kemayoran, Jakarta Pusat, DKI Jakarta 10640', { align: 'center', spacing: 12 });
  doc.setFontSize(13);
  line('Apotik Online Terpercaya', { align: 'center', spacing: 12 });
  line('www.medikpedia.com', { align: 'center', spacing: 16 });
  drawLine();
  // Order meta (two-column layout)
  const writeMeta = (label, value) => {
    doc.setFontSize(10);
    doc.text(label + ' :', leftMargin, y);
    doc.text(String(value || '-'), valueX, y);
    y += 16;
  };
  writeMeta('ID Pesanan', `#${orderData.id || '-'}`);
  writeMeta('Tanggal', new Date().toLocaleString('id-ID'));
  if (orderData.buyer_name) writeMeta('Pembeli', orderData.buyer_name);
  if (orderData.phone) writeMeta('No. HP', orderData.phone);
  drawLine();
  doc.setFont('helvetica', 'bold'); doc.setFontSize(11);
  line('DAFTAR PRODUK', { align: 'center', spacing: 21 });
  doc.setFont('helvetica', 'normal');
  let total = 0;
  cart.forEach(it => {
    const subtotal = it.price * it.qty; total += subtotal;
    // wrap name so qty and price columns stay fixed
    const nameWrapWidth = qtyX - leftMargin - 12;
    const nameLines = doc.splitTextToSize(it.name, nameWrapWidth);
    nameLines.forEach((ln, idx) => {
      const textX = leftMargin + (idx === 0 ? 0 : 8); // indent wrapped lines
      doc.text(ln, textX, y);
      if (idx === 0) {
        // qty, unit price, and subtotal in separate right-aligned columns
        doc.text(String(it.qty), qtyX, y, { align: 'right' });
        doc.text(`x ${rp(it.price)}`, unitPriceX, y, { align: 'right' });
        doc.text(rp(subtotal), subtotalX, y, { align: 'right' });
      }
      y += 18;
    });
    y += 6;
  });
  // add small gap so products don't touch the separator line
  y += 8;
  drawLine();
  // add extra spacing before TOTAL to avoid crowding
  y += 12;
  doc.setFont('helvetica', 'bold'); doc.setFontSize(18);
  doc.text('TOTAL', leftMargin, y);
  doc.text(rp(total), rightX, y, { align: 'right' }); y += 36;
  drawLine();
  if (orderData.payment_method) {
    doc.setFont('helvetica', 'normal'); doc.setFontSize(8);
    line(`Pembayaran  : ${orderData.payment_method}`, { spacing: 13 });
    drawLine();
  }
  doc.setFont('helvetica', 'normal'); doc.setFontSize(8);
  line('Terima kasih telah berbelanja!', { align: 'center', spacing: 11 });
  line('Hubungi kami via WhatsApp: 085890007359', { align: 'center', spacing: 11 });
  return doc.output('bloburl');
}

async function submitOrder() {
  const jenis = document.getElementById('f_jenis').value;
  const err = document.getElementById('formErr');
  err.style.display = 'none';

  const originalTotal = cart.reduce((s, it) => s + (it.price * it.qty), 0);
  const discountedTotal = cart.reduce((s, it) => s + (it.price * it.qty) - (it.discount || 0), 0);
  const payload = {
    buyer_type: jenis,
    items: cart.map(it => ({ id: it.id, nama_obat: it.name, quantity: it.qty, harga: it.price, catatan: it.note || '', potongan: it.discount || 0 })),
    total: discountedTotal,
    original_total: originalTotal,
    discounted_total: discountedTotal,
    approval_status: 'pending',
    payment_method: document.getElementById('f_payment').value.trim(),
  };

  if (jenis === 'apotik') {
    payload.buyer_name = document.getElementById('f_penanggung_jawab').value.trim() || document.getElementById('f_nama_apotik').value.trim();
    payload.phone    = document.getElementById('f_hp_apotik').value.trim();
    payload.address  = document.getElementById('f_alamat_apotik').value.trim();
    payload.kecamatan = document.getElementById('f_kec_apotik').value.trim();
    payload.kota     = document.getElementById('f_kota_apotik').value.trim();
    payload.sia      = document.getElementById('f_sia').value.trim();
    payload.sipa     = document.getElementById('f_sipa').value.trim();
    if (!payload.buyer_name || !document.getElementById('f_nama_apotik').value.trim() || !payload.phone || !payload.address || !payload.sia || !payload.sipa) {
      err.textContent = 'Semua field apotik wajib diisi.'; err.style.display = 'block'; return;
    }
  } else {
    payload.buyer_name = document.getElementById('f_nama').value.trim();
    payload.phone    = document.getElementById('f_hp').value.trim();
    payload.address  = document.getElementById('f_alamat').value.trim();
    payload.kecamatan = document.getElementById('f_kec').value.trim();
    payload.kota     = document.getElementById('f_kota').value.trim();
    if (!payload.buyer_name || !payload.phone || !payload.address) {
      err.textContent = 'Nama, No. HP, dan Alamat wajib diisi.'; err.style.display = 'block'; return;
    }
  }

  if (!payload.payment_method) {
    err.textContent = 'Metode pembayaran wajib diisi.'; err.style.display = 'block'; return;
  }

  try {
    const response = await fetch('{{ route("orders.history.store") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (!response.ok) { const e = await response.json(); throw (e.errors || new Error('Gagal menyimpan pesanan.')); }
    const result = await response.json();
    window.orderPayload = { ...payload, id: result.id };
    document.getElementById('orderFormPanel').style.display = 'none';
    document.getElementById('nextActionPanel').style.display = 'block';
  } catch (error) {
    let msg = 'Terjadi kesalahan yang tidak diketahui.';
    if (error && error.message) msg = error.message;
    if (typeof error === 'object' && !error.message) msg = Object.values(error).map(e => e.join(', ')).join('<br>');
    err.innerHTML = msg; err.style.display = 'block';
  }
}

function downloadReceiptAndClose() {
  const pdfUrl = buildReceiptPdf(window.orderPayload || {});
  const a = document.createElement('a');
  a.href = pdfUrl;
  a.download = `struk-medikpedia-${window.orderPayload?.id || Date.now()}.pdf`;
  document.body.appendChild(a); a.click(); document.body.removeChild(a);
  setTimeout(() => URL.revokeObjectURL(pdfUrl), 100);
}

function openWhatsAppOrder() {
  let total = 0;
  let msg = '*Halo Medikpedia, saya ingin memesan:*\n';
  cart.forEach((it, i) => {
    const sub = it.price * it.qty;
    const disc = it.discount || 0;
    const afterDisc = sub - disc;
    total += afterDisc;
    msg += `${i+1}. *${it.name}*\n   Qty: ${it.qty} x ${rp(it.price)} = ${rp(sub)}\n`;
    if (disc > 0) msg += `   Potongan: -${rp(disc)} → *${rp(afterDisc)}*\n`;
    if (it.note) msg += `   Catatan: ${it.note}\n`;
  });
  msg += `---\n*Total: ${rp(total)}*\n\n*Data ${window.orderPayload?.buyer_type === 'apotik' ? 'Apotik' : 'Pemesan'}:*\n`;
  if (window.orderPayload?.buyer_type === 'apotik') {
    msg += `- Apotik: ${document.getElementById('f_nama_apotik').value.trim()}\n- Penanggung Jawab: ${window.orderPayload.buyer_name}\n- HP/WA: ${window.orderPayload.phone}\n- Alamat: ${window.orderPayload.address}\n- SIA: ${window.orderPayload.sia}\n- SIPA: ${window.orderPayload.sipa}\n`;
  } else {
    msg += `- Nama: ${window.orderPayload?.buyer_name}\n- HP/WA: ${window.orderPayload?.phone}\n- Alamat: ${window.orderPayload?.address}\n`;
  }
  if (window.orderPayload?.payment_method) msg += `*Metode Pembayaran: ${window.orderPayload.payment_method}*\n`;
  msg += '\nTerima kasih';
  window.open(`https://wa.me/${WA}?text=${encodeURIComponent(msg)}`, '_blank');
}

document.addEventListener('DOMContentLoaded', () => {
  syncBadge();
  toggleBuyerType();
  if (window.location.hash === '#keranjang') openCart();
  const navBtn = document.getElementById('cartNavBtn');
  if (navBtn) navBtn.onclick = () => openCart();
  // Load jsPDF
  const s = document.createElement('script');
  s.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
  document.head.appendChild(s);
});
</script>
