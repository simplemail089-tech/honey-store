@extends('shop.layout')

@section('title', 'المفضلة - رحيق')

@section('content')
<div class="container my-5">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">المفضلة</li>
            </ol>
        </nav>
        <h1 class="page-title">
            <i class="bi bi-heart-fill" style="color: #FF6B6B;"></i>
            قائمة المفضلة
        </h1>
    </div>

    @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="wishlist-product-card">
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                            <div class="product-image-wrapper">
                                @if($product->main_image)
                                    @if(str_starts_with($product->main_image, 'http'))
                                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="product-img">
                                    @else
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-img">
                                    @endif
                                @else
                                    <img src="https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=500&h=500&fit=crop" alt="{{ $product->name }}" class="product-img">
                                @endif
                                
                                <!-- Remove Button -->
                                <button class="remove-btn" onclick="event.preventDefault(); removeFromWishlist({{ $product->id }}, this.closest('.col-lg-3'));" title="إزالة من المفضلة">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                
                                <!-- Badges -->
                                @if($product->is_featured)
                                    <span class="wishlist-badge badge-featured">مميز</span>
                                @endif
                                
                                @if($product->stock > 0 && $product->stock <= 5)
                                    <span class="wishlist-badge badge-stock" style="top: 60px;">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        متبقي {{ $product->stock }} فقط!
                                    </span>
                                @endif
                            </div>
                        </a>
                        
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name }}</div>
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                <h3 class="product-name">{{ $product->name }}</h3>
                            </a>
                            <div class="product-price">{{ number_format($product->price, 2) }} ج.م</div>
                            
                            @if($product->stock > 0)
                                <button class="btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                                    <i class="bi bi-cart-plus"></i>
                                    أضف للسلة
                                </button>
                            @else
                                <button class="btn-add-to-cart" disabled>
                                    <i class="bi bi-x-circle"></i>
                                    غير متوفر
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty Wishlist -->
        <div class="empty-wishlist text-center py-5">
            <div class="empty-icon">
                <i class="bi bi-heart"></i>
            </div>
            <h3 class="empty-title">قائمة المفضلة فارغة</h3>
            <p class="empty-text">لم تقم بإضافة أي منتجات إلى المفضلة بعد</p>
            <a href="{{ route('products') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-search me-2"></i>
                تصفح المنتجات
            </a>
        </div>
    @endif
</div>

<style>
    .page-header {
        text-align: center;
    }

    .breadcrumb {
        justify-content: center;
        background: none;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-item a {
        color: #D4A017;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #666;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #000;
        margin: 0;
        font-family: 'Amiri', serif;
    }

    .wishlist-product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .wishlist-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .product-image-wrapper {
        position: relative;
        aspect-ratio: 1;
        background: #fafafa;
        overflow: hidden;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }

    .wishlist-product-card:hover .product-img {
        transform: scale(1.05);
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 35px;
        height: 35px;
        background: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 3;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .remove-btn:hover {
        background: #FF6B6B;
        color: white;
        transform: scale(1.1);
    }

    .remove-btn i {
        font-size: 0.9rem;
    }

    .wishlist-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: linear-gradient(135deg, #FFD700 0%, #D4A017 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        z-index: 2;
        box-shadow: 0 3px 10px rgba(212, 160, 23, 0.5);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-stock {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        animation: pulse-slow 3s ease-in-out infinite;
    }

    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .product-info {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        color: #D4A017;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        min-height: 45px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #D4A017;
        margin-bottom: 1rem;
    }

    .btn-add-to-cart {
        background: #D4A017;
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: auto;
    }

    .btn-add-to-cart:hover:not(:disabled) {
        background: #B8860B;
        transform: translateY(-2px);
    }

    .btn-add-to-cart:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    /* Empty Wishlist */
    .empty-wishlist {
        max-width: 500px;
        margin: 4rem auto;
    }

    .empty-icon {
        font-size: 6rem;
        color: #ddd;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 1rem;
        font-family: 'Cairo', sans-serif;
    }

    .empty-text {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    .btn-primary {
        background: #D4A017;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #B8860B;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 576px) {
        .page-title {
            font-size: 1.75rem;
        }
        
        .product-name {
            min-height: 40px;
            font-size: 0.95rem;
        }
    }
</style>

<script>
    async function removeFromWishlist(productId, cardElement) {
        try {
            const response = await fetch('{{ route("wishlist.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Fade out and remove
                cardElement.style.opacity = '0';
                cardElement.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    cardElement.remove();
                    
                    // Check if wishlist is empty
                    const remainingProducts = document.querySelectorAll('.wishlist-product-card').length;
                    if (remainingProducts === 0) {
                        location.reload();
                    }
                }, 300);
                
                showToast('تمت إزالة المنتج من المفضلة', 'success');
                updateWishlistCount();
            }
        } catch (error) {
            console.error('Error removing from wishlist:', error);
            showToast('حدث خطأ أثناء إزالة المنتج', 'error');
        }
    }
</script>
@endsection
