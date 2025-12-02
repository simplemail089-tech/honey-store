@extends('shop.layout')

@section('title', 'رحيق الطبيعي - الصفحة الرئيسية')
@section('description', 'اكتشف أجود أنواع العسل الطبيعي من مصادر موثوقة')

@push('styles')
<style>
    /* ===== Hero Section احترافي ===== */
    .hero-modern {
        position: relative;
        margin-top: 72px;
        background: #000;
        overflow: hidden;
        min-height: 600px;
    }

    .hero-slide {
        position: relative;
        min-height: 600px;
    }

    /* Background Image */
    .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
    }

    .hero-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.7;
    }

    .hero-bg::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.3) 100%);
    }

    /* Content */
    .hero-content-wrapper {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        min-height: 600px;
        padding: 4rem 0;
    }

    .hero-content {
        max-width: 600px;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(212, 160, 23, 0.2);
        backdrop-filter: blur(10px);
        color: #FFD700;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(212, 160, 23, 0.3);
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        color: #FFF;
        margin-bottom: 1.5rem;
        font-family: 'Amiri', serif;
        line-height: 1.15;
        text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.5);
    }

    .hero-title .accent {
        color: #FFD700;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 2.5rem;
        line-height: 1.7;
        text-shadow: 1px 1px 10px rgba(0, 0, 0, 0.5);
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        background: #D4A017;
        color: white;
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        border: none;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(212, 160, 23, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(212, 160, 23, 0.5);
        background: #FFD700;
        color: #000;
    }

    .btn-hero-secondary {
        background: transparent;
        color: white;
        padding: 1rem 2.5rem;
        font-size: 1.05rem;
        font-weight: 600;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
    }

    .btn-hero-secondary:hover {
        border-color: white;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateY(-2px);
    }

    /* Carousel Controls - Modern */
    .carousel-control-prev,
    .carousel-control-next {
        width: 55px;
        height: 55px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(212, 160, 23, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(212, 160, 23, 0.3);
        border-radius: 50%;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 3;
    }

    .hero-modern:hover .carousel-control-prev,
    .hero-modern:hover .carousel-control-next {
        opacity: 1;
    }

    .carousel-control-prev {
        right: 25px;
        left: auto;
    }

    .carousel-control-next {
        left: 25px;
        right: auto;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: rgba(212, 160, 23, 0.3);
        border-color: #D4A017;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: none;
        background-image: none;
        font-size: 1.5rem;
        color: white;
    }

    .carousel-control-prev-icon::before {
        content: '→';
        font-weight: bold;
    }

    .carousel-control-next-icon::before {
        content: '←';
        font-weight: bold;
    }

    .carousel-indicators {
        bottom: 30px;
        z-index: 3;
    }

    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.5);
        margin: 0 6px;
        transition: all 0.3s ease;
    }

    .carousel-indicators .active {
        background: #FFD700;
        border-color: #FFD700;
        width: 40px;
        border-radius: 6px;
    }

    /* القسم العام */
    .section {
        padding: 4rem 0;
        background: var(--body-bg);
    }
    
    .section-alt {
        padding: 4rem 0;
        background: linear-gradient(135deg, #FFFBF0 0%, #FFF8E7 100%);
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        position: relative;
        display: inline-block;
        font-family: 'Amiri', serif;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        right: 0;
        width: 80px;
        height: 3px;
        background: var(--primary-gold);
        border-radius: 2px;
    }

    .section-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 400;
        margin-bottom: 2.5rem;
    }

    /* بطاقات الفئات */
    .category-card {
        position: relative;
        border-radius: 20px;
        padding: 0;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 220px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.5) 100%);
        border-radius: 20px;
        transition: all 0.3s ease;
        z-index: 1;
    }
    
    .category-card:hover::before {
        background: linear-gradient(135deg, rgba(212,160,23,0.7) 0%, rgba(212,160,23,0.5) 100%);
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .category-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 2rem;
    }

    .category-icon {
        font-size: 3rem;
        color: white;
        margin-bottom: 1rem;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-icon {
        transform: scale(1.1);
    }

    .category-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.4rem;
        font-family: 'Amiri', serif;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
    }

    .category-count {
        color: rgba(255,255,255,0.9);
        font-size: 0.9rem;
        font-weight: 500;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
    }

    /* كروت المنتجات */
    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #f0f0f0;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        background: #fafafa;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Quick View Button */
    .quick-view-btn {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%) translateY(50px);
        background: white;
        color: #000;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        z-index: 4;
        transition: all 0.3s ease;
        opacity: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        white-space: nowrap;
    }

    .product-card:hover .quick-view-btn {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .quick-view-btn:hover {
        background: #D4A017;
        color: white;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.08);
    }

    /* Wishlist Heart Button */
    .wishlist-btn {
        position: absolute;
        top: 15px;
        left: 15px;
        width: 40px;
        height: 40px;
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

    .wishlist-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .wishlist-btn i {
        font-size: 1.2rem;
        color: #666;
        transition: all 0.3s ease;
    }

    .wishlist-btn.active i {
        color: #FF6B6B;
    }

    .wishlist-btn:hover i {
        color: #FF6B6B;
    }

    /* Product Badges - Modern & Attention-Grabbing */
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
        display: flex;
        align-items: center;
        gap: 0.25rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        animation: badge-pop 0.3s ease;
    }

    @keyframes badge-pop {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Badge Colors - Distinct & Eye-Catching */
    .badge-featured {
        background: linear-gradient(135deg, #FFD700 0%, #D4A017 100%);
        box-shadow: 0 3px 10px rgba(212, 160, 23, 0.5);
    }

    .badge-new {
        background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%);
        box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4);
    }

    .badge-sale {
        background: linear-gradient(135deg, #F7971E 0%, #FFD200 100%);
        box-shadow: 0 4px 12px rgba(247, 151, 30, 0.4);
        color: #000;
    }

    .badge-bestseller {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .badge-limited {
        background: linear-gradient(135deg, #FF6B9D 0%, #FFC371 100%);
        animation: pulse 2s ease-in-out infinite;
    }

    .badge-stock {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        animation: pulse-slow 3s ease-in-out infinite;
    }

    @keyframes pulse-slow {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 4px 18px rgba(240, 147, 251, 0.6);
        }
    }

    .product-content {
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .product-category {
        color: var(--primary-gold);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        min-height: 50px;
        font-family: 'Cairo', sans-serif;
        line-height: 1.4;
    }

    .product-price {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--primary-gold);
        margin-bottom: 1.5rem;
        font-family: 'Cairo', sans-serif;
    }

    .btn-add-cart {
        background: #D4A017;
        color: white;
        width: 100%;
        padding: 0.75rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-add-cart:hover {
        background: #B8860B;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
        color: white;
    }

    /* قسم المميزات */
    .features-section {
        background: #F8F9FA;
        padding: 2rem 0;
    }

    .feature-box {
        text-align: center;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }

    .feature-box:hover {
        transform: translateY(-5px);
    }

    .feature-icon {
        font-size: 2.5rem;
        color: #D4A017;
        margin-bottom: 1rem;
    }

    .feature-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 0.6rem;
        font-family: 'Cairo', sans-serif;
    }

    .feature-text {
        color: #666;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .hero-modern {
            min-height: 500px;
        }

        .hero-slide {
            min-height: 500px;
        }

        .hero-content-wrapper {
            min-height: 500px;
            padding: 3rem 0;
        }

        .hero-content {
            text-align: center;
            max-width: 100%;
        }

        .hero-title {
            font-size: clamp(2rem, 5vw, 3rem);
        }

        .hero-actions {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .hero-modern {
            margin-top: 60px;
            min-height: 400px;
        }

        .hero-slide {
            min-height: 400px;
        }

        .hero-content-wrapper {
            min-height: 400px;
            padding: 1.5rem 0;
        }

        .hero-badge {
            font-size: 0.7rem;
            padding: 0.35rem 0.85rem;
            margin-bottom: 0.75rem;
        }

        .hero-title {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .hero-subtitle {
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            line-height: 1.4;
        }

        .hero-actions {
            flex-direction: column;
            gap: 0.65rem;
        }

        .btn-hero-primary,
        .btn-hero-secondary {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 45px;
            height: 45px;
        }

        .carousel-control-prev {
            right: 15px;
        }

        .carousel-control-next {
            left: 15px;
        }

        .carousel-indicators {
            bottom: 20px;
        }

        .carousel-indicators [data-bs-target] {
            width: 8px;
            height: 8px;
            margin: 0 4px;
        }

        .carousel-indicators .active {
            width: 24px;
        }
    }

    /* Responsive للأقسام الأخرى */
    @media (max-width: 768px) {

        .section-title {
            font-size: 1.5rem;
        }
        
        .section-subtitle {
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .section {
            padding: 2.5rem 0;
        }
        
        .section-alt {
            padding: 2.5rem 0;
        }

        /* بطاقات الفئات - Mobile */
        .category-card {
            height: 200px;
            border-radius: 20px;
            border-width: 1px;
        }
        
        .category-icon {
            font-size: 2.75rem;
            margin-bottom: 0.85rem;
        }
        
        .category-card:hover .category-icon {
            transform: scale(1.15) translateY(-6px) rotate(5deg);
        }
        
        .category-title {
            font-size: 1.15rem;
        }
        
        .category-count {
            font-size: 0.9rem;
        }
        
        /* بطاقات المنتجات - Mobile */
        .product-card {
            border-radius: 20px;
        }
        
        .product-content {
            padding: 1.25rem;
        }
        
        .product-category {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }
        
        .product-title {
            font-size: 1rem;
            min-height: auto;
            margin-bottom: 0.75rem;
            line-height: 1.35;
        }
        
        .product-price {
            font-size: 1.15rem;
            margin-bottom: 1rem;
        }
        
        .product-image img {
            padding: 1rem;
        }
        
        .product-badge {
            top: 10px;
            right: 10px;
            padding: 0.3rem 0.65rem;
            font-size: 0.65rem;
            color: #D4A017;
            background-color: #F8F4E5;
        }
        
        .btn-add-cart {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        /* Features - Mobile */
        .features-section {
            padding: 1.5rem 0;
        }
        
        .feature-box {
            padding: 0.75rem 0.5rem;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 1.75rem;
            margin-bottom: 0.35rem;
        }
        
        .feature-title {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
        }
        
        .feature-text {
            font-size: 0.7rem;
            line-height: 1.3;
            display: none;
        }
        
        /* عرض في صف واحد على الموبايل */
        .features-section .row {
            margin: 0 -0.25rem;
        }
        
        .features-section .col-lg-3 {
            padding: 0 0.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Slider -->
<section class="hero-modern">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        @if($sliders->count() > 0)
            <div class="carousel-indicators">
                @foreach($sliders as $index => $slider)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" 
                            class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            
            <div class="carousel-inner">
                @foreach($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="hero-slide">
                            <!-- Background Image -->
                            @if($slider->image)
                                <div class="hero-bg">
                                    <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}">
                                </div>
                            @else
                                <div class="hero-bg">
                                    <img src="https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=1920&h=800&fit=crop&q=80" alt="عسل طبيعي">
                                </div>
                            @endif
                            
                            <!-- Content -->
                            <div class="container hero-content-wrapper">
                                <div class="hero-content">
                                    <div class="hero-badge">
                                        <i class="bi bi-award me-1"></i> منتج طبيعي 100%
                                    </div>
                                    <h1 class="hero-title">
                                        {{ $slider->title }}
                                    </h1>
                                    <p class="hero-subtitle">{{ $slider->description }}</p>
                                    <div class="hero-actions">
                                        @if($slider->link && $slider->button_text)
                                            <a href="{{ $slider->link }}" class="btn-hero-primary">
                                                <span>{{ $slider->button_text }}</span>
                                                <i class="bi bi-arrow-left"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('products') }}" class="btn-hero-secondary">
                                            <i class="bi bi-grid-3x3-gap"></i>
                                            <span>تصفح المنتجات</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        @else
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-slide">
                        <!-- Background Image -->
                        <div class="hero-bg">
                            <img src="https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=1920&h=800&fit=crop&q=80" alt="عسل طبيعي">
                        </div>
                        
                        <!-- Content -->
                        <div class="container hero-content-wrapper">
                            <div class="hero-content">
                                <div class="hero-badge">
                                    <i class="bi bi-award me-1"></i> منتج طبيعي 100%
                                </div>
                                <h1 class="hero-title">
                                    عسل طبيعي <span class="accent">100%</span><br>من قلب الطبيعة
                                </h1>
                                <p class="hero-subtitle">اكتشف أجود أنواع العسل الطبيعي من مصادر موثوقة ومناحل مختارة بعناية</p>
                                <div class="hero-actions">
                                    <a href="{{ route('products') }}" class="btn-hero-primary">
                                        <span>تسوق الآن</span>
                                        <i class="bi bi-arrow-left"></i>
                                    </a>
                                    <a href="{{ route('products') }}" class="btn-hero-secondary">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                        <span>تصفح المنتجات</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- قسم الفئات -->
<section class="section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">تصفح حسب الفئة</h2>
            <p class="section-subtitle">اختر من بين تشكيلتنا المتنوعة من منتجات العسل الطبيعي</p>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-lg-3 col-md-6 col-6">
                    <a href="{{ route('products', ['category' => $category->id]) }}" class="text-decoration-none">
                        @php
                            // استخدام صورة الفئة من الداشبورد
                            if ($category->image) {
                                // التحقق من نوع المسار
                                if (str_starts_with($category->image, 'http')) {
                                    $bgImage = $category->image;
                                } elseif (str_starts_with($category->image, 'storage/')) {
                                    $bgImage = asset($category->image);
                                } else {
                                    $bgImage = asset('storage/' . $category->image);
                                }
                            } else {
                                // صورة افتراضية فقط إذا لم يتم رفع صورة
                                $bgImage = 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=600&h=400&fit=crop';
                            }
                            
                            // الأيقونات من قاعدة البيانات أو افتراضية
                            $icon = $category->icon ?? 'droplet-fill';
                        @endphp
                        <div class="category-card" style="background-image: url('{{ $bgImage }}');">
                            <div class="category-content">
                                <h3 class="category-title">{{ $category->name }}</h3>
                                <p class="category-count">{{ $category->products_count }} منتج</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم المنتجات الأكثر مبيعاً -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">الأكثر مبيعاً</h2>
            <p class="section-subtitle">المنتجات الأكثر طلباً من عملائنا</p>
        </div>
        
        <div class="row g-4">
            @forelse($bestSellers as $product)
                <div class="col-lg-3 col-md-6 col-6">
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
                                    <img src="https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=500&h=500&fit=crop" alt="{{ $product->name }}">
                                @endif
                                
                                {{-- Wishlist Button --}}
                                <button class="wishlist-btn" onclick="event.preventDefault(); toggleWishlist({{ $product->id }}, this);" title="أضف للمفضلة">
                                    <i class="bi bi-heart"></i>
                                </button>
                                
                                {{-- Quick View Button --}}
                                <button class="quick-view-btn" onclick="event.preventDefault(); openQuickView({{ $product->id }});" title="معاينة سريعة">
                                    <i class="bi bi-eye"></i>
                                    معاينة سريعة
                                </button>
                                
                                {{-- Product Badges - متزامن مع صفحة المنتجات --}}
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
                                <div class="product-category">{{ $product->category->name }}</div>
                                <h3 class="product-title">{{ $product->name }}</h3>
                                <div class="product-price">{{ number_format($product->price, 2) }} ج.م</div>
                                @if($product->variants && $product->variants->count() > 0)
                                    <button class="btn btn-add-cart w-100" onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('products.show', $product) }}';">
                                        <i class="bi bi-list-check me-2"></i>
                                        اختر الخيارات
                                    </button>
                                @else
                                    <button class="btn btn-add-cart w-100" onclick="addToCart({{ $product->id }})">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        أضف للسلة
                                    </button>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        لا توجد منتجات متاحة حالياً
                    </div>
                </div>
            @endforelse
        </div>
        
        @if($bestSellers->count() >= 8)
            <div class="text-center mt-5">
                <a href="{{ route('products') }}" class="btn btn-hero">
                    <span>عرض جميع المنتجات</span>
                </a>
            </div>
        @endif
    </div>
</section>

<!-- قسم المميزات -->
<section class="features-section">
    <div class="container">
        <div class="row g-3 g-md-4">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="feature-title">منتجات طبيعية 100%</h3>
                    <p class="feature-text">جميع منتجاتنا طبيعية وخالية من أي إضافات</p>
                </div>
            </div>
            
            <div class="col-6 col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3 class="feature-title">توصيل سريع</h3>
                    <p class="feature-text">نوصل طلبك في أسرع وقت ممكن</p>
                </div>
            </div>
            
            <div class="col-6 col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3 class="feature-title">جودة مضمونة</h3>
                    <p class="feature-text">نضمن لك أعلى معايير الجودة</p>
                </div>
            </div>
            
            <div class="col-6 col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h3 class="feature-title">دعم مستمر</h3>
                    <p class="feature-text">فريقنا جاهز لمساعدتك دائماً</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
