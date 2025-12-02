@extends('shop.layout')

@section('title', $product->name . ' - رحيق')
@section('description', $product->description ?? 'منتج طبيعي عالي الجودة')

@push('styles')
<style>
    /* Product Page */
    .product-page {
        padding-top: 90px;
        padding-bottom: 4rem;
        background: #FAFAFA;
    }

    /* Breadcrumb - تحسين التباين */
    .breadcrumb-modern {
        background: #FAFAFA;
        padding: 1rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid #E8E8E8;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: #999;
        font-size: 0.8rem;
    }

    .breadcrumb-item a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .breadcrumb-item a:hover {
        color: #D4A017;
        background: rgba(212, 160, 23, 0.1);
    }

    .breadcrumb-item.active {
        color: #666;
        font-weight: 400;
    }

    @media (max-width: 576px) {
        .breadcrumb {
            font-size: 0.8rem;
        }
        
        .breadcrumb-item a {
            padding: 0.15rem 0.3rem;
        }
    }

    /* Product Container */
    .product-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    /* Gallery Section */
    .gallery-section {
        position: sticky;
        top: 100px;
    }

    .main-image-container {
        position: relative;
        background: #FAFAFA;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 2rem;
        cursor: zoom-in;
        transition: transform 0.3s;
    }

    .main-image:hover {
        transform: scale(1.05);
    }

    .badge-featured {
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

    /* Thumbnails */
    .thumbnails-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    .thumbnail {
        aspect-ratio: 1;
        background: #FAFAFA;
        border: 2px solid #E8E8E8;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
    }

    .thumbnail:hover {
        border-color: #D4A017;
    }

    .thumbnail.active {
        border-color: #D4A017;
        box-shadow: 0 0 0 2px rgba(212, 160, 23, 0.2);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.5rem;
    }

    /* Product Info */
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(212, 160, 23, 0.1);
        color: #D4A017;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        width: fit-content;
    }

    .product-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #000;
        line-height: 1.3;
        margin: 0;
    }

    .product-price-box {
        background: rgba(212, 160, 23, 0.05);
        padding: 1.2rem;
        border-radius: 10px;
        border: 2px dashed #D4A017;
    }

    .price-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .price-value {
        font-size: 2rem;
        font-weight: 700;
        color: #D4A017;
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }
    
    .price-value span {
        transition: all 0.2s ease;
        display: inline-block;
    }

    .price-value .currency {
        font-size: 1.2rem;
        font-weight: 600;
        color: #999;
    }

    /* Stock Status */
    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .stock-status.in-stock {
        background: #d4edda;
        color: #155724;
    }

    .stock-status.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    /* Quick Info */
    .quick-info {
        background: #F9F9F9;
        border-radius: 10px;
        padding: 1.2rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid #EFEFEF;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-size: 0.9rem;
    }

    .info-value {
        color: #000;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Quantity Selector */
    .quantity-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .quantity-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #F9F9F9;
        border-radius: 10px;
        padding: 0.3rem;
        width: fit-content;
    }

    .qty-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: white;
        border-radius: 8px;
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qty-btn:hover {
        background: #D4A017;
        color: white;
    }

    .qty-input {
        width: 60px;
        height: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 1.1rem;
        font-weight: 700;
        color: #000;
    }

    /* Variants Section */
    .variants-section {
        margin-top: 1.5rem;
    }

    .variant-label {
        display: block;
        font-weight: 700;
        font-size: 1rem;
        color: #000;
        margin-bottom: 1rem;
    }

    .variants-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
    }

    .variant-option {
        position: relative;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .variant-option:hover:not(.out-of-stock) {
        border-color: #D4A017;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 160, 23, 0.2);
    }

    .variant-option.selected {
        border-color: #D4A017;
        background: linear-gradient(135deg, #FFF8E7 0%, #FFFBF0 100%);
        box-shadow: 0 4px 12px rgba(212, 160, 23, 0.25);
    }

    .variant-option.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f5f5f5;
    }

    .variant-size {
        font-weight: 700;
        font-size: 1.1rem;
        color: #000;
        margin-bottom: 0.5rem;
    }

    .variant-price {
        color: #D4A017;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .variant-stock-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .variant-stock-badge.warning {
        background: #ffc107;
        color: #000;
    }

    /* Add to Cart Button */
    .btn-add-to-cart {
        background: #D4A017;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-add-to-cart:hover {
        background: #B8860B;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 160, 23, 0.3);
    }

    .btn-add-to-cart i {
        font-size: 1.3rem;
    }

    /* Trust Badges - Grid 2x2 */
    .trust-badges {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding: 1rem;
        background: linear-gradient(135deg, #FFF9E6 0%, #FFF5D6 100%);
        border-radius: 12px;
        border: 2px solid rgba(212, 160, 23, 0.2);
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .trust-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(212, 160, 23, 0.2);
    }

    .trust-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #D4A017 0%, #FFD700 100%);
        border-radius: 50%;
        font-size: 1rem;
        color: white;
        box-shadow: 0 3px 10px rgba(212, 160, 23, 0.3);
    }

    .trust-text {
        font-size: 0.8rem;
        color: #333;
        font-weight: 600;
        line-height: 1.3;
    }

    /* Sticky Add to Cart للموبايل - مخفي لتجنب التداخل */
    .sticky-add-cart {
        display: none;
    }

    @media (max-width: 768px) {
        /* مساحة إضافية أسفل الصفحة */
        .product-page {
            padding-bottom: 80px;
        }
        
        /* Trust Badges على الموبايل */
        .trust-badges {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            padding: 0.75rem;
        }
        
        .trust-item {
            padding: 0.6rem;
            gap: 0.5rem;
        }
        
        .trust-icon {
            width: 35px;
            height: 35px;
            min-width: 35px;
            font-size: 0.9rem;
        }
        
        .trust-text {
            font-size: 0.7rem;
        }
    }

    /* Tabs */
    .product-tabs {
        margin-top: 3rem;
    }

    .nav-tabs {
        border-bottom: 2px solid #E8E8E8;
    }

    .nav-tabs .nav-link {
        color: #333;
        background: #f8f8f8;
        border: 2px solid #e0e0e0;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        margin-left: 0.5rem;
        border-radius: 8px 8px 0 0;
        transition: all 0.3s;
        position: relative;
    }

    .nav-tabs .nav-link:hover {
        color: #D4A017;
        background: #fff;
        border-color: #D4A017;
    }

    .nav-tabs .nav-link.active {
        color: #fff !important;
        background: #D4A017 !important;
        border-color: #D4A017 !important;
        box-shadow: 0 -2px 8px rgba(212, 160, 23, 0.3);
    }
    
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #D4A017;
    }

    .tab-content {
        padding: 2rem 0;
    }

    .tab-pane {
        color: #666;
        line-height: 1.8;
    }

    .tab-pane h5 {
        color: #000;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .tab-pane ul {
        padding-right: 1.5rem;
    }

    .tab-pane li {
        margin-bottom: 0.5rem;
    }

    /* Related Products */
    .related-section {
        margin-top: 4rem;
    }

    /* Image Zoom */
    .main-image {
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }

    .image-zoom-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        cursor: zoom-out;
    }

    .image-zoom-overlay.active {
        display: flex;
    }

    .image-zoom-overlay img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .zoom-close {
        position: absolute;
        top: 20px;
        left: 20px;
        background: white;
        color: #000;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .zoom-close:hover {
        background: #D4A017;
        color: white;
    }

    /* Reviews Section */
    .reviews-summary {
        background: #F9F9F9;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 3rem;
        align-items: center;
    }

    .rating-overview {
        text-align: center;
        flex-shrink: 0;
    }

    .rating-number {
        font-size: 3rem;
        font-weight: 700;
        color: #000;
        line-height: 1;
    }

    .rating-stars {
        font-size: 1.2rem;
        color: #FFB800;
        margin: 0.5rem 0;
    }

    .rating-count {
        color: #666;
        font-size: 0.9rem;
    }

    .rating-breakdown {
        flex: 1;
    }

    .rating-bar-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .rating-star-label {
        font-size: 0.9rem;
        color: #666;
        min-width: 60px;
    }

    .rating-bar {
        flex: 1;
        height: 8px;
        background: #E0E0E0;
        border-radius: 4px;
        overflow: hidden;
    }

    .rating-bar-fill {
        height: 100%;
        background: #FFB800;
        transition: width 0.3s ease;
    }

    .rating-bar-count {
        min-width: 40px;
        text-align: left;
        font-size: 0.85rem;
        color: #999;
    }

    /* Review Item */
    .review-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid #E8E8E8;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .reviewer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #D4A017;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .reviewer-name {
        font-weight: 600;
        color: #000;
        font-size: 0.95rem;
    }

    .review-rating {
        color: #FFB800;
        font-size: 0.9rem;
    }

    .review-date {
        color: #999;
        font-size: 0.85rem;
    }

    .review-title {
        font-weight: 600;
        color: #000;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .review-comment {
        color: #666;
        line-height: 1.6;
        font-size: 0.9rem;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    /* Review Form */
    .review-form-section {
        background: #F9F9F9;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .review-form-section h5 {
        margin-bottom: 1.5rem;
    }

    .star-rating-input {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .star-rating-input input {
        display: none;
    }

    .star-rating-input label {
        font-size: 2rem;
        color: #DDD;
        cursor: pointer;
        transition: all 0.3s;
    }

    .star-rating-input input:checked ~ label,
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label {
        color: #FFB800;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #E8E8E8;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #D4A017;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .btn-submit-review {
        background: #D4A017;
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.95rem;
    }

    .btn-submit-review:hover {
        background: #B8860B;
        transform: translateY(-2px);
    }

    .btn-submit-review:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 2rem;
        text-align: center;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .related-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        flex-direction: column;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .related-image {
        aspect-ratio: 1;
        background: #FAFAFA;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
    }

    .related-info {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .related-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 0.5rem;
        line-height: 1.3;
        flex: 1;
    }

    .related-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #D4A017;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .gallery-section {
            position: static;
            margin-bottom: 2rem;
        }

        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .product-page {
            padding-top: 80px;
        }

        .product-container {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .price-value {
            font-size: 1.75rem;
        }

        .thumbnails-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .trust-badges {
            grid-template-columns: repeat(2, 1fr);
        }

        .nav-tabs .nav-link {
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
        }

        .related-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="product-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-modern">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products') }}">المنتجات</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ route('products', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="product-container">
            <div class="row g-4">
                <!-- Gallery -->
                <div class="col-lg-5">
                    <div class="gallery-section">
                        @php
                            // جمع جميع الصور
                            $allImages = [];
                            
                            // إضافة الصورة الرئيسية
                            if ($product->main_image) {
                                if (str_starts_with($product->main_image, 'http')) {
                                    $allImages[] = $product->main_image;
                                } else {
                                    $allImages[] = asset('storage/' . $product->main_image);
                                }
                            }
                            
                            // إضافة الصور الإضافية
                            if (is_array($product->images) && count($product->images) > 0) {
                                foreach ($product->images as $additionalImage) {
                                    if (str_starts_with($additionalImage, 'http')) {
                                        $allImages[] = $additionalImage;
                                    } else {
                                        $allImages[] = asset('storage/' . $additionalImage);
                                    }
                                }
                            }
                            
                            // الصورة الأولى للعرض
                            $firstImage = !empty($allImages) ? $allImages[0] : asset('images/placeholder.jpg');
                        @endphp
                        
                        <!-- Main Image -->
                        <div class="main-image-container">
                            @if($product->is_featured)
                                <span class="badge-featured">
                                    <i class="bi bi-star-fill"></i> مميز
                                </span>
                            @endif
                            <img src="{{ $firstImage }}" 
                                 alt="{{ $product->name }}" 
                                 class="main-image" 
                                 id="mainImage"
                                 onclick="zoomImage(this.src)">
                        </div>

                        <!-- Thumbnails -->
                        @if(count($allImages) > 1)
                            <div class="thumbnails-grid">
                                @foreach($allImages as $index => $imageUrl)
                                    <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ $imageUrl }}', this)">
                                        <img src="{{ $imageUrl }}" alt="صورة {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-7">
                    <div class="product-info">
                        <!-- Category -->
                        @if($product->category)
                            <div>
                                <span class="category-badge">
                                    <i class="bi bi-grid"></i>
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        @endif

                        <!-- Title -->
                        <h1 class="product-title">{{ $product->name }}</h1>

                        <!-- Price -->
                        <div class="product-price-box">
                            <div class="price-label">السعر</div>
                            <div class="price-value">
                                <span>{{ number_format($product->price, 2) }}</span>
                                <span class="currency">ج.م</span>
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div>
                            @if($product->stock > 0)
                                <span class="stock-status in-stock">
                                    <i class="bi bi-check-circle-fill"></i>
                                    متوفر في المخزون
                                </span>
                            @else
                                <span class="stock-status out-of-stock">
                                    <i class="bi bi-x-circle-fill"></i>
                                    غير متوفر حالياً
                                </span>
                            @endif
                        </div>

                        <!-- Quick Info -->
                        <div class="quick-info">
                            @if($product->category)
                                <div class="info-item">
                                    <span class="info-label">الفئة</span>
                                    <span class="info-value">{{ $product->category->name }}</span>
                                </div>
                            @endif
                            @if($product->stock !== null)
                                <div class="info-item">
                                    <span class="info-label">المخزون</span>
                                    <span class="info-value">{{ $product->stock }} قطعة</span>
                                </div>
                            @endif
                            <div class="info-item">
                                <span class="info-label">رمز المنتج</span>
                                <span class="info-value">#{{ $product->id }}</span>
                            </div>
                        </div>

                        <!-- Variants (Sizes) Selection -->
                        @if($product->variants->count() > 0)
                            <div class="variants-section mb-4">
                                <label class="variant-label">اختر الحجم <span class="text-danger">*</span></label>
                                <div class="variants-grid">
                                    @foreach($product->variants as $variant)
                                        <div class="variant-option {{ $variant->is_default ? 'selected' : '' }} {{ $variant->stock == 0 ? 'out-of-stock' : '' }}" 
                                             data-variant-id="{{ $variant->id }}"
                                             data-variant-price="{{ $variant->price }}"
                                             data-variant-stock="{{ $variant->stock }}"
                                             onclick="selectVariant(this, {{ $variant->stock }})">
                                            <div class="variant-size">{{ $variant->size }}</div>
                                            <div class="variant-price">{{ number_format($variant->price, 2) }} ج.م</div>
                                            @if($variant->stock == 0)
                                                <div class="variant-stock-badge">نفذ</div>
                                            @elseif($variant->stock <= 5)
                                                <div class="variant-stock-badge warning">متبقي {{ $variant->stock }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" id="selectedVariantId" value="{{ $product->getDefaultVariant()?->id ?? $product->variants->first()->id }}">
                            </div>
                        @endif

                        <!-- Quantity & Add to Cart -->
                        @php
                            $availableStock = $product->variants->count() > 0 
                                ? ($product->getDefaultVariant()?->stock ?? $product->variants->first()->stock ?? 0)
                                : $product->stock;
                        @endphp
                        
                        @if($availableStock > 0)
                            <div class="quantity-section">
                                <label class="quantity-label">الكمية</label>
                                <div class="quantity-selector">
                                    <button class="qty-btn" onclick="decreaseQty()">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="qty-input" id="productQuantity" value="1" min="1" max="{{ $availableStock }}" readonly>
                                    <button class="qty-btn" id="increaseQtyBtn" onclick="increaseQty({{ $availableStock }})">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <button class="btn-add-to-cart" id="addToCartBtn" onclick="addProductToCart()">
                                <i class="bi bi-cart-plus"></i>
                                <span>أضف إلى السلة</span>
                            </button>
                        @else
                            <button class="btn-add-to-cart" style="background: #ccc; cursor: not-allowed;" disabled>
                                <i class="bi bi-x-circle"></i>
                                <span>غير متوفر</span>
                            </button>
                        @endif

                        <!-- Trust Badges - 2x2 Grid -->
                        <div class="trust-badges">
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="trust-text">منتج أصلي 100%</div>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="trust-text">شحن سريع</div>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </div>
                                <div class="trust-text">استرجاع مجاني</div>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="bi bi-headset"></i>
                                </div>
                                <div class="trust-text">دعم 24/7</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="product-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#description">الوصف</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#specifications">المواصفات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#reviews">التقييمات</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="description">
                        <h5 style="font-size: 1.1rem; margin-bottom: 1rem; color: #000;">وصف المنتج</h5>
                        <div class="product-description" style="line-height: 2.2; color: #333; font-size: 1rem;">
                            @php
                                $description = $product->description ?? 'عسل طبيعي 100%
مفيد للمناعة
خالي من السكر المضاف
أجود أنواع العسل الطبيعي';
                                $lines = explode("\n", trim($description));
                            @endphp
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach($lines as $line)
                                    @if(trim($line))
                                        <li style="padding: 0.6rem 0; padding-right: 1.5rem; position: relative; font-size: 1.05rem;">
                                            <i class="bi bi-check-circle-fill" style="color: var(--primary-gold); position: absolute; right: 0; top: 0.7rem; font-size: 1.1rem;"></i>
                                            <strong>{{ trim($line) }}</strong>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="specifications">
                        <h5>المواصفات</h5>
                        @if($product->specifications)
                            <div style="white-space: pre-line;">{{ $product->specifications }}</div>
                        @else
                            <ul>
                                <li><strong>الوزن:</strong> حسب العبوة المختارة</li>
                                <li><strong>المنشأ:</strong> عسل طبيعي 100%</li>
                                <li><strong>التخزين:</strong> يحفظ في مكان جاف بعيداً عن الشمس</li>
                                <li><strong>الصلاحية:</strong> سنتان من تاريخ الإنتاج</li>
                            </ul>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="reviews">
                        <!-- Reviews Summary -->
                        @if($reviewStats['total'] > 0)
                            <div class="reviews-summary">
                                <div class="rating-overview">
                                    <div class="rating-number">{{ $reviewStats['average'] }}</div>
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-{{ $i <= $reviewStats['average'] ? 'fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="rating-count">{{ $reviewStats['total'] }} تقييم</div>
                                </div>
                                
                                <div class="rating-breakdown">
                                    @foreach($reviewStats['distribution'] as $stars => $data)
                                        <div class="rating-bar-item">
                                            <div class="rating-star-label">{{ $stars }} نجمة</div>
                                            <div class="rating-bar">
                                                <div class="rating-bar-fill" style="width: {{ $data['percentage'] }}%"></div>
                                            </div>
                                            <div class="rating-bar-count">{{ $data['count'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Reviews List -->
                        @if($reviews->count() > 0)
                            <div class="reviews-list">
                                @foreach($reviews as $review)
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <div class="reviewer-avatar">
                                                    {{ mb_substr($review->customer_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="reviewer-name">
                                                        {{ $review->customer_name }}
                                                        @if($review->is_verified_purchase)
                                                            <span class="verified-badge">
                                                                <i class="bi bi-patch-check-fill"></i>
                                                                مشترٍ موثق
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="review-rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star-{{ $i <= $review->rating ? 'fill' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-date">
                                                {{ $review->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        @if($review->title)
                                            <div class="review-title">{{ $review->title }}</div>
                                        @endif
                                        <div class="review-comment">{{ $review->comment }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 2rem; color: #999;">
                                <i class="bi bi-chat-square-text" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                <p>لا توجد تقييمات بعد. كن أول من يقيم هذا المنتج!</p>
                            </div>
                        @endif

                        <!-- Review Form -->
                        <div class="review-form-section">
                            <h5>أضف تقييمك</h5>
                            <form id="reviewForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <!-- Star Rating -->
                                <div class="form-group">
                                    <label class="form-label">التقييم <span style="color: red;">*</span></label>
                                    <div class="star-rating-input">
                                        <input type="radio" name="rating" value="5" id="star5" required>
                                        <label for="star5"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" name="rating" value="4" id="star4">
                                        <label for="star4"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" name="rating" value="3" id="star3">
                                        <label for="star3"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" name="rating" value="2" id="star2">
                                        <label for="star2"><i class="bi bi-star-fill"></i></label>
                                        
                                        <input type="radio" name="rating" value="1" id="star1">
                                        <label for="star1"><i class="bi bi-star-fill"></i></label>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="form-group">
                                    <label class="form-label" for="customer_name">الاسم <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label class="form-label" for="customer_email">البريد الإلكتروني (اختياري)</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email">
                                </div>

                                <!-- Title -->
                                <div class="form-group">
                                    <label class="form-label" for="review_title">عنوان التقييم (اختياري)</label>
                                    <input type="text" class="form-control" id="review_title" name="title" placeholder="مثال: منتج رائع!">
                                </div>

                                <!-- Comment -->
                                <div class="form-group">
                                    <label class="form-label" for="review_comment">التعليق <span style="color: red;">*</span></label>
                                    <textarea class="form-control" id="review_comment" name="comment" required placeholder="شاركنا رأيك في المنتج..."></textarea>
                                </div>

                                <button type="submit" class="btn-submit-review" id="submitReviewBtn">
                                    <i class="bi bi-send-fill"></i>
                                    إرسال التقييم
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts && $relatedProducts->count() > 0)
            <div class="related-section">
                <h2 class="section-title">منتجات ذات صلة</h2>
                <div class="related-grid">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('products.show', $related) }}" class="related-card">
                            <div class="related-image">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                            </div>
                            <div class="related-info">
                                <div class="related-title">{{ $related->name }}</div>
                                <div class="related-price">{{ number_format($related->price, 2) }} ج.م</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<!-- Image Zoom Overlay -->
<div class="image-zoom-overlay" id="imageZoomOverlay" onclick="closeZoom()">
    <button class="zoom-close" onclick="closeZoom()">
        <i class="bi bi-x"></i>
    </button>
    <img src="" alt="Zoomed Image" id="zoomedImage">
</div>

<script>
    const productId = {{ $product->id }};

    // Change main image
    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    }

    // Quantity controls
    function increaseQty(max) {
        const input = document.getElementById('productQuantity');
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decreaseQty() {
        const input = document.getElementById('productQuantity');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }

    // Select Variant
    function selectVariant(element, stock) {
        // Don't select if out of stock
        if (element.classList.contains('out-of-stock')) {
            showToast('هذا الحجم غير متوفر', 'error');
            return;
        }
        
        // Remove selected from all
        document.querySelectorAll('.variant-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Add selected to clicked
        element.classList.add('selected');
        
        // Update hidden input
        const variantId = element.dataset.variantId;
        const variantPrice = parseFloat(element.dataset.variantPrice);
        document.getElementById('selectedVariantId').value = variantId;
        
        // تحديث السعر المعروض فورًا
        const priceDisplay = document.querySelector('.product-price-box .price-value span');
        if (priceDisplay && !isNaN(variantPrice)) {
            // إضافة animation للسعر
            priceDisplay.style.transform = 'scale(1.1)';
            priceDisplay.style.color = 'var(--primary-gold)';
            
            setTimeout(() => {
                priceDisplay.textContent = variantPrice.toFixed(2);
                setTimeout(() => {
                    priceDisplay.style.transform = 'scale(1)';
                    priceDisplay.style.color = '';
                }, 150);
            }, 100);
        }
        
        // Update quantity max and reset to 1
        const qtyInput = document.getElementById('productQuantity');
        qtyInput.max = stock;
        qtyInput.value = 1;
        
        // Update increase button onclick
        const increaseBtn = document.getElementById('increaseQtyBtn');
        if (increaseBtn) {
            increaseBtn.setAttribute('onclick', `increaseQty(${stock})`);
        }
    }

    // Add to cart
    async function addProductToCart() {
        const quantity = parseInt(document.getElementById('productQuantity').value);
        const variantId = document.getElementById('selectedVariantId')?.value;
        
        // If product has variants, pass variant_id
        if (variantId) {
            await addToCart(productId, quantity, variantId);
        } else {
            await addToCart(productId, quantity);
        }
    }

    // ==========================================
    // Image Zoom Functionality
    // ==========================================
    function zoomImage(src) {
        const overlay = document.getElementById('imageZoomOverlay');
        const zoomedImg = document.getElementById('zoomedImage');
        zoomedImg.src = src;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeZoom() {
        const overlay = document.getElementById('imageZoomOverlay');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeZoom();
        }
    });

    // ==========================================
    // Review Form Submission
    // ==========================================
    document.getElementById('reviewForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('submitReviewBtn');
        const originalHTML = btn.innerHTML;
        
        try {
            // Loading state
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> جاري الإرسال...';
            
            const formData = new FormData(this);
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const response = await fetch('{{ route("reviews.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData  // إرسال FormData مباشرة بدلاً من JSON
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast(result.message || 'تم إضافة تقييمك بنجاح! سيظهر بعد المراجعة.', 'success');
                this.reset();
                
                // Success state
                btn.innerHTML = '<i class="bi bi-check-lg"></i> تم الإرسال بنجاح!';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }, 3000);
            } else {
                // عرض رسائل الخطأ من الـ validation
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('\n');
                    showToast(errorMessages, 'error');
                } else {
                    showToast(result.message || 'حدث خطأ أثناء إرسال التقييم', 'error');
                }
                throw new Error(result.message || 'حدث خطأ');
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    });
</script>
@endpush
