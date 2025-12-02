@extends('shop.layout')

@section('title', 'جميع المنتجات - رحيق')

@push('styles')
<style>
    /* صفحة المنتجات الاحترافية */
    .products-page {
        padding-top: 90px;
        background: #FAFAFA;
        min-height: 100vh;
    }

    /* Header */
    .page-header-clean {
        background: white;
        padding: 2rem 0;
        border-bottom: 1px solid #E8E8E8;
        margin-bottom: 2rem;
    }

    .page-header-clean h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-modern {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .breadcrumb-modern .breadcrumb-item {
        color: #666;
        display: flex;
        align-items: center;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        color: #999;
    }

    .breadcrumb-modern .breadcrumb-item a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .breadcrumb-modern .breadcrumb-item a:hover {
        color: #D4A017;
        background: rgba(212, 160, 23, 0.1);
    }

    .breadcrumb-modern .breadcrumb-item.active {
        font-weight: 400;
    }

    @media (max-width: 576px) {
        .breadcrumb-modern {
            font-size: 0.8rem;
        }
    }

    /* Toolbar - احترافي */
    .toolbar {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .results-count {
        color: #666;
        font-size: 0.95rem;
    }

    .results-count strong {
        color: #000;
        font-weight: 600;
    }

    .toolbar-right {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    /* زر الفلاتر */
    .btn-filter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.2rem;
        border: 2px solid #E8E8E8;
        background: white;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-filter:hover {
        border-color: #D4A017;
        background: rgba(212, 160, 23, 0.05);
        color: #D4A017;
    }

    .btn-filter i {
        font-size: 1.1rem;
    }

    /* Sort Select */
    .sort-select {
        border: 2px solid #E8E8E8;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        background: white;
        font-size: 0.9rem;
        color: #333;
        min-width: 220px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .sort-select:focus {
        border-color: #D4A017;
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.1);
    }

    /* Offcanvas للفلاتر */
    .offcanvas {
        max-width: 350px;
    }

    .offcanvas-header {
        border-bottom: 1px solid #F0F0F0;
        padding: 1.5rem;
    }

    .offcanvas-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .offcanvas-title i {
        color: #D4A017;
    }

    .btn-close {
        font-size: 0.9rem;
    }

    .offcanvas-body {
        padding: 1.5rem;
    }

    /* Filter Sections */
    .filter-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #F0F0F0;
    }

    .filter-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .filter-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: #D4A017;
    }

    /* Search */
    .filter-search {
        position: relative;
    }

    .filter-search input {
        width: 100%;
        padding: 0.65rem 1rem;
        padding-left: 2.5rem;
        border: 2px solid #E8E8E8;
        border-radius: 10px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .filter-search input:focus {
        border-color: #D4A017;
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.1);
    }

    .filter-search i {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    /* Categories */
    .category-list {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0.8rem;
        border-radius: 8px;
        text-decoration: none;
        color: #666;
        transition: all 0.3s;
        font-size: 0.85rem;
    }

    .category-item:hover {
        background: rgba(212, 160, 23, 0.05);
        color: #D4A017;
        padding-right: 1.2rem;
    }

    .category-item.active {
        background: rgba(212, 160, 23, 0.1);
        color: #D4A017;
        font-weight: 600;
    }

    .category-item-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-item-img {
        width: 35px;
        height: 35px;
        border-radius: 6px;
        object-fit: cover;
    }

    .category-count {
        background: #F0F0F0;
        color: #666;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .category-item.active .category-count {
        background: #D4A017;
        color: white;
    }

    /* Quick Filters */
    .quick-filters {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .quick-filter {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 0.8rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.85rem;
        color: #666;
    }

    .quick-filter:hover {
        background: rgba(212, 160, 23, 0.05);
        color: #D4A017;
    }

    .quick-filter input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #D4A017;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    /* Product Card */
    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        background: #FAFAFA;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        z-index: 2;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge-featured {
        background: linear-gradient(135deg, #FFD700 0%, #D4A017 100%);
        box-shadow: 0 3px 10px rgba(212, 160, 23, 0.5);
    }

    .badge-bestseller {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.5);
    }

    .badge-new {
        background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%);
        box-shadow: 0 3px 10px rgba(78, 205, 196, 0.5);
    }

    .badge-sale {
        background: linear-gradient(135deg, #FF6B6B 0%, #ee5a5a 100%);
        box-shadow: 0 3px 10px rgba(255, 107, 107, 0.5);
    }

    .product-content {
        padding: 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        color: #D4A017;
        font-size: 0.75rem;
        margin-bottom: 0.4rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 0.6rem;
        line-height: 1.4;
        min-height: 44px;
        flex: 1;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #D4A017;
        margin-bottom: 0.8rem;
    }

    .btn-add-cart {
        background: #D4A017;
        color: white;
        width: 100%;
        padding: 0.7rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-add-cart:hover {
        background: #B8860B;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 160, 23, 0.3);
    }

    /* Empty State - تحسين حالة الفراغ */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #FAFAFA 0%, #F5F5F5 100%);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 2px dashed #E0E0E0;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #FFF9E6 0%, #FFF5D6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 3px solid rgba(212, 160, 23, 0.2);
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: #D4A017;
    }

    .empty-state i:not(.empty-state-icon i) {
        font-size: 4rem;
        color: #D4A017;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-state .suggestions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }

    .empty-state .suggestion-tag {
        background: white;
        color: #666;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        border: 1px solid #E0E0E0;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .empty-state .suggestion-tag:hover {
        background: #D4A017;
        color: white;
        border-color: #D4A017;
        transform: translateY(-2px);
    }

    /* Pagination */
    .pagination {
        margin-top: 2rem;
        justify-content: center;
    }

    .pagination .page-link {
        border: 2px solid #E8E8E8;
        color: #666;
        padding: 0.6rem 1rem;
        margin: 0 0.25rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .pagination .page-link:hover {
        border-color: #D4A017;
        background: rgba(212, 160, 23, 0.05);
        color: #D4A017;
    }

    .pagination .page-item.active .page-link {
        background: #D4A017;
        border-color: #D4A017;
        color: white;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 992px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .products-page {
            padding-top: 80px;
        }

        .page-header-clean {
            padding: 1.5rem 0;
        }

        .page-header-clean h1 {
            font-size: 1.5rem;
        }

        .toolbar {
            padding: 1rem;
            flex-direction: column;
            align-items: stretch;
        }

        .results-count {
            text-align: center;
            font-size: 0.85rem;
        }

        .toolbar-right {
            width: 100%;
            justify-content: space-between;
        }

        .sort-select {
            min-width: auto;
            flex: 1;
            font-size: 0.85rem;
            padding: 0.6rem 0.8rem;
        }

        .btn-filter {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .product-content {
            padding: 1rem;
        }

        .product-title {
            font-size: 0.95rem;
            min-height: auto;
        }

        .product-price {
            font-size: 1.1rem;
        }

        .btn-add-cart {
            font-size: 0.8rem;
            padding: 0.6rem;
        }
    }
</style>
@endpush

@section('content')
<div class="products-page">
    <!-- Header -->
    <div class="page-header-clean">
        <div class="container">
            <h1>جميع المنتجات</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-modern">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المنتجات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <!-- Toolbar -->
        <div class="toolbar">
            <div class="results-count">
                عرض <strong>{{ $products->count() }}</strong> من <strong>{{ $products->total() }}</strong> منتج
            </div>
            <div class="toolbar-right">
                <!-- زر الفلاتر -->
                <button class="btn-filter" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas">
                    <i class="bi bi-funnel"></i>
                    <span>فلاتر</span>
                </button>

                <!-- Sort -->
                <form method="GET" action="{{ route('products') }}" class="d-inline flex-grow-1">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: منخفض → مرتفع</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: مرتفع → منخفض</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم (أ-ي)</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->main_image)
                                    @if(str_starts_with($product->main_image, 'http'))
                                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}">
                                    @endif
                                @else
                                    <img src="https://via.placeholder.com/400x400/FFF8E7/D4A017?text=عسل+طبيعي" alt="{{ $product->name }}">
                                @endif
                                
                                {{-- Product Badges - متزامن مع الصفحة الرئيسية --}}
                                @if($product->is_featured)
                                    <span class="product-badge badge-featured">
                                        <i class="bi bi-star-fill"></i>
                                        مميز
                                    </span>
                                @elseif($product->is_best_seller)
                                    <span class="product-badge badge-bestseller">
                                        <i class="bi bi-trophy-fill"></i>
                                        الأكثر مبيعاً
                                    </span>
                                @elseif($product->is_new)
                                    <span class="product-badge badge-new">
                                        <i class="bi bi-lightning-fill"></i>
                                        جديد
                                    </span>
                                @elseif($product->compare_price && $product->compare_price > $product->price)
                                    <span class="product-badge badge-sale">
                                        <i class="bi bi-percent"></i>
                                        تخفيض
                                    </span>
                                @endif
                            </div>
                            <div class="product-content">
                                <div class="product-category">
                                    {{ $product->category->name ?? 'عام' }}
                                </div>
                                <h3 class="product-title">{{ $product->name }}</h3>
                                <div class="product-price">{{ number_format($product->price, 2) }} ج.م</div>
                                @if($product->variants && $product->variants->count() > 0)
                                    <button class="btn-add-cart" onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('products.show', $product) }}';">
                                        <i class="bi bi-list-check"></i>
                                        <span>اختر الخيارات</span>
                                    </button>
                                @else
                                    <button class="btn-add-cart" onclick="event.preventDefault(); addToCart({{ $product->id }}, 1);">
                                        <i class="bi bi-cart-plus"></i>
                                        <span>أضف للسلة</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h3>لم نجد نتائج</h3>
                <p>
                    @if(request('search'))
                        لا توجد منتجات تطابق "{{ request('search') }}"
                    @elseif(request('category'))
                        هذه الفئة فارغة حالياً
                    @else
                        لم نجد أي منتجات تطابق معايير البحث
                    @endif
                </p>
                <a href="{{ route('products') }}" class="btn-add-cart" style="max-width: 250px; margin: 0 auto; display: inline-flex;">
                    <i class="bi bi-grid me-2"></i>
                    عرض جميع المنتجات
                </a>
                
                @if($categories && $categories->count() > 0)
                    <div class="suggestions">
                        @foreach($categories->take(4) as $cat)
                            <a href="{{ route('products', ['category' => $cat->id]) }}" class="suggestion-tag">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Offcanvas للفلاتر -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filtersOffcanvas" aria-labelledby="filtersOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="filtersOffcanvasLabel">
            <i class="bi bi-funnel-fill"></i>
            الفلاتر
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Search -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="bi bi-search"></i>
                بحث
            </h3>
            <form method="GET" action="{{ route('products') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <!-- Categories -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="bi bi-grid"></i>
                الفئات
            </h3>
            <div class="category-list">
                <a href="{{ route('products', ['search' => request('search'), 'sort' => request('sort')]) }}" 
                   class="category-item {{ !request('category') ? 'active' : '' }}">
                    <span>جميع المنتجات</span>
                    <span class="category-count">{{ $categories->sum('products_count') }}</span>
                </a>
                @foreach($categories as $category)
                    @php
                        if ($category->image) {
                            if (str_starts_with($category->image, 'http')) {
                                $categoryImage = $category->image;
                            } elseif (str_starts_with($category->image, 'storage/')) {
                                $categoryImage = asset($category->image);
                            } else {
                                $categoryImage = asset('storage/' . $category->image);
                            }
                        } else {
                            $categoryImage = 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=100&h=100&fit=crop';
                        }
                    @endphp
                    <a href="{{ route('products', ['category' => $category->id, 'search' => request('search'), 'sort' => request('sort')]) }}" 
                       class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                        <div class="category-item-content">
                            <img src="{{ $categoryImage }}" alt="{{ $category->name }}" class="category-item-img">
                            <span>{{ $category->name }}</span>
                        </div>
                        <span class="category-count">{{ $category->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="bi bi-sliders"></i>
                فلاتر سريعة
            </h3>
            <div class="quick-filters">
                <label class="quick-filter">
                    <input type="checkbox" name="in_stock" {{ request('in_stock') ? 'checked' : '' }}>
                    <span>متوفر في المخزون</span>
                </label>
                <label class="quick-filter">
                    <input type="checkbox" name="featured" {{ request('featured') ? 'checked' : '' }}>
                    <span>منتجات مميزة</span>
                </label>
                <label class="quick-filter">
                    <input type="checkbox" name="on_sale" {{ request('on_sale') ? 'checked' : '' }}>
                    <span>عروض خاصة</span>
                </label>
            </div>
        </div>
    </div>
</div>
@endsection
