@extends('shop.layout')

@section('title', 'تم استلام طلبك')

@section('content')
<!-- Progress Bar -->
<div class="progress-bar-container">
    <div class="container">
        <div class="checkout-progress">
            <div class="progress-step completed">
                <div class="step-circle">
                    <i class="bi bi-check"></i>
                </div>
                <div class="step-label">السلة</div>
                <div class="progress-line"></div>
            </div>
            <div class="progress-step completed">
                <div class="step-circle">
                    <i class="bi bi-check"></i>
                </div>
                <div class="step-label">معلومات التوصيل</div>
                <div class="progress-line"></div>
            </div>
            <div class="progress-step completed">
                <div class="step-circle">
                    <i class="bi bi-check"></i>
                </div>
                <div class="step-label">تم بنجاح</div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-bottom: 4rem;">
    <div class="success-container">
        <!-- Success Animation -->
        <div class="success-animation">
            <div class="success-checkmark">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <div class="success-message">
            <h1>تم استلام طلبك بنجاح!</h1>
            <p class="lead">شكراً لك على ثقتك بنا</p>
            <p class="order-number">
                رقم الطلب: <strong>{{ $order->order_number }}</strong>
            </p>
        </div>

        <!-- Order Details -->
        <div class="order-details-card">
            <h3 class="details-title">
                <i class="bi bi-receipt me-2"></i>
                تفاصيل الطلب
            </h3>
            
            <div class="row g-4">
                <!-- معلومات العميل -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i class="bi bi-person-fill"></i> معلومات العميل</h5>
                        <ul class="info-list">
                            <li><strong>الاسم:</strong> {{ $order->customer_name }}</li>
                            <li><strong>الهاتف:</strong> {{ $order->customer_phone }}</li>
                            @if($order->customer_email)
                                <li><strong>البريد:</strong> {{ $order->customer_email }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <!-- عنوان التوصيل -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i class="bi bi-geo-alt-fill"></i> عنوان التوصيل</h5>
                        <ul class="info-list">
                            <li>{{ $order->shipping_address_line1 }}</li>
                            @if($order->shipping_address_line2)
                                <li>{{ $order->shipping_address_line2 }}</li>
                            @endif
                            <li>{{ $order->shipping_city }}, {{ $order->shipping_country }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- المنتجات المطلوبة -->
            <div class="products-section mt-4">
                <h5><i class="bi bi-bag-check-fill"></i> المنتجات المطلوبة</h5>
                <div class="products-list">
                    @foreach($order->items as $item)
                        <div class="product-row">
                            <div class="product-info">
                                <span class="product-name">{{ $item->snapshot['name'] ?? $item->product->name }}</span>
                                <span class="product-qty">الكمية: {{ $item->quantity }}</span>
                            </div>
                            <span class="product-total">{{ number_format($item->total_price, 2) }} ج.م</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- الفاتورة -->
            <div class="invoice-section mt-4">
                <div class="invoice-row">
                    <span>المجموع الفرعي:</span>
                    <strong>{{ number_format($order->subtotal, 2) }} ج.م</strong>
                </div>
                <div class="invoice-row">
                    <span>الشحن:</span>
                    <strong>{{ number_format($order->shipping_total, 2) }} ج.م</strong>
                </div>
                <hr>
                <div class="invoice-row invoice-total">
                    <span>المجموع الكلي:</span>
                    <strong>{{ number_format($order->total, 2) }} ج.م</strong>
                </div>
                <div class="payment-badge">
                    <i class="bi bi-cash-coin"></i>
                    الدفع عند الاستلام
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="next-steps">
            <h4>ماذا يحدث الآن؟</h4>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5>تأكيد الطلب</h5>
                    <p>تم استلام طلبك وجاري المراجعة</p>
                </div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5>التحضير</h5>
                    <p>سنقوم بتجهيز طلبك بعناية</p>
                </div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h5>الشحن</h5>
                    <p>سيتم شحن طلبك في أقرب وقت</p>
                </div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="bi bi-house-heart-fill"></i>
                    </div>
                    <h5>التوصيل</h5>
                    <p>سيصلك الطلب خلال 2-5 أيام</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="success-actions">
            @if(session('whatsapp_message') && session('whatsapp_number'))
                <a href="https://wa.me/{{ session('whatsapp_number') }}?text={{ session('whatsapp_message') }}" 
                   class="btn btn-success btn-lg" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i>
                    تأكيد الطلب عبر واتساب
                </a>
            @endif
            
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-house-fill me-2"></i>
                العودة للرئيسية
            </a>
            
            <a href="{{ route('products') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-grid-fill me-2"></i>
                متابعة التسوق
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Progress Bar */
    .progress-bar-container {
        background: white;
        padding: 2rem 0;
        margin-top: 90px;
        margin-bottom: 2rem;
        border-bottom: 1px solid #E8E8E8;
    }

    .checkout-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        flex: 1;
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        border: 3px solid #E8E8E8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #999;
        transition: all 0.3s;
        position: relative;
        z-index: 2;
    }

    .progress-step.completed .step-circle {
        background: #28a745;
        border-color: #28a745;
        color: white;
        animation: checkPulse 0.6s ease-in-out;
    }

    @keyframes checkPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .step-label {
        font-size: 0.85rem;
        color: #999;
        font-weight: 500;
        text-align: center;
    }

    .progress-step.completed .step-label {
        color: #28a745;
        font-weight: 600;
    }

    .progress-line {
        position: absolute;
        top: 25px;
        left: 50%;
        right: -50%;
        height: 3px;
        background: #E8E8E8;
        z-index: 1;
    }

    .progress-step:last-child .progress-line {
        display: none;
    }

    .progress-step.completed .progress-line {
        background: #28a745;
    }

    .success-container {
        max-width: 900px;
        margin: 0 auto;
        text-align: center;
    }
    
    /* Success Animation */
    .success-animation {
        margin-bottom: 2rem;
    }
    
    .success-checkmark {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        position: relative;
        animation: scaleIn 0.5s ease-in-out;
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .check-icon {
        width: 120px;
        height: 120px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #25D366;
        background: #f0fff4;
    }
    
    .icon-line {
        height: 5px;
        background-color: #25D366;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }
    
    .icon-line.line-tip {
        top: 56px;
        left: 25px;
        width: 25px;
        transform: rotate(45deg);
        animation: tipInPlace 0.75s ease-in-out 0.2s forwards;
    }
    
    .icon-line.line-long {
        top: 48px;
        right: 15px;
        width: 47px;
        transform: rotate(-45deg);
        animation: longInPlace 0.75s ease-in-out 0.4s forwards;
    }
    
    @keyframes tipInPlace {
        0% { width: 0; left: 5px; top: 36px; }
        100% { width: 25px; left: 25px; top: 56px; }
    }
    
    @keyframes longInPlace {
        0% { width: 0; right: 35px; top: 68px; }
        100% { width: 47px; right: 15px; top: 48px; }
    }
    
    .icon-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(37, 211, 102, 0.2);
    }
    
    .icon-fix {
        top: 12px;
        width: 10px;
        left: 34px;
        z-index: 1;
        height: 95px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #f0fff4;
    }
    
    /* Success Message */
    .success-message h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #28a745;
        margin-bottom: 1rem;
    }
    
    .success-message .lead {
        font-size: 1.3rem;
        color: #666;
        margin-bottom: 1rem;
    }
    
    .order-number {
        font-size: 1.1rem;
        color: #333;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        display: inline-block;
        margin-bottom: 2rem;
    }
    
    .order-number strong {
        color: #D4A017;
        font-size: 1.3rem;
    }
    
    /* Order Details Card */
    .order-details-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        text-align: right;
        margin: 2rem 0;
    }
    
    .details-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        text-align: right;
    }
    
    .info-section h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-section i {
        color: #D4A017;
    }
    
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .info-list li {
        padding: 0.5rem 0;
        color: #666;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .info-list li:last-child {
        border-bottom: none;
    }
    
    .products-section h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .products-section i {
        color: #D4A017;
    }
    
    .products-list {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
    }
    
    .product-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .product-row:last-child {
        border-bottom: none;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
    }
    
    .product-qty {
        font-size: 0.9rem;
        color: #666;
    }
    
    .product-total {
        font-weight: 700;
        color: #D4A017;
    }
    
    /* Invoice */
    .invoice-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
    }
    
    .invoice-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        color: #666;
    }
    
    .invoice-total {
        font-size: 1.3rem;
        color: #000;
        margin-top: 0.5rem;
    }
    
    .invoice-total strong {
        color: #25D366;
    }
    
    .payment-badge {
        background: #d4edda;
        color: #155724;
        padding: 0.75rem;
        border-radius: 8px;
        margin-top: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    /* Next Steps */
    .next-steps {
        margin: 3rem 0;
    }
    
    .next-steps h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 2rem;
    }
    
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .step-item {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    
    .step-item:hover {
        transform: translateY(-5px);
    }
    
    .step-icon {
        font-size: 3rem;
        color: #D4A017;
        margin-bottom: 1rem;
    }
    
    .step-item h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .step-item p {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }
    
    /* Actions */
    .success-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .success-actions .btn {
        padding: 1rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-success {
        background: #25D366;
        border: none;
    }
    
    .btn-success:hover {
        background: #20ba5a;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
    }
    
    @media (max-width: 768px) {
        .progress-bar-container {
            padding: 1.5rem 0;
            margin-top: 80px;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }

        .step-label {
            font-size: 0.75rem;
        }

        .success-message h1 {
            font-size: 1.75rem;
        }
        
        .steps-grid {
            grid-template-columns: 1fr;
        }
        
        .success-actions {
            flex-direction: column;
        }
        
        .success-actions .btn {
            width: 100%;
        }

        .order-details-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush
@endsection
