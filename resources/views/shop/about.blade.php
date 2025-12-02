@extends('shop.layout')

@section('title', 'من نحن')
@section('description', 'تعرف على متجر رحيق - متخصصون في بيع أجود أنواع العسل الطبيعي')

@push('styles')
<style>
    .about-page {
        padding-top: 100px;
        padding-bottom: 4rem;
    }

    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, #FFF9E6 0%, #FFF5D6 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(212, 160, 23, 0.1) 0%, transparent 70%);
    }

    .about-hero h1 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 1rem;
        position: relative;
    }

    .about-hero p {
        font-size: 1.1rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }

    .hero-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #D4A017 0%, #FFD700 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 25px rgba(212, 160, 23, 0.3);
    }

    .hero-icon i {
        font-size: 2rem;
        color: white;
    }

    /* Story Section */
    .story-section {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 4rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .story-section h2 {
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        color: #333;
        text-align: center;
    }

    .story-section p {
        color: #666;
        line-height: 2;
        margin-bottom: 1rem;
        text-align: center;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Values Section */
    .values-section {
        background: #F9F9F9;
        border-radius: 20px;
        padding: 3rem;
        margin-bottom: 4rem;
    }

    .values-section h2 {
        text-align: center;
        margin-bottom: 2rem;
        color: #333;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .value-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }

    .value-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #FFF9E6 0%, #FFF5D6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 2px solid rgba(212, 160, 23, 0.3);
    }

    .value-icon i {
        font-size: 1.5rem;
        color: #D4A017;
    }

    .value-card h3 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .value-card p {
        color: #666;
        font-size: 0.9rem;
    }

    /* Stats Section */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 4rem;
    }

    .stat-item {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 2px solid rgba(212, 160, 23, 0.1);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #D4A017;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        color: white;
    }

    .cta-section h2 {
        color: white;
        margin-bottom: 1rem;
    }

    .cta-section p {
        opacity: 0.8;
        margin-bottom: 1.5rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-cta {
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-cta-primary {
        background: #D4A017;
        color: white;
    }

    .btn-cta-primary:hover {
        background: #B8860B;
        color: white;
        transform: translateY(-2px);
    }

    .btn-cta-secondary {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .btn-cta-secondary:hover {
        border-color: white;
        color: white;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .values-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .about-hero {
            padding: 2rem 1rem;
        }

        .about-hero h1 {
            font-size: 1.75rem;
        }

        .values-grid {
            grid-template-columns: 1fr;
        }

        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-number {
            font-size: 2rem;
        }

        .cta-section {
            padding: 2rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="about-page">
    <div class="container">
        <!-- Hero Section -->
        <div class="about-hero">
            <div class="hero-icon">
                <i class="bi bi-hexagon-fill"></i>
            </div>
            <h1>متجر رحيق</h1>
            <p>نقدم لكم أجود أنواع العسل الطبيعي من أفضل المناحل، بجودة عالية وأسعار منافسة</p>
        </div>

        <!-- Story Section -->
        <div class="story-section">
            <h2>قصتنا</h2>
            <p>
                بدأت رحلتنا منذ سنوات بهدف واحد: توفير أجود أنواع العسل الطبيعي للجميع. نؤمن بأن العسل ليس مجرد منتج غذائي، بل هو كنز من كنوز الطبيعة يحمل فوائد صحية لا تُحصى.
            </p>
            <p>
                نتعامل مباشرة مع أفضل المناحل ونختار بعناية كل منتج نقدمه لكم، لنضمن حصولكم على عسل طبيعي 100% بدون أي إضافات.
            </p>
            <p>
                نفخر بثقة عملائنا الذين يعودون إلينا مراراً وتكراراً، ونسعى دائماً لتقديم أفضل تجربة تسوق ممكنة.
            </p>
        </div>

        <!-- Values Section -->
        <div class="values-section">
            <h2>قيمنا</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3>الجودة أولاً</h3>
                    <p>نختار منتجاتنا بعناية فائقة لنضمن حصولكم على أفضل جودة</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3>منتج أصلي 100%</h3>
                    <p>نضمن أن جميع منتجاتنا طبيعية وخالية من أي إضافات</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>توصيل سريع</h3>
                    <p>نوصل طلباتكم بأسرع وقت ممكن لجميع المناطق</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h3>خدمة عملاء متميزة</h3>
                    <p>فريقنا جاهز لخدمتكم والإجابة على استفساراتكم</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h3>استرجاع مجاني</h3>
                    <p>نوفر سياسة استرجاع سهلة ومريحة لراحتكم</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h3>رضا العميل</h3>
                    <p>رضاكم هو هدفنا الأول والأخير</p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-item">
                <div class="stat-number">5000+</div>
                <div class="stat-label">عميل سعيد</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">منتج طبيعي</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-label">سنوات خبرة</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">نسبة الرضا</div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2>ابدأ رحلتك مع رحيق</h2>
            <p>اكتشف مجموعتنا المميزة من أجود أنواع العسل الطبيعي</p>
            <div class="cta-buttons">
                <a href="{{ route('products') }}" class="btn-cta btn-cta-primary">
                    <i class="bi bi-grid me-2"></i>
                    تصفح المنتجات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
