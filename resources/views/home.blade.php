@extends('layouts.frontend')
@section('title', 'Medikpedia - Apotik Online Terpercaya')
@section('styles')
<style>
/* ==============================================
   HOME PAGE - Clean GoApotik Style
   ============================================== */
</style>
<style>
/* SEARCH */
.search-section { padding: 1.25rem 0 0.75rem; background: #f8faff; }
.search-box {
    display: flex; gap: 0.5rem; background: #fff;
    border-radius: 14px; padding: 0.5rem;
    box-shadow: 0 2px 16px rgba(30,136,229,0.1);
    border: 1.5px solid #e5e7eb; max-width: 700px; margin: 0 auto;
}
.search-box input {
    flex: 1; border: none; outline: none;
    padding: 0.5rem 0.75rem; font-size: 0.95rem; color: #374151; background: transparent;
}
.search-box button {
    padding: 0.55rem 1.4rem;
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    color: #fff; border: none; border-radius: 10px; cursor: pointer;
    font-weight: 700; font-size: 0.88rem; white-space: nowrap;
    display: flex; align-items: center; gap: 0.4rem; transition: all 0.2s;
}
.search-box button:hover { background: linear-gradient(135deg, #1565C0, #0D47A1); }
</style>
<style>
/* QUICK CATEGORY */
.quick-section { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 0; display: none; }
.quick-row { display: flex; gap: 0.5rem; overflow-x: auto; padding-bottom: 4px; }
.quick-row::-webkit-scrollbar { height: 3px; }
.quick-row::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 2px; }
.quick-btn {
    display: flex; flex-direction: column; align-items: center; gap: 0.35rem;
    padding: 0.5rem 1rem; border-radius: 12px; text-decoration: none;
    color: #374151; white-space: nowrap; flex-shrink: 0;
    border: 1.5px solid #e5e7eb; background: #fff; min-width: 75px;
    transition: all 0.2s; font-size: 0;
}
.quick-btn:hover { background: #e3f2fd; border-color: #90caf9; color: #1565C0; }
.quick-btn.active { background: linear-gradient(135deg,#1E88E5,#1565C0); border-color: transparent; color: #fff; }
.quick-btn i { font-size: 1.25rem; }
.quick-btn span { font-size: 0.7rem; font-weight: 600; }

/* PROMO CARDS */
.promo-section { 
    padding: 1rem 0; 
    background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
    width: 100%;
    margin: 0;
}
.promo-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
    gap: 1.5rem;
    max-width: 100%;
    padding: 0 1rem;
    justify-items: center;
}
.promo-card {
    border-radius: 18px; 
    padding: 1.5rem 2rem; 
    color: #fff;
    text-decoration: none; 
    display: flex; 
    align-items: center; 
    gap: 1.2rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    position: relative; 
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(0, 0, 0, 0.15);
    min-height: 120px;
    width: 100%;
    max-width: 520px;
}
.promo-card::after {
    content: ''; 
    position: absolute; 
    right: -30px; 
    top: -30px;
    width: 120px; 
    height: 120px; 
    background: rgba(255,255,255,0.15); 
    border-radius: 50%;
}
.promo-card:hover { 
    transform: translateY(-8px) scale(1.03); 
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2); 
}
.promo-1 { background: linear-gradient(135deg, #1565C0 0%, #1E88E5 100%); position: relative; }
.promo-1::before { content: ''; position: absolute; inset: 0; background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 100%); border-radius: 18px; }
.promo-2 { background: linear-gradient(135deg, #2e7d32 0%, #43a047 100%); position: relative; }
.promo-2::before { content: ''; position: absolute; inset: 0; background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 100%); border-radius: 18px; }
.promo-3 { background: linear-gradient(135deg, #6a1b9a 0%, #8e24aa 100%); position: relative; }
.promo-3::before { content: ''; position: absolute; inset: 0; background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 100%); border-radius: 18px; }
.promo-goapotik { background: linear-gradient(135deg, #023e8a 0%, #0077b6 50%, #00b4d8 100%); position: relative; }
.promo-goapotik::before { content: ''; position: absolute; inset: 0; background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 100%); border-radius: 18px; }
.promo-goapotik-logo { height: 72px; object-fit: contain; flex-shrink: 0; }
.promo-card > i { font-size: 3.5rem; opacity: 0.95; flex-shrink: 0; }
.promo-card h4 { font-size: 1.35rem; font-weight: 800; margin: 0 0 0.4rem; color: #fff; }
.promo-card p  { font-size: 1rem; color: rgba(255,255,255,0.9); margin: 0; font-weight: 500; }

/* SECTION HEADER */
.sec-head { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem; }
.sec-head-left { display: flex; flex-direction: column; gap: 0.25rem; }
.sec-tag { display: inline-block; background: #e3f2fd; color: #1565C0; padding: 0.2rem 0.75rem; border-radius: 50px; font-size: 0.72rem; font-weight: 600; }
.sec-title { font-size: 1.2rem; font-weight: 800; color: #1f2937; margin: 0; }
.sec-link { font-size: 0.82rem; color: #1E88E5; text-decoration: none; font-weight: 600; white-space: nowrap; }
.sec-link:hover { text-decoration: underline; }
</style>
<style>
/* PRODUCT GRID */
.prod-section { padding: 1.5rem 0; }
.prod-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(185px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.prod-card {
    background: #fff; border-radius: 14px; overflow: hidden;
    border: 1.5px solid #e5e7eb; display: flex; flex-direction: column;
    transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
}
.prod-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(30,136,229,0.12); border-color: #90caf9; }
.prod-img {
    width: 100%; height: 148px;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; position: relative;
}
.prod-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
.prod-card:hover .prod-img img { transform: scale(1.06); }
.prod-img .no-img-icon { font-size: 2.5rem; color: #90caf9; }
.prod-badge-label {
    position: absolute; top: 8px; left: 8px;
    background: #1E88E5; color: #fff;
    font-size: 0.62rem; font-weight: 700; padding: 0.18rem 0.45rem; border-radius: 6px;
}
.prod-badge-grade-a {
    position: absolute; top: 8px; right: 8px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: 0.62rem; font-weight: 700; padding: 0.2rem 0.5rem;
    border-radius: 6px;
    display: inline-flex; align-items: center; gap: 0.2rem;
    box-shadow: 0 2px 6px rgba(217,119,6,.35);
}
.prod-body { padding: 0.85rem; flex: 1; display: flex; flex-direction: column; }
.prod-brand-tag {
    font-size: 0.66rem; font-weight: 700; color: #1565C0; background: #e3f2fd;
    display: inline-block; padding: 0.15rem 0.5rem; border-radius: 20px; margin-bottom: 0.4rem;
}
.prod-name {
    font-size: 0.86rem; font-weight: 700; color: #1f2937; margin-bottom: 0.4rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; line-height: 1.35; flex: 1;
}
  .prod-desc { color: #374151; font-size: 0.86rem; margin: 0 0 0.45rem; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
  .prod-meta { font-size: 0.72rem; color: #6b7280; margin-bottom: 0.35rem; }
.prod-price { font-size: 1rem; font-weight: 800; color: #1E88E5; margin-bottom: 0.35rem; }
.stock-ok  { font-size: 0.65rem; font-weight: 600; color: #065f46; background: #d1fae5; padding: 0.15rem 0.5rem; border-radius: 20px; display: inline-block; margin-bottom: 0.6rem; }
.stock-low { font-size: 0.65rem; font-weight: 600; color: #92400e; background: #fef3c7; padding: 0.15rem 0.5rem; border-radius: 20px; display: inline-block; margin-bottom: 0.6rem; }
.stock-out { font-size: 0.65rem; font-weight: 600; color: #7f1d1d; background: #fee2e2; padding: 0.15rem 0.5rem; border-radius: 20px; display: inline-block; margin-bottom: 0.6rem; }
.btn-detail {
    display: block; width: 100%; padding: 0.5rem;
    background: linear-gradient(135deg, #1E88E5, #1565C0); color: #fff;
    border: none; border-radius: 9px; cursor: pointer; font-weight: 700;
    font-size: 0.78rem; text-align: center; text-decoration: none; transition: all 0.25s;
}
.btn-detail:hover { background: linear-gradient(135deg, #1565C0, #0D47A1); transform: translateY(-1px); color: #fff; }
.btn-cart {
    display: block; width: 100%; padding: 0.42rem;
    background: #fff; color: #1E88E5;
    border: 1.5px solid #1E88E5; border-radius: 9px; cursor: pointer;
    font-weight: 700; font-size: 0.72rem; text-align: center;
    text-decoration: none; transition: all 0.2s; margin-top: 0.4rem;
}
.btn-cart:hover { background: #e3f2fd; }
.btn-cart.added { background: #d1fae5; color: #065f46; border-color: #34d399; }
</style>
<style>
/* WHY US */
.why-section { background: #fff; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 1.25rem 0; }
.why-grid { display: flex; justify-content: center; gap: 2.5rem; flex-wrap: wrap; }
.why-item { display: flex; align-items: center; gap: 0.75rem; }
.why-icon { width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.why-text h4 { font-size: 0.84rem; font-weight: 700; color: #1f2937; margin: 0 0 0.1rem; }
.why-text p  { font-size: 0.73rem; color: #6b7280; margin: 0; line-height: 1.4; }

/* CTA */
.cta-section { padding: 1.25rem 0; }
.cta-box {
    background: linear-gradient(135deg, #0D47A1, #1E88E5);
    border-radius: 20px; padding: 2rem 2.5rem;
    display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;
}
.cta-box h3 { font-size: 1.25rem; font-weight: 800; color: #fff; margin: 0 0 0.3rem; }
.cta-box p  { color: rgba(255,255,255,0.85); font-size: 0.9rem; margin: 0; }
.btn-wa {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: #25D366; color: #fff; padding: 0.75rem 1.75rem;
    border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.95rem;
    transition: all 0.25s; white-space: nowrap; flex-shrink: 0;
}
.btn-wa:hover { background: #1ebe5d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,211,102,0.4); color: #fff; }

/* CATEGORY GRID */
.cat-section { padding: 1.5rem 0 1rem; }
.cat-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem; }
.cat-card {
    display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
    padding: 1rem 0.5rem; background: #fff; border-radius: 14px;
    border: 1.5px solid #e5e7eb; text-decoration: none; color: #374151;
    transition: all 0.25s; text-align: center;
}
.cat-card:hover { background: #e3f2fd; border-color: #90caf9; color: #1565C0; transform: translateY(-3px); box-shadow: 0 6px 18px rgba(30,136,229,0.1); }
.cat-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; }
.cat-card > span { font-size: 0.7rem; font-weight: 600; line-height: 1.3; }

/* ABOUT STRIP */
.about-strip { padding: 1rem 0 2.5rem; }
.about-box {
    background: #fff; border-radius: 20px; padding: 1.75rem 2rem;
    border: 1.5px solid #e5e7eb;
    display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}
.about-logo { height: 72px; object-fit: contain; flex-shrink: 0; }
.about-info { flex: 1; min-width: 200px; }
.about-info h3 { font-size: 1.05rem; font-weight: 800; color: #1f2937; margin: 0 0 0.4rem; }
.about-info p  { font-size: 0.85rem; color: #6b7280; line-height: 1.7; margin: 0 0 0.85rem; }
.btn-about {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: #e3f2fd; color: #1565C0; padding: 0.45rem 1.1rem;
    border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.82rem; transition: all 0.2s;
}
.btn-about:hover { background: #1565C0; color: #fff; }
.about-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.65rem; flex-shrink: 0; }
.about-stat-item { text-align: center; padding: 0.65rem 1rem; background: #f8faff; border-radius: 12px; border: 1px solid #e5e7eb; }
.about-stat-item .n { font-size: 1.3rem; font-weight: 800; color: #1E88E5; display: block; line-height: 1.2; }
.about-stat-item .l { font-size: 0.68rem; color: #6b7280; }
.about-stat-item:nth-child(even) .n { color: #7CB342; }

/* CART DRAWER */
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
</style>
<style>
/* ORDER MODAL */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 3000; }
.modal-box { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 92%; max-width: 480px; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 20px; z-index: 3001; box-shadow: 0 25px 60px rgba(0,0,0,0.25); }
.modal-head { background: linear-gradient(135deg,#1565C0,#1E88E5); padding: 1.25rem 1.5rem; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center; }
.modal-head h3 { color: #fff; margin: 0; font-size: 1rem; font-weight: 700; }
.modal-head p { color: rgba(255,255,255,0.8); margin: 0; font-size: 0.75rem; }
.modal-close { background: rgba(255,255,255,0.2); border: none; color: #fff; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 1rem; }
.modal-summary { padding: 1rem 1.5rem; background: #f8faff; border-bottom: 1px solid #e5e7eb; font-size: 0.85rem; color: #374151; }
.modal-form { padding: 1.25rem 1.5rem; }
.form-lbl { display: block; font-size: 0.78rem; font-weight: 700; color: #374151; margin-bottom: 0.3rem; }
.form-inp { width: 100%; padding: 0.6rem 0.85rem; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem; outline: none; transition: border-color 0.2s; margin-bottom: 0.75rem; }
.form-inp:focus { border-color: #1E88E5; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.form-error { display: none; background: #fee2e2; color: #7f1d1d; padding: 0.6rem; border-radius: 8px; font-size: 0.8rem; margin-bottom: 0.75rem; }
.btn-submit-wa { width: 100%; padding: 0.85rem; background: linear-gradient(135deg,#25D366,#1ebe5d); color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }

/* RESPONSIVE */
@media (max-width: 992px) {
    .cat-grid { grid-template-columns: repeat(4,1fr); }
    .why-grid { gap: 1.5rem; }
    .hero-img-wrap { display: none; }
}
@media (max-width: 768px) {
    .hero { padding: 2rem 0 1.75rem; }
    .promo-grid { grid-template-columns: 1fr 1fr; }
    .cat-grid { grid-template-columns: repeat(3,1fr); }
    .prod-grid { grid-template-columns: repeat(2,1fr); }
    .cta-box { flex-direction: column; text-align: center; padding: 1.5rem; }
    .stats-strip-row { flex-wrap: wrap; }
    .stat-cell { min-width: 50%; border-bottom: 1px solid #e5e7eb; }
    .cart-drawer { width: 100%; max-width: 100%; right: -100%; }
    .cart-drawer.open { right: 0; }
    .about-box { flex-direction: column; align-items: flex-start; }
    .about-stats { width: 100%; grid-template-columns: repeat(4,1fr); }
}
@media (max-width: 480px) {
    .promo-grid { grid-template-columns: 1fr; }
    .cat-grid { grid-template-columns: repeat(3,1fr); }
    .prod-grid { grid-template-columns: repeat(2,1fr); }
    .why-grid { flex-direction: column; align-items: center; gap: 1rem; }
    .hero-btns { flex-direction: column; }
    .about-stats { grid-template-columns: repeat(2,1fr); }
    .form-row { grid-template-columns: 1fr; }
    .cart-head { padding: 0.95rem 1rem; }
    .cart-head h2 { font-size: 0.98rem; }
    .cart-close-btn { width: 30px; height: 30px; font-size: 0.9rem; }
    .cart-body { padding: 0.75rem 0.9rem; }
    .cart-item-row { gap: 0.6rem; padding: 0.7rem 0; }
    .cart-item-thumb { width: 44px; height: 44px; }
    .cart-item-name { font-size: 0.78rem; }
    .cart-item-price { font-size: 0.76rem; }
    .qty-btn { width: 24px; height: 24px; font-size: 0.82rem; }
    .qty-num { font-size: 0.8rem; }
    .cart-foot { padding: 0.9rem 1rem; }
    .cart-total-row { margin-bottom: 0.75rem; }
    .cart-total-row strong { font-size: 1rem; }
    .btn-order-wa { padding: 0.8rem; font-size: 0.92rem; border-radius: 10px; }
    .btn-clear-cart { margin-top: 0.35rem; font-size: 0.75rem; }
}
</style>
@endsection

@section('content')

{{-- ============================================================
     BANNER PROMO SLIDESHOW
     ============================================================ --}}
@if($banners->count())
<div class="banner-slider" id="bannerSlider" style="width: 100%; height: 100%; aspect-ratio: 3998/1224;">
    <div class="banner-track" id="bannerTrack" style="display: flex; width: 100%; height: 100%;">
        @foreach($banners as $i => $banner)
        <div class="banner-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" style="min-width: 100%; width: 100%; height: 100%;">
            <a href="{{ $banner->url_tujuan ?: '#' }}" class="banner-link" style="display: block; width: 100%; height: 100%;" {{ $banner->url_tujuan ? 'target=_blank' : '' }}>
                <img src="{{ url('storage/'.$banner->gambar) }}" alt="{{ $banner->judul }}" class="banner-img" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                <div class="banner-overlay">
                    <div class="banner-text">
                        <h2 class="banner-title-text">{{ $banner->judul }}</h2>
                        @if($banner->subjudul)
                        <p class="banner-sub-text">{{ $banner->subjudul }}</p>
                        @endif
                        @if($banner->url_tujuan)
                        <span class="banner-btn-label">{{ $banner->label_tombol }} <i class="fa-solid fa-arrow-right"></i></span>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    @if($banners->count() > 1)
    {{-- Dots --}}
    <div class="banner-dots">
        @foreach($banners as $i => $banner)
        <button class="banner-dot {{ $i === 0 ? 'active' : '' }}" onclick="goToBanner({{ $i }})" aria-label="Slide {{ $i+1 }}"></button>
        @endforeach
    </div>
    @endif
</div>

<style>
/* ===== BANNER SLIDESHOW ===== */
.banner-slider {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
    line-height: 0;
}
.banner-search-panel {
    position: relative;
    z-index: 2;
    margin-top: 0;
    padding: 1rem 0 0.75rem;
    background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.9) 100%);
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    order: 3;
}
.banner-search-panel .container {
    display: flex;
    justify-content: center;
}
.banner-search-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: min(100%, 760px);
    pointer-events: auto;
}
.banner-search-box {
    flex: 1;
    background: rgba(255,255,255,0.96);
    border: 1px solid rgba(255,255,255,0.9);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    backdrop-filter: blur(8px);
}
.banner-cart-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border: none;
    border-radius: 999px;
    background: #7CB342;
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.22);
    cursor: pointer;
    position: relative;
}
.banner-cart-btn:hover { background: #689F38; }
.banner-cart-btn .cart-badge {
    position: absolute;
    top: -4px;
    right: -4px;
}
.banner-track { position: relative; width: 100%; height: 100%; }
.banner-slide {
    display: none;
    position: relative;
    width: 100%;
    height: 100%;
}
.banner-slide.active { display: block; }
.banner-link { display: block; text-decoration: none; }
.banner-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    display: block;
    transition: transform 7s ease;
}
.banner-slide.active .banner-img { transform: scale(1.0); }
.banner-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.2) 50%, transparent 80%);
    display: flex;
    align-items: center;
}
.banner-text {
    padding: 0 2.5rem;
    max-width: 560px;
    line-height: 1.4;
}
.banner-title-text {
    font-size: clamp(1.25rem, 3vw, 2rem);
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.5rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    line-height: 1.2;
}
.banner-sub-text {
    font-size: clamp(0.82rem, 1.5vw, 1rem);
    color: rgba(255,255,255,0.9);
    margin: 0 0 1rem;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}
.banner-btn-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #7CB342;
    color: #fff;
    padding: 0.55rem 1.25rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    transition: background 0.2s;
}
.banner-link:hover .banner-btn-label { background: #689F38; }

/* Nav buttons */
.banner-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.25);
    border: none;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: background 0.2s;
    backdrop-filter: blur(4px);
}
.banner-nav:hover { background: rgba(255,255,255,0.45); }
.banner-prev { left: 1rem; }
.banner-next { right: 1rem; }

/* Dots */
.banner-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 10;
}
.banner-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.45);
    border: 1px solid rgba(255,255,255,0.6);
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
}
.banner-dot.active {
    background: #7CB342;
    width: 22px;
    border-radius: 4px;
    border-color: #7CB342;
}

@media (max-width: 768px) {
    .banner-slider { aspect-ratio: 16/9; margin-top: 60px; }
    .banner-text { padding: 0 1.25rem; max-width: 90%; }
    .banner-title-text { font-size: 1rem; }
    .banner-sub-text { display: none; }
    .banner-btn-label { font-size: 0.75rem; padding: 0.4rem 0.85rem; }
    .banner-nav { width: 26px; height: 26px; font-size: 0.7rem; top: 50%; }
    .banner-search-panel { padding: 0.8rem 0 0.6rem; }
    .banner-search-row { gap: 0.6rem; }
    .promo-grid { grid-template-columns: 1fr 1fr; gap: 1rem; padding: 0 0.75rem; }
    .promo-card { padding: 1.25rem 1.5rem; min-height: auto; align-items: center; }
    .promo-card > i { font-size: 3.5rem; }
    .promo-goapotik-logo { height: 72px; }
    .promo-card h4 { font-size: 1.35rem; }
    .promo-card p { font-size: 1rem; }}
@media (max-width: 480px) {
    .banner-slider { aspect-ratio: 4/3; margin-top: 55px; }
    .banner-nav { top: 50%; }
    .banner-prev { left: 0.4rem; }
    .banner-next { right: 0.4rem; }
    .banner-search-overlay .container { padding: 0 0.75rem; }
    .banner-search-row { flex-direction: column; align-items: stretch; }
    .banner-cart-btn { width: 100%; height: 46px; border-radius: 999px; }
    .promo-grid { grid-template-columns: 1fr; gap: 0.75rem; padding: 0 0.5rem; }
    .promo-card { padding: 1.25rem 1.5rem; min-height: auto; align-items: center; }
    .promo-card > i { font-size: 3.5rem; }
    .promo-goapotik-logo { height: 72px; }
    .promo-card h4 { font-size: 1.35rem; }
    .promo-card p { font-size: 1rem; }
}

.search-engine-section {
    background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
    padding: 2.5rem 0;
    margin: 0;
    box-shadow: 0 8px 32px rgba(13, 71, 161, 0.2);
    width: 100%;
}

.search-engine-wrapper {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
  width: 100%;
}

.search-engine-box {
    flex: 1;
  min-width: 0;
    max-width: 600px;
    display: flex;
    gap: 0.75rem;
    background: #fff;
    border-radius: 16px;
    padding: 0.75rem;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
    border: none;
    transition: all 0.3s ease;
}

.search-engine-box > i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
}

.search-engine-box:hover,
.search-engine-box:focus-within {
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.25);
    transform: translateY(-2px);
}

.search-engine-box input {
    flex: 1;
    border: none;
    outline: none;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    color: #374151;
    background: transparent;
}

.search-engine-box input::placeholder {
    color: #9ca3af;
}

.search-engine-box button {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    color: #fff;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.9rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
}

.search-engine-box button:hover {
    background: linear-gradient(135deg, #1565C0, #0D47A1);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(30, 136, 229, 0.4);
}

.cart-btn-home {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: #fff;
    color: #1E88E5;
    padding: 0.75rem 1.75rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    position: relative;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.cart-btn-home:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(30, 136, 229, 0.25);
}

.cart-badge-home {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    background: #ef4444;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 800;
    color: #fff;
}

@media (max-width: 768px) {
    .search-engine-section {
        padding: 1.5rem 0;
    }

    .search-engine-wrapper {
        gap: 0.75rem;
      padding: 0 1rem;
    }

    .search-engine-box {
        min-width: 100%;
        max-width: 100%;
    }

    .cart-btn-home {
        width: 100%;
        justify-content: center;
        font-size: 0.85rem;
        padding: 0.65rem 1.25rem;
    }
}

@media (max-width: 480px) {
    .search-engine-section {
        padding: 1rem 0;
    }

    .search-engine-wrapper {
      padding: 0 0.75rem;
    }

    .search-engine-box {
        min-width: 100%;
        max-width: 100%;
        padding: 0.6rem;
    }

    .search-engine-box input {
        padding: 0.6rem 0.75rem;
        font-size: 0.85rem;
    }

    .search-engine-box button {
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        gap: 0.3rem;
    }

    .search-engine-box button i {
        font-size: 0.85rem;
    }

    .search-engine-box button span {
        display: none;
    }

    .cart-btn-home {
        width: 100%;
        padding: 0.6rem 1rem;
        font-size: 0.75rem;
    }
}
</style>

<script>
(function() {
    let current = 0;
    const slides = document.querySelectorAll('.banner-slide');
    const dots   = document.querySelectorAll('.banner-dot');
    const total  = slides.length;
    let timer;

    function show(idx) {
        slides[current].classList.remove('active');
        if (dots[current]) dots[current].classList.remove('active');
        current = (idx + total) % total;
        slides[current].classList.add('active');
        if (dots[current]) dots[current].classList.add('active');
    }

    function startTimer() {
        clearInterval(timer);
        if (total > 1) timer = setInterval(() => show(current + 1), 4500);
    }

    window.slideBanner = function(dir) { show(current + dir); startTimer(); };
    window.goToBanner  = function(idx) { show(idx); startTimer(); };

    startTimer();

    // Pause on hover
    const slider = document.getElementById('bannerSlider');
    if (slider) {
        slider.addEventListener('mouseenter', () => clearInterval(timer));
        slider.addEventListener('mouseleave', startTimer);
    }
})();
</script>
@endif

{{-- QUICK CATEGORY --}}
<div class="quick-section">
  <div class="container">
    <div class="quick-row">
      <a href="{{ route('products.index') }}" class="quick-btn active"><i class="fa-solid fa-th-large"></i><span>Semua</span></a>
      <a href="{{ route('products.pbf') }}" class="quick-btn"><i class="fa-solid fa-box" style="color:#f59e0b;"></i><span>PBF</span></a>
      <a href="{{ route('products.apotek') }}" class="quick-btn"><i class="fa-solid fa-store" style="color:#059669;"></i><span>Apotek</span></a>
      <a href="{{ route('products.index') }}?kategori_produk=OBAT" class="quick-btn"><i class="fa-solid fa-pills" style="color:#1E88E5;"></i><span>Obat</span></a>
      <a href="{{ route('products.index') }}?kategori_produk=SKINCARE+%26+KOSMETIK" class="quick-btn"><i class="fa-solid fa-spa" style="color:#e91e8c;"></i><span>Skincare</span></a>
      <a href="{{ route('products.index') }}?kategori_produk=ALAT+KESEHATAN" class="quick-btn"><i class="fa-solid fa-stethoscope" style="color:#0288d1;"></i><span>ALKES</span></a>
    </div>
  </div>
</div>

{{-- PROMO --}}
<div class="promo-section">
  <div class="container">
    <div class="promo-grid">
      <a href="{{ route('contact') }}" class="promo-card promo-contact">
        <i class="fa-solid fa-headset"></i><div><h4>Hubungi Kami</h4><p>Butuh bantuan? Kontak tim Medikpedia sekarang.</p></div>
      </a>
      <a href="https://store.goapotik.com/penjual/apotek-medikpedia" target="_blank" rel="noopener" class="promo-card promo-goapotik">
        <img src="{{ asset('LOGO GOAPOTIK.png') }}" alt="GoApotik" class="promo-goapotik-logo">
        <div><h4>GoApotik</h4><p>Kunjungi toko kami di Go Apotik.</p></div>
      </a>
    </div>
  </div>
</div>

{{-- KATEGORI PILIHAN --}}
@include('components.category-selection')

<div class="search-engine-section">
    <div class="container">
        <div class="search-engine-wrapper">
            <form method="GET" action="{{ route('products.index') }}" class="search-engine-box">
                <i class="fa-solid fa-magnifying-glass" style="color: #9ca3af; padding: 0 0.5rem; font-size: 1.05rem; flex-shrink: 0;"></i>
                <input type="text" name="search" placeholder="Cari obat, vitamin, skincare, ALKES...">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Cari</span>
                </button>
            </form>
            <button class="cart-btn-home" onclick="if(typeof openCart==='function'){openCart();}else{window.location.href='{{ route('products.index') }}';}">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Keranjang</span>
                <span class="cart-badge-home" id="cartBadgeHome">0</span>
            </button>
        </div>
    </div>
</div>

<style>
/* ===== FEATURED CAROUSEL ===== */
.featured-section { padding: 3rem 0; }
.featured-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.5rem; flex-wrap: wrap; gap: .75rem;
}
.featured-nav {
    display: flex; align-items: center; gap: .5rem;
}
.featured-nav-btn {
    width: 38px; height: 38px; border-radius: 50%;
    border: 2px solid #e5e7eb; background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #374151; font-size: .85rem;
    transition: all .2s; flex-shrink: 0;
}
.featured-nav-btn:hover,
.featured-nav-btn.active-page { border-color: #1E88E5; background: #1E88E5; color: #fff; }
.featured-nav-btn:disabled { opacity: .35; cursor: not-allowed; }

/* Track geser */
.featured-track-wrap {
    overflow: hidden;
    border-radius: 16px;
}
.featured-track {
    display: flex;
    gap: 1rem;
    transition: transform .4s cubic-bezier(.25,.8,.25,1);
    will-change: transform;
}

/* Card di carousel — lebar tetap */
.featured-track .prod-card {
    flex: 0 0 calc(20% - .8rem); /* 5 per baris desktop */
    min-width: 0;
}

/* Dots */
.featured-dots {
    display: flex; justify-content: center; gap: .5rem; margin-top: 1.25rem;
}
.featured-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #d1d5db; border: none; cursor: pointer;
    transition: all .25s; padding: 0;
}
.featured-dot.active { background: #1E88E5; width: 24px; border-radius: 4px; }

/* CTA bawah */
.featured-cta { text-align: center; margin-top: 1.75rem; }

/* ===== MOBILE: 4 card terlihat, sisa geser ===== */
@media (max-width: 768px) {
    .featured-track .prod-card {
        flex: 0 0 calc(50% - .5rem); /* 2 per baris → 4 visible */
    }
    .featured-nav { display: none; } /* sembunyikan tombol prev/next di mobile */
    .featured-track-wrap { overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .featured-track { transition: none; }
    .featured-track .prod-card { scroll-snap-align: start; }
}
@media (max-width: 480px) {
    .featured-track .prod-card {
        flex: 0 0 calc(50% - .5rem);
    }
}
</style>

<section class="featured-section">
  <div class="container">
    <div class="featured-header">
      <div class="sec-head-left">
        <span class="sec-tag">🔥 Terlaris</span>
        <h2 class="sec-title" style="margin:0;">Produk Unggulan</h2>
      </div>
      <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
        @php $totalPages = ceil($featuredProducts->count() / 10); @endphp
        @if($featuredProducts->count() > 0)
        <div class="featured-nav">
          <button class="featured-nav-btn" id="featPrev" onclick="featMove(-1)" disabled>
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <span id="featPageInfo" style="font-size:.82rem;color:#6b7280;min-width:50px;text-align:center;">1 / {{ $totalPages }}</span>
          <button class="featured-nav-btn" id="featNext" onclick="featMove(1)" {{ $totalPages <= 1 ? 'disabled' : '' }}>
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>
        @endif
        <a href="{{ route('products.index') }}" class="sec-link">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>

    @if($featuredProducts->count() > 0)
    <div class="featured-track-wrap" id="featWrap">
      <div class="featured-track" id="featTrack">
        @foreach($featuredProducts as $med)
        <div class="prod-card">
          <div class="prod-img">
            @if($med->gambar)
              <img src="{{ url('storage/'.$med->gambar) }}" alt="{{ $med->nama_obat }}" loading="lazy">
            @else
              <i class="fa-solid fa-pills no-img-icon"></i>
            @endif
            @if($med->kategori_produk)
              <span class="prod-badge-label">{{ $med->kategori_produk==='SKINCARE & KOSMETIK'?'✨':($med->kategori_produk==='ALAT KESEHATAN'?'🩺':'💊') }}</span>
            @endif
          </div>
          <div class="prod-body">
            @if($med->kategori)<span class="prod-brand-tag">{{ $med->kategori }}</span>@endif
            <h3 class="prod-name">{{ $med->nama_obat }}</h3>
            <div class="prod-price">{{ $med->getFormattedPrice() }}</div>
            @if($med->stok > 10)
              <span class="stock-ok"><i class="fa-solid fa-circle-check"></i> Stok Tersedia</span>
            @elseif($med->stok > 0)
              <span class="stock-low"><i class="fa-solid fa-triangle-exclamation"></i> Sisa {{ $med->stok }}</span>
            @else
              <span class="stock-out"><i class="fa-solid fa-circle-xmark"></i> Habis</span>
            @endif
            <a href="{{ route('medicines.show', $med->id) }}" class="btn-detail">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a>
            @if($med->stok > 0)
            <button class="btn-cart" onclick="addToCart({{ $med->id }},'{{ addslashes($med->nama_obat) }}',{{ $med->harga }},'{{ $med->gambar ? url('storage/'.$med->gambar) : '' }}',this)">
              <i class="fa-solid fa-cart-plus"></i> Keranjang
            </button>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Dots pagination --}}
    @if($totalPages > 1)
    <div class="featured-dots" id="featDots">
      @for($i = 0; $i < $totalPages; $i++)
        <button class="featured-dot {{ $i===0?'active':'' }}" onclick="featGoTo({{ $i }})"></button>
      @endfor
    </div>
    @endif

    @else
    <div style="text-align:center;padding:3rem;background:#fff;border-radius:16px;border:1px solid #e5e7eb;">
      <i class="fa-solid fa-box-open" style="font-size:3rem;color:#d1d5db;display:block;margin-bottom:1rem;"></i>
      <p style="color:#6b7280;margin:0;">Belum ada produk tersedia saat ini.</p>
    </div>
    @endif

    <div class="featured-cta">
      <a href="{{ route('products.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.7rem 2.25rem;background:linear-gradient(135deg,#1E88E5,#1565C0);color:#fff;border-radius:50px;text-decoration:none;font-weight:700;font-size:0.9rem;transition:all 0.25s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
        <i class="fa-solid fa-pills"></i> Lihat Semua Produk
      </a>
    </div>
  </div>
</section>

<script>
(function () {
    const ITEMS_PER_PAGE = 10;
    let currentPage = 0;
    const track = document.getElementById('featTrack');
    const wrap  = document.getElementById('featWrap');
    const dots  = document.querySelectorAll('.featured-dot');
    const prevBtn = document.getElementById('featPrev');
    const nextBtn = document.getElementById('featNext');
    const pageInfo = document.getElementById('featPageInfo');

    if (!track) return;

    const cards  = track.querySelectorAll('.prod-card');
    const total  = cards.length;
    const pages  = Math.ceil(total / ITEMS_PER_PAGE);

    function getCardWidth() {
        if (!cards.length) return 0;
        const style = getComputedStyle(track);
        const gap   = parseFloat(style.gap) || 16;
        return cards[0].getBoundingClientRect().width + gap;
    }

    function goTo(page) {
        if (page < 0 || page >= pages) return;
        currentPage = page;

        // Geser track sebesar (page * 10 cards)
        const cardW  = getCardWidth();
        const offset = currentPage * ITEMS_PER_PAGE * cardW;
        track.style.transform = `translateX(-${offset}px)`;

        // Update dots
        dots.forEach((d, i) => d.classList.toggle('active', i === currentPage));

        // Update nav buttons
        if (prevBtn) prevBtn.disabled = currentPage === 0;
        if (nextBtn) nextBtn.disabled = currentPage >= pages - 1;
        if (pageInfo) pageInfo.textContent = (currentPage + 1) + ' / ' + pages;
    }

    window.featMove = function (dir) { goTo(currentPage + dir); };
    window.featGoTo = function (p)   { goTo(p); };

    // Touch / swipe support
    let touchStartX = 0;
    wrap && wrap.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    wrap && wrap.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) goTo(currentPage + (diff > 0 ? 1 : -1));
    });
})();
</script>


{{-- PROMO PRODUK --}}
@if(isset($promoProducts) && $promoProducts->count())
<style>
/* =============================================
   PROMO PRODUK — Simple & Elegant Background
   ============================================= */
.promo-products-section {
    padding: 3rem 0;
    background: linear-gradient(135deg, #1E88E5 0%, #2e7d32 100%);
    position: relative;
    overflow: hidden;
    border-top: none;
    border-bottom: none;
}

.promo-products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    gap: 0.75rem;
    flex-wrap: wrap;
    position: relative;
    z-index: 2;
}

.promo-products-header .sec-tag {
    font-size: 0.875rem;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.promo-products-header .sec-title {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    letter-spacing: -0.5px;
}

/* Horizontal scroll grid */
.promo-products-track-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.5) rgba(255, 255, 255, 0.1);
    margin: 0 -1rem;
    padding: 0.5rem 1rem;
    position: relative;
    z-index: 2;
}

.promo-products-track-wrap::-webkit-scrollbar {
    height: 8px;
}

.promo-products-track-wrap::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.promo-products-track-wrap::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.5);
    border-radius: 4px;
}

.promo-products-track-wrap::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.8);
}

.promo-products-track {
    display: flex;
    gap: 1.2rem;
    width: max-content;
    padding: 0.5rem 0;
}

/* Individual promo photo card — Clean Design */
.promo-photo-card {
    width: 165px;
    height: 165px;
    border-radius: 18px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
    background: #fff;
    border: 2px solid rgba(255, 255, 255, 0.4);
}

.promo-photo-card:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
    border-color: rgba(255, 255, 255, 0.8);
}

.promo-photo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.promo-photo-card:hover img { transform: scale(1.08); }

/* Simple overlay on hover */
.promo-photo-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(30, 136, 229, 0.3) 0%, rgba(56, 192, 155, 0.2) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.promo-photo-card:hover::before { opacity: 1; }

/* Star badge */
.promo-photo-card::after {
    content: '★';
    position: absolute;
    top: 10px;
    right: 10px;
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #fff100 0%, #ffb300 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(255, 179, 0, 0.4);
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    opacity: 0;
    transition: opacity 0.3s ease;
    transform: scale(0);
}

.promo-photo-card:hover::after { 
    opacity: 1; 
    transform: scale(1);
}

@media (max-width: 768px) {
    .promo-products-section { padding: 2.5rem 0; }
    .promo-products-header .sec-title { font-size: 1.6rem; }
    .promo-photo-card { width: 145px; height: 145px; border-radius: 16px; }
    .promo-products-track { gap: 0.95rem; }
}

@media (max-width: 600px) {
    .promo-products-section { padding: 2rem 0; }
    .promo-photo-card { width: 130px; height: 130px; border-radius: 14px; }
    .promo-products-track { gap: 0.8rem; }
    .promo-products-header .sec-title { font-size: 1.4rem; }
    .promo-products-header .sec-tag { font-size: 0.75rem; padding: 0.4rem 0.8rem; }
}

@media (max-width: 400px) {
    .promo-photo-card { width: 115px; height: 115px; border-radius: 12px; }
    .promo-products-track { gap: 0.6rem; }
    .promo-photo-card::after { width: 28px; height: 28px; font-size: 14px; top: 6px; right: 6px; }
}
</style>

<section class="promo-products-section">
  <div class="container">
    <div class="promo-products-header">
      <div class="sec-head-left">
        <span class="sec-tag">🏷️ PENAWARAN EKSKLUSIF</span>
        <h2 class="sec-title">Promo Spesial Hari Ini</h2>
      </div>
    </div>

    <div class="promo-products-track-wrap">
      <div class="promo-products-track">
        @foreach($promoProducts as $promo)
          @if($promo->url_tujuan)
            <a href="{{ $promo->url_tujuan }}" class="promo-photo-card" title="{{ $promo->judul }}" data-tooltip="{{ $promo->judul }}">
          @else
            <span class="promo-photo-card" title="{{ $promo->judul }}" data-tooltip="{{ $promo->judul }}">
          @endif
            <img src="{{ url('storage/'.$promo->gambar) }}" alt="{{ $promo->judul }}" loading="lazy">
          @if($promo->url_tujuan)
            </a>
          @else
            </span>
          @endif
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif


{{-- WHY US --}}
<style>
/* =============================================
   KENAPA PILIH KAMI — Modern Card Section
   ============================================= */
.why-cards-section {
    margin-top: 0;
    padding: 5rem 0 5.5rem;
    background: linear-gradient(160deg, #f0f7ff 0%, #eef5ff 50%, #f6fbff 100%);
    position: relative;
    overflow: hidden;
}
.why-cards-section::before {
    content: '';
    position: absolute;
    top: -120px; left: -120px;
    width: 420px; height: 420px;
    background: radial-gradient(circle, rgba(30,136,229,.10) 0%, transparent 70%);
    pointer-events: none;
}
.why-cards-section::after {
    content: '';
    position: absolute;
    bottom: -100px; right: -80px;
    width: 360px; height: 360px;
    background: radial-gradient(circle, rgba(124,179,66,.09) 0%, transparent 70%);
    pointer-events: none;
}

/* Section heading */
.why-section-head {
    text-align: center;
    margin-bottom: 2.5rem;
}
.why-section-tag {
    display: inline-flex; align-items: center; gap: .45rem;
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    color: #fff;
    padding: .45rem 1.2rem;
    border-radius: 999px;
    font-size: .8rem; font-weight: 700;
    letter-spacing: .04em;
    box-shadow: 0 6px 20px rgba(30,136,229,.3);
    margin-bottom: .85rem;
}
.why-section-title {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 900;
    color: #0f172a;
    margin: 0 0 .5rem;
    line-height: 1.2;
}
.why-section-sub {
    font-size: .95rem;
    color: #64748b;
    margin: 0;
}

/* Grid */
.why-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

/* Card */
.why-card {
    background: #fff;
    border-radius: 20px;
    padding: 2.25rem 2rem;
    border: 1.5px solid rgba(148, 163, 184, 0.15);
    box-shadow: 0 4px 24px rgba(15,23,42,.06), 0 1px 4px rgba(15,23,42,.04);
    display: flex;
    flex-direction: column;
    gap: 0;
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    position: relative;
    overflow: hidden;
}
/* Coloured top accent bar */
.why-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    border-radius: 20px 20px 0 0;
}
.why-card:nth-child(1)::before { background: linear-gradient(90deg, #1E88E5 0%, #64b5f6 100%); }
.why-card:nth-child(2)::before { background: linear-gradient(90deg, #2e7d32 0%, #66bb6a 100%); }
.why-card:nth-child(3)::before { background: linear-gradient(90deg, #e65100 0%, #ffb74d 100%); }

/* Hover lift */
.why-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 48px rgba(15,23,42,.12), 0 4px 12px rgba(15,23,42,.06);
    border-color: rgba(30,136,229,.25);
}

/* Icon circle */
.why-card-icon-wrap {
    width: 64px; height: 64px;
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.65rem;
    margin-bottom: 1.25rem;
    flex-shrink: 0;
    transition: transform .3s ease;
}
.why-card:hover .why-card-icon-wrap { transform: scale(1.08) rotate(-4deg); }
.why-card:nth-child(1) .why-card-icon-wrap { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #1565C0; }
.why-card:nth-child(2) .why-card-icon-wrap { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #2e7d32; }
.why-card:nth-child(3) .why-card-icon-wrap { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; }

/* Title */
.why-card h4 {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 .65rem;
    line-height: 1.3;
}

/* Body text */
.why-card p {
    font-size: .92rem;
    color: #64748b;
    line-height: 1.75;
    margin: 0 0 1.25rem;
    flex: 1;
}

/* Badge pill */
.why-card-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    width: fit-content;
    font-size: .76rem; font-weight: 700;
    padding: .4rem .85rem;
    border-radius: 999px;
}
.why-card:nth-child(1) .why-card-badge { background: #e3f2fd; color: #1565C0; }
.why-card:nth-child(2) .why-card-badge { background: #e8f5e9; color: #1b5e20; }
.why-card:nth-child(3) .why-card-badge { background: #fff3e0; color: #bf360c; }

/* ---- Responsive ---- */
@media (max-width: 900px) {
    .why-cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.1rem; }
    .why-cards-section { padding: 3.5rem 0 4rem; }
}
@media (max-width: 600px) {
    .why-cards-section { padding: 2.5rem 0 3rem; }
    .why-section-head { margin-bottom: 1.75rem; }
    .why-cards-grid {
        grid-template-columns: 1fr;
        gap: .9rem;
    }
    /* On mobile show cards side-by-side (2 cols) for the top two */
    .why-cards-grid .why-card:nth-child(1),
    .why-cards-grid .why-card:nth-child(2) {
        /* still 1 col on very small screens */
    }
    .why-card {
        padding: 1.5rem 1.25rem;
        border-radius: 16px;
        flex-direction: row;
        align-items: flex-start;
        gap: 1rem;
    }
    .why-card-icon-wrap {
        width: 50px; height: 50px;
        border-radius: 14px;
        font-size: 1.3rem;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    .why-card-content { flex: 1; }
    .why-card h4 { font-size: .98rem; margin-bottom: .4rem; }
    .why-card p { font-size: .84rem; line-height: 1.6; margin-bottom: .75rem; }
    .why-card-badge { font-size: .7rem; padding: .3rem .65rem; }
}
@media (max-width: 400px) {
    .why-card { padding: 1.2rem 1rem; }
    .why-card-icon-wrap { width: 44px; height: 44px; font-size: 1.15rem; }
}
</style>

<section class="why-cards-section">
  <div class="container">

    {{-- Section heading --}}
    <div class="why-section-head">
      <div>
        <span class="why-section-tag"><i class="fa-solid fa-star"></i> Kenapa Pilih Kami?</span>
      </div>
      <h2 class="why-section-title">Keunggulan Medikpedia</h2>
      <p class="why-section-sub">Kami hadir sebagai mitra terpercaya untuk kebutuhan farmasi Anda</p>
    </div>

    <div class="why-cards-grid">

      {{-- Card 1 --}}
      <div class="why-card">
        <div class="why-card-icon-wrap"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="why-card-content">
          <h4>Produk Original & Terjamin</h4>
          <p>Semua produk bersertifikat BPOM, berstandar GMP, dan dijamin keasliannya langsung dari pabrikan resmi terpercaya.</p>
          <span class="why-card-badge"><i class="fa-solid fa-circle-check"></i> Bersertifikat BPOM</span>
        </div>
      </div>

      {{-- Card 2 --}}
      <div class="why-card">
        <div class="why-card-icon-wrap"><i class="fa-solid fa-truck-fast"></i></div>
        <div class="why-card-content">
          <h4>Pengiriman Cepat & Aman</h4>
          <p>Langsung dari distributor ke seluruh Indonesia. Sistem logistik handal dengan cold chain untuk produk berkebutuhan khusus.</p>
          <span class="why-card-badge"><i class="fa-solid fa-location-dot"></i> Seluruh Indonesia</span>
        </div>
      </div>

      {{-- Card 3 --}}
      <div class="why-card">
        <div class="why-card-icon-wrap"><i class="fa-solid fa-tag"></i></div>
        <div class="why-card-content">
          <h4>Harga Distributor Terbaik</h4>
          <p>Harga langsung dari distributor tanpa markup berlebih. Hemat lebih banyak dengan berbelanja langsung dari sumbernya.</p>
          <span class="why-card-badge"><i class="fa-solid fa-percent"></i> Harga Terjangkau</span>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- CTA WHATSAPP --}}
<div class="cta-section">
  <div class="container">
    <div class="cta-box">
      <div>
        <h3>💬 Mau pesan via WhatsApp?</h3>
        <p>Tim kami siap bantu proses pesanan Anda dengan cepat & mudah.</p>
      </div>
      <a href="https://wa.me/6285890007359?text=Halo%20Medikpedia%2C%20saya%20ingin%20memesan%20produk." target="_blank" class="btn-wa">
        <i class="fa-brands fa-whatsapp" style="font-size:1.3rem;"></i> Chat WhatsApp
      </a>
    </div>
  </div>
</div>

{{-- KATEGORI --}}
<section class="cat-section">
  <div class="container">
    <div class="sec-head">
      <div class="sec-head-left">
        <span class="sec-tag">🗂️ Kategori</span>
        <h2 class="sec-title">Belanja Berdasarkan Kategori</h2>
      </div>
    </div>
    <div class="cat-grid">
      <a href="{{ route('products.pbf') }}" class="cat-card">
        <div class="cat-icon" style="background:#fef3c7;"><i class="fa-solid fa-box" style="color:#f59e0b;"></i></div>
        <span>PBF</span>
      </a>
      <a href="{{ route('products.apotek') }}" class="cat-card">
        <div class="cat-icon" style="background:#d1fae5;"><i class="fa-solid fa-store" style="color:#059669;"></i></div>
        <span>Apotek</span>
      </a>
      <a href="{{ route('products.index') }}?kategori_produk=OBAT" class="cat-card">
        <div class="cat-icon" style="background:#e3f2fd;"><i class="fa-solid fa-pills" style="color:#1E88E5;"></i></div>
        <span>Obat</span>
      </a>
      <a href="{{ route('products.index') }}?kategori_produk=SKINCARE+%26+KOSMETIK" class="cat-card">
        <div class="cat-icon" style="background:#fce4ec;"><i class="fa-solid fa-spa" style="color:#e91e8c;"></i></div>
        <span>Skincare & Kosmetik</span>
      </a>
      <a href="{{ route('products.index') }}?kategori_produk=ALAT+KESEHATAN" class="cat-card">
        <div class="cat-icon" style="background:#e8f5e9;"><i class="fa-solid fa-stethoscope" style="color:#2e7d32;"></i></div>
        <span>ALKES</span>
      </a>
      <a href="{{ route('products.index') }}" class="cat-card">
        <div class="cat-icon" style="background:#fff3e0;"><i class="fa-solid fa-capsules" style="color:#e65100;"></i></div>
        <span>Obat Bebas</span>
      </a>
      <a href="{{ route('products.index') }}" class="cat-card">
        <div class="cat-icon" style="background:#ede7f6;"><i class="fa-solid fa-tablets" style="color:#7b1fa2;"></i></div>
        <span>Vitamin & Suplemen</span>
      </a>
    </div>
  </div>
</section>

{{-- TENTANG SINGKAT --}}
<div class="about-strip">
  <div class="container">
    <div class="about-box">
      <img src="{{ asset('logo1.png') }}" alt="Medikpedia" class="about-logo">
      <div class="about-info">
        <h3><span style="color:#1E88E5;">Medik</span><span style="color:#7CB342;">pedia</span> — Distributor Farmasi Terpercaya</h3>
        <p>Sejak 2016 melayani kebutuhan medis masyarakat & praktisi kesehatan di seluruh Indonesia. Produk original, harga distributor, pengiriman cepat.</p>
        <a href="{{ route('about') }}" class="btn-about"><i class="fa-solid fa-circle-info"></i> Selengkapnya Tentang Kami</a>
      </div>
      <div class="about-stats">
        <div class="about-stat-item"><span class="n">15+</span><span class="l">Tahun Pengalaman</span></div>
        <div class="about-stat-item"><span class="n">100+</span><span class="l">Brand Partner</span></div>
        <div class="about-stat-item"><span class="n">50+</span><span class="l">Kota Jangkauan</span></div>
        <div class="about-stat-item"><span class="n">24/7</span><span class="l">Layanan Aktif</span></div>
      </div>
    </div>
  </div>
</div>

{{-- SEMUA PRODUK DI BAWAH ABOUT STRIP --}}
<section class="prod-section" style="padding-top:1rem; padding-bottom:2rem; background:#f8fbff;">
  <div class="container">
    <div class="sec-head" style="margin-bottom:1rem;">
      <div class="sec-head-left">
        <span class="sec-tag">🛍️ Semua Produk</span>
        <h2 class="sec-title">Katalog Produk Lengkap</h2>
      </div>
      <a href="{{ route('products.index') }}" class="sec-link">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    @if($allProducts->count() > 0)
      <div class="prod-grid" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1rem;">
        <style>
          @media (max-width: 576px) {
            .prod-grid {
              grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
              gap: 0.75rem !important;
            }
          }
        </style>
        @foreach($allProducts as $med)
          <div class="prod-card">
            <div class="prod-img">
              @if($med->gambar)
                <img src="{{ url('storage/' . $med->gambar) }}" alt="{{ $med->nama_obat }}">
              @else
                <div class="prod-img-fallback"><i class="fa-solid fa-pills"></i></div>
              @endif
              @if($med->kategori_produk)
                <span class="prod-badge-label">{{ $med->kategori_produk==='SKINCARE & KOSMETIK'?'✨':($med->kategori_produk==='ALAT KESEHATAN'?'🩺':'💊') }}</span>
              @endif
            </div>
            <div class="prod-body">
              @if($med->kategori)
                <span class="prod-brand-tag">{{ $med->kategori }}</span>
              @endif
              <h3 class="prod-name">{{ $med->nama_obat }}</h3>
              <div class="prod-price">{{ $med->getFormattedPrice() }}</div>
              @if($med->stok > 0)
                <div class="prod-stock">Stok: {{ $med->stok }}</div>
              @else
                <div class="prod-stock prod-stock-out">Habis</div>
              @endif
              <div class="prod-actions">
                <a href="{{ route('medicines.show', $med->id) }}" class="btn-detail">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a>
                @if($med->stok > 0)
                  <button class="btn-cart" onclick="addToCart({{ $med->id }},'{{ addslashes($med->nama_obat) }}',{{ $med->harga }},'{{ $med->gambar ? url('storage/'.$med->gambar) : '' }}',this)">
                    <i class="fa-solid fa-cart-plus"></i> Keranjang
                  </button>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p style="color:#475569;">Belum ada produk untuk ditampilkan saat ini.</p>
    @endif
  </div>
</section>

@endsection

@section('scripts')
@include('partials.cart')
@endsection
