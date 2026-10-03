<!-- Category Breadcrumb Helper -->
<div style="padding: 1rem 0; background: #f8faff; border-bottom: 1px solid #e5e7eb;">
    <div class="container">
        <div style="display: flex; gap: 0.5rem; align-items: center; font-size: 0.85rem; color: #6b7280;">
            <a href="{{ route('home') }}" style="color: #1E88E5; text-decoration: none; font-weight: 600;">
                <i class="fa-solid fa-home"></i> Home
            </a>
            <span>/</span>
            <a href="{{ route('products.index') }}" style="color: #1E88E5; text-decoration: none; font-weight: 600;">
                Katalog
            </a>
            @if(isset($mainCategory))
            <span>/</span>
            <span style="color: #374151; font-weight: 600;">{{ ucfirst($mainCategory) }}</span>
            @endif
            @if(isset($subCategory))
            <span>/</span>
            <span style="color: #1f2937; font-weight: 700;">{{ ucfirst(str_replace('-', ' ', $subCategory)) }}</span>
            @endif
        </div>
    </div>
</div>
