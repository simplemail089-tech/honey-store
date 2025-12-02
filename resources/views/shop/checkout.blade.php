@extends('shop.layout')

@section('title', 'إتمام الطلب')

@push('styles')
<style>
    /* Checkout Page */
    .checkout-page {
        padding-top: 90px;
        padding-bottom: 4rem;
        background: #FAFAFA;
        min-height: 100vh;
    }

    /* Progress Bar */
    .progress-bar-container {
        background: white;
        padding: 2rem 0;
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

    .progress-step.active .step-circle {
        background: #D4A017;
        border-color: #D4A017;
        color: white;
        box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
    }

    .progress-step.completed .step-circle {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }

    .step-label {
        font-size: 0.85rem;
        color: #999;
        font-weight: 500;
        text-align: center;
    }

    .progress-step.active .step-label {
        color: #D4A017;
        font-weight: 600;
    }

    .progress-step.completed .step-label {
        color: #28a745;
    }

    .progress-step::before {
        content: '';
        position: absolute;
        top: 25px;
        left: calc(50% + 25px);
        width: calc(100% - 50px);
        height: 3px;
        background: #E8E8E8;
        z-index: 1;
    }

    .progress-step:last-child::before {
        display: none;
    }

    .progress-step.completed::before {
        background: #28a745;
    }

    /* Page Header */
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #666;
        font-size: 0.95rem;
    }

    /* Form Sections */
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F0F0F0;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: rgba(212, 160, 23, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #D4A017;
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #000;
        margin: 0;
    }

    /* Form Groups */
    .form-group-icon {
        position: relative;
    }

    .form-group-icon .form-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1.1rem;
        pointer-events: none;
    }

    .form-group-icon .form-control,
    .form-group-icon .form-select {
        padding-right: 3rem;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-control, .form-select {
        border: 2px solid #E8E8E8;
        border-radius: 10px;
        padding: 0.7rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #D4A017;
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.1);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    /* Order Summary */
    .order-summary {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        position: sticky;
        top: 100px;
    }

    .summary-header {
        font-size: 1.2rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F0F0F0;
    }

    .summary-items {
        max-height: 250px;
        overflow-y: auto;
        margin-bottom: 1rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F5F5F5;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 0.9rem;
        color: #333;
        font-weight: 500;
        margin-bottom: 0.2rem;
    }

    .item-qty {
        font-size: 0.8rem;
        color: #999;
    }

    .item-price {
        font-weight: 600;
        color: #D4A017;
        font-size: 0.9rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        font-size: 0.9rem;
    }

    .summary-row span {
        color: #666;
    }

    .summary-row strong {
        color: #000;
        font-weight: 600;
    }

    .summary-divider {
        border: none;
        border-top: 1px solid #E8E8E8;
        margin: 1rem 0;
    }

    .summary-total {
        background: rgba(212, 160, 23, 0.05);
        padding: 1rem;
        border-radius: 10px;
        margin: 1rem 0;
    }

    .summary-total .summary-row {
        padding: 0;
        font-size: 1.1rem;
    }

    .summary-total span,
    .summary-total strong {
        color: #000;
        font-weight: 700;
    }

    /* Payment Method */
    .payment-badge {
        background: #F0F8F5;
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 1rem 0;
    }

    .payment-badge i {
        font-size: 1.5rem;
        color: #28a745;
    }

    .payment-text {
        flex: 1;
    }

    .payment-text strong {
        color: #000;
        font-size: 0.95rem;
    }

    .payment-text small {
        color: #666;
        font-size: 0.8rem;
    }

    /* Submit Button */
    .btn-submit {
        background: #D4A017;
        border: none;
        color: white;
        width: 100%;
        padding: 0.9rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: #B8860B;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    /* Security Note */
    .security-note {
        text-align: center;
        padding-top: 1rem;
        border-top: 1px solid #F0F0F0;
        margin-top: 1rem;
    }

    .security-note i {
        color: #28a745;
        font-size: 1.1rem;
    }

    .security-note small {
        color: #666;
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .checkout-page {
            padding-top: 80px;
        }

        .progress-bar-container {
            padding: 1.5rem 0;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }

        .step-label {
            font-size: 0.75rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .form-section {
            padding: 1.2rem;
        }

        .section-header {
            margin-bottom: 1rem;
        }

        .order-summary {
            position: static;
            margin-top: 1.5rem;
        }

        .summary-header {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="checkout-page">
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
                <div class="progress-step active">
                    <div class="step-circle">2</div>
                    <div class="step-label">إتمام الطلب</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Page Header -->
        <div class="text-center mb-4">
            <h1 class="page-title">إتمام الطلب</h1>
            <p class="page-subtitle">أدخل بياناتك لإتمام عملية الشراء بأمان</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            
            <div class="row g-3">
                <!-- Form Sections -->
                <div class="col-lg-8">
                    <!-- Customer Info -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h2 class="section-title">معلومات العميل</h2>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">
                                    الاسم الكامل <span class="required">*</span>
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-person form-icon"></i>
                                    <input type="text" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" 
                                           name="customer_name" 
                                           value="{{ old('customer_name') }}" 
                                           placeholder="محمد أحمد"
                                           required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">
                                    رقم الهاتف <span class="required">*</span>
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-telephone form-icon"></i>
                                    <input type="tel" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" 
                                           name="customer_phone" 
                                           value="{{ old('customer_phone') }}" 
                                           placeholder="01xxxxxxxxx" 
                                           required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="customer_email" class="form-label">
                                    البريد الإلكتروني (اختياري)
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-envelope form-icon"></i>
                                    <input type="email" 
                                           class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" 
                                           name="customer_email" 
                                           value="{{ old('customer_email') }}"
                                           placeholder="example@email.com">
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <h2 class="section-title">عنوان التوصيل</h2>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="shipping_state" class="form-label">
                                    المحافظة <span class="required">*</span>
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-map form-icon"></i>
                                    <select class="form-select @error('shipping_state') is-invalid @enderror" 
                                            id="shipping_state" 
                                            name="shipping_state" 
                                            required>
                                        <option value="">اختر المحافظة</option>
                                        @foreach($shippingRates as $state => $cities)
                                            <option value="{{ $state }}" {{ old('shipping_state') == $state ? 'selected' : '' }}>
                                                {{ $state }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shipping_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="shipping_city" class="form-label">
                                    المدينة <span class="required">*</span>
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-building form-icon"></i>
                                    <select class="form-select @error('shipping_city') is-invalid @enderror" 
                                            id="shipping_city" 
                                            name="shipping_city" 
                                            required
                                            disabled>
                                        <option value="">اختر المحافظة أولاً</option>
                                    </select>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="shipping_address_line1" class="form-label">
                                    العنوان التفصيلي <span class="required">*</span>
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-house form-icon"></i>
                                    <input type="text" 
                                           class="form-control @error('shipping_address_line1') is-invalid @enderror" 
                                           id="shipping_address_line1" 
                                           name="shipping_address_line1" 
                                           value="{{ old('shipping_address_line1') }}"
                                           placeholder="رقم الشارع، اسم الحي، علامة مميزة" 
                                           required>
                                    @error('shipping_address_line1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="shipping_address_line2" class="form-label">
                                    عنوان إضافي (اختياري)
                                </label>
                                <div class="form-group-icon">
                                    <i class="bi bi-signpost form-icon"></i>
                                    <input type="text" 
                                           class="form-control" 
                                           id="shipping_address_line2" 
                                           name="shipping_address_line2" 
                                           value="{{ old('shipping_address_line2') }}"
                                           placeholder="رقم الشقة، الدور">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <h2 class="section-title">ملاحظات إضافية</h2>
                        </div>
                        
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="أي ملاحظات خاصة بالطلب...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="order-summary">
                        <h3 class="summary-header">ملخص الطلب</h3>
                        
                        <!-- Items -->
                        <div class="summary-items">
                            @foreach($cartItems as $item)
                                <div class="summary-item">
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->product->name }}</div>
                                        <div class="item-qty">الكمية: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="item-price">
                                        {{ number_format($item->quantity * $item->unit_price, 2) }} ج.م
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr class="summary-divider">
                        
                        <!-- Subtotal -->
                        <div class="summary-row">
                            <span>المجموع الفرعي</span>
                            <strong id="subtotal-amount">{{ number_format($subtotal, 2) }} ج.م</strong>
                        </div>
                        
                        @if(session('coupon'))
                        <!-- Coupon Discount -->
                        <div class="summary-row" style="color: #28a745;">
                            <span>
                                <i class="bi bi-tag-fill me-1"></i>
                                الخصم ({{ session('coupon')['code'] }})
                            </span>
                            <strong id="discount-amount">-{{ number_format(session('coupon')['discount'], 2) }} ج.م</strong>
                        </div>
                        @endif
                        
                        <!-- Shipping -->
                        <div class="summary-row">
                            <span>تكلفة الشحن</span>
                            <strong id="shipping-amount" class="text-muted">اختر المدينة</strong>
                        </div>
                        
                        <!-- Total -->
                        <div class="summary-total">
                            <div class="summary-row">
                                <span>المجموع الكلي</span>
                                <strong id="total-amount">{{ number_format($subtotal - (session('coupon')['discount'] ?? 0), 2) }} ج.م</strong>
                            </div>
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="payment-badge">
                            <i class="bi bi-cash-coin"></i>
                            <div class="payment-text">
                                <strong>الدفع عند الاستلام</strong><br>
                                <small>ادفع نقداً عند استلام طلبك</small>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle"></i>
                            <span>تأكيد الطلب</span>
                        </button>
                        
                        <!-- Security -->
                        <div class="security-note">
                            <i class="bi bi-shield-check"></i>
                            <small>معلوماتك آمنة ومحمية 100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Shipping Data (محافظات ومدن)
    const shippingData = @json($shippingRates);
    
    // عناصر DOM
    const subtotal = {{ $subtotal }};
    const discount = {{ session('coupon')['discount'] ?? 0 }};
    const stateSelect = document.getElementById('shipping_state');
    const citySelect = document.getElementById('shipping_city');
    const shippingAmount = document.getElementById('shipping-amount');
    const totalAmount = document.getElementById('total-amount');
    
    // عند اختيار محافظة
    stateSelect.addEventListener('change', function() {
        const selectedState = this.value;
        
        // مسح المدن القديمة
        citySelect.innerHTML = '<option value="">اختر المدينة</option>';
        citySelect.disabled = true;
        
        if (selectedState && shippingData[selectedState]) {
            // تفعيل قائمة المدن
            citySelect.disabled = false;
            
            // إضافة المدن
            shippingData[selectedState].forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = `${city.name} - ${city.cost} ج.م`;
                option.setAttribute('data-cost', city.cost);
                citySelect.appendChild(option);
            });
        }
        
        // إعادة تعيين الشحن
        updateShipping(null);
    });
    
    // عند اختيار مدينة
    citySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const shippingCost = selectedOption.getAttribute('data-cost');
        updateShipping(shippingCost);
    });
    
    // تحديث حساب الشحن
    function updateShipping(shippingCost) {
        if (shippingCost) {
            const shipping = parseFloat(shippingCost);
            const total = subtotal - discount + shipping;
            
            shippingAmount.textContent = shipping.toFixed(2) + ' ج.م';
            shippingAmount.classList.remove('text-muted');
            shippingAmount.style.color = '#D4A017';
            
            totalAmount.textContent = total.toFixed(2) + ' ج.م';
        } else {
            shippingAmount.textContent = 'اختر المدينة';
            shippingAmount.classList.add('text-muted');
            shippingAmount.style.color = '';
            
            const total = subtotal - discount;
            totalAmount.textContent = total.toFixed(2) + ' ج.م';
        }
    }
    
    // Form Validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        if (!citySelect.value) {
            e.preventDefault();
            citySelect.focus();
            citySelect.classList.add('is-invalid');
            if (typeof showNotification === 'function') {
                showNotification('الرجاء اختيار المدينة', 'error');
            } else {
                alert('الرجاء اختيار المحافظة والمدينة');
            }
        }
    });
    
    // Remove invalid class on change
    const formInputs = document.querySelectorAll('.form-control, .form-select');
    formInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>
@endpush
