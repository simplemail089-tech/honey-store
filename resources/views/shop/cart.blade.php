@extends('shop.layout')

@section('title', 'سلة التسوق')

@section('content')
<div class="container" style="margin-top: 120px; margin-bottom: 4rem;">
    
    @if($cartItems->isEmpty())
        <!-- Empty State -->
        <div class="empty-cart-state">
            <div class="empty-cart-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h2>سلة التسوق فارغة</h2>
            <p>لم تقم بإضافة أي منتجات إلى سلتك بعد</p>
            <a href="{{ route('products') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-grid me-2"></i>
                تصفح المنتجات
            </a>
        </div>
    @else
        <!-- Cart Header -->
        <div class="cart-header mb-4">
            <h1>سلة التسوق</h1>
            <p class="text-muted">لديك {{ $cartItems->count() }} {{ $cartItems->count() == 1 ? 'منتج' : 'منتجات' }} في سلتك</p>
        </div>

        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items-container">
                    @foreach($cartItems as $item)
                        <div class="cart-item" id="cart-item-{{ $item->id }}">
                            <a href="{{ route('products.show', $item->product) }}" class="cart-item-image">
                                @if($item->product->main_image)
                                    @if(str_starts_with($item->product->main_image, 'http'))
                                        <img src="{{ $item->product->main_image }}" alt="{{ $item->product->name }}">
                                    @else
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}">
                                    @endif
                                @else
                                    <img src="https://via.placeholder.com/150x150/FFF8E7/D4A017?text=عسل" alt="{{ $item->product->name }}">
                                @endif
                            </a>
                            
                            <div class="cart-item-details">
                                <a href="{{ route('products.show', $item->product) }}" class="cart-item-name-link">
                                    <h5 class="cart-item-name">{{ $item->product->name }}</h5>
                                </a>
                                @if($item->variant)
                                    <p class="cart-item-variant">
                                        <i class="bi bi-boxes me-1"></i>
                                        <span class="badge bg-primary">{{ $item->variant->size }}</span>
                                    </p>
                                @endif
                                <p class="cart-item-category">
                                    <i class="bi bi-tag me-1"></i>
                                    {{ $item->product->category->name ?? 'عام' }}
                                </p>
                                <div class="cart-item-price">
                                    <span class="price">{{ number_format($item->unit_price, 2) }} ج.م</span>
                                </div>
                            </div>
                            
                            <div class="cart-item-actions">
                                <div class="quantity-control">
                                    <button class="qty-btn qty-minus" onclick="handleQuantityChange({{ $item->id }}, {{ $item->quantity }}, 'decrease')" data-item-id="{{ $item->id }}">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="qty-input" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock ?? 999 }}" readonly>
                                    <button class="qty-btn qty-plus" onclick="handleQuantityChange({{ $item->id }}, {{ $item->quantity }}, 'increase')" data-item-id="{{ $item->id }}">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                
                                <div class="cart-item-total">
                                    <strong id="item-total-{{ $item->id }}">
                                        {{ number_format($item->quantity * $item->unit_price, 2) }} ج.م
                                    </strong>
                                </div>
                                
                                <!-- Error message inline -->
                                <div id="error-{{ $item->id }}" class="cart-item-error" style="display: none;"></div>
                                
                                <button class="btn-remove" onclick="removeItem({{ $item->id }})" title="حذف">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="cart-actions mt-3 text-center">
                    <a href="{{ route('products') }}" class="text-muted text-decoration-none" style="font-size: 0.9rem;">
                        <i class="bi bi-arrow-right me-1"></i>
                        متابعة التسوق
                    </a>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h4 class="summary-title">ملخص الطلب</h4>
                    
                    <div class="summary-row">
                        <span>المجموع الفرعي</span>
                        <strong id="subtotal-amount">{{ number_format($subtotal, 2) }} ج.م</strong>
                    </div>
                    
                    <!-- Coupon Section -->
                    <div class="coupon-section mt-3 mb-3">
                        @if(session('coupon'))
                            <div class="coupon-applied">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <i class="bi bi-tag-fill text-success me-2"></i>
                                        <strong>{{ session('coupon')['code'] }}</strong>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                <div class="summary-row text-success">
                                    <span>الخصم</span>
                                    <strong id="discount-amount">-{{ number_format(session('coupon')['discount'], 2) }} ج.م</strong>
                                </div>
                            </div>
                        @else
                            <!-- Collapsible Coupon -->
                            <div class="coupon-toggle">
                                <a href="#" class="text-decoration-none" onclick="event.preventDefault(); document.getElementById('couponFormCollapse').style.display = document.getElementById('couponFormCollapse').style.display === 'none' ? 'block' : 'none'; this.style.display = 'none';" style="font-size: 0.9rem; color: #666;">
                                    <i class="bi bi-tag me-1"></i>
                                    هل لديك كود خصم؟
                                </a>
                            </div>
                            <div class="coupon-form" id="couponFormCollapse" style="display: none; margin-top: 1rem;">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="couponCode" class="form-control" placeholder="أدخل الكود" style="border-radius: 8px 0 0 8px;">
                                    <button class="btn btn-outline-warning" onclick="applyCoupon()" id="applyCouponBtn" style="border-radius: 0 8px 8px 0;">
                                        تطبيق
                                    </button>
                                </div>
                                <div id="coupon-message" class="mt-2" style="display: none;"></div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="summary-row">
                        <span>الشحن</span>
                        <strong id="shipping-amount" class="text-muted" style="font-size: 0.95rem;">سيُحسب عند الدفع</strong>
                    </div>
                    
                    <hr>
                    
                    <div class="summary-row summary-total">
                        <span>المجموع</span>
                        <strong id="total-amount">{{ number_format($total - (session('coupon')['discount'] ?? 0), 2) }} ج.م</strong>
                    </div>
                    
                    <p class="text-muted small mt-2 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        تكلفة الشحن تُحدد بناءً على منطقة التوصيل
                    </p>
                    
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-checkout w-100 mt-3">
                        <i class="bi bi-credit-card me-2"></i>
                        إتمام الطلب
                    </a>
                    
                    <div class="security-badges mt-3">
                        <div class="badge-item">
                            <i class="bi bi-shield-check"></i>
                            <span>دفع آمن</span>
                        </div>
                        <div class="badge-item">
                            <i class="bi bi-truck"></i>
                            <span>شحن سريع</span>
                        </div>
                        <div class="badge-item">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>إرجاع سهل</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Empty State */
    .empty-cart-state {
        text-align: center;
        padding: 4rem 2rem;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .empty-cart-icon {
        font-size: 6rem;
        color: #ddd;
        margin-bottom: 1.5rem;
    }
    
    .empty-cart-state h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .empty-cart-state p {
        font-size: 1.1rem;
        color: #666;
    }
    
    /* Cart Header */
    .cart-header h1 {
        font-size: 2rem;
        font-weight: 900;
        color: #000;
        margin-bottom: 0.5rem;
        font-family: 'Amiri', serif;
    }
    
    /* Cart Items Container */
    .cart-items-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.3s ease;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item:hover {
        background: #fafafa;
    }
    
    .cart-item-image {
        flex-shrink: 0;
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #f0f0f0;
        display: block;
        transition: all 0.3s ease;
    }
    
    .cart-item-image:hover {
        border-color: #D4A017;
        transform: scale(1.05);
    }
    
    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .cart-item-name-link {
        text-decoration: none;
        color: inherit;
        transition: color 0.3s ease;
    }
    
    .cart-item-name-link:hover .cart-item-name {
        color: #D4A017;
    }
    
    .cart-item-details {
        flex-grow: 1;
    }
    
    .cart-item-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-variant {
        font-size: 0.85rem;
        color: #333;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cart-item-variant .badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35rem 0.7rem;
    }
    
    .cart-item-category {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-price .price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #D4A017;
    }
    
    /* Cart Item Actions */
    .cart-item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 1rem;
    }
    
    /* Inline Error Messages */
    .cart-item-error {
        font-size: 0.85rem;
        color: #dc3545;
        background: #ffe6e6;
        border: 1px solid #ffcccc;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        margin-top: 0.5rem;
        text-align: center;
        animation: slideIn 0.3s ease-out;
    }
    
    .cart-item-error i {
        margin-left: 0.3rem;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Coupon Messages */
    .coupon-error {
        color: #dc3545;
        background: #ffe6e6;
        border: 1px solid #ffcccc;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.9rem;
        animation: slideIn 0.3s ease-out;
    }
    
    .coupon-success {
        color: #28a745;
        background: #e6ffe6;
        border: 1px solid #ccffcc;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.9rem;
        animation: slideIn 0.3s ease-out;
    }
    
    .coupon-error i,
    .coupon-success i {
        margin-left: 0.3rem;
    }
    
    /* Button Loading State */
    .qty-btn:disabled,
    .btn-remove:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spinner 0.6s linear infinite;
    }
    
    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f8f8f8;
        border-radius: 8px;
        padding: 0.25rem;
    }
    
    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #666;
    }
    
    .qty-btn:hover {
        background: #D4A017;
        color: white;
    }
    
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #000;
    }
    
    .cart-item-total {
        font-size: 1.3rem;
        color: #000;
    }
    
    .btn-remove {
        background: transparent;
        border: none;
        color: #DC3545;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-remove:hover {
        background: #ffebee;
    }
    
    /* Cart Actions */
    .cart-actions {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }
    
    /* Order Summary */
    .order-summary {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        position: sticky;
        top: 100px;
    }
    
    .summary-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        font-family: 'Amiri', serif;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    
    .summary-row span {
        color: #666;
    }
    
    .summary-row strong {
        color: #000;
    }
    
    .summary-total {
        font-size: 1.3rem;
        margin-top: 1rem;
    }
    
    .summary-total span,
    .summary-total strong {
        color: #000;
        font-size: 1.3rem;
    }
    
    .btn-checkout {
        background: #D4A017;
        border: none;
        padding: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-checkout:hover {
        background: #b8890f;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
    }
    
    /* Security Badges */
    .coupon-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
    }
    
    .coupon-form .form-label {
        font-size: 0.95rem;
        color: #000;
        color: #666;
        font-size: 0.9rem;
    }
    
    .badge-item i {
        color: #25D366;
        font-size: 1.2rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
        }
        
        .cart-item-actions {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-actions {
            flex-direction: column;
        }
        
        .order-summary {
            position: static;
            margin-top: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // دالة عرض Modal التأكيد
    function showConfirmModal(title, message, onConfirm) {
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        document.getElementById('confirmModalTitle').textContent = title;
        document.getElementById('confirmModalMessage').textContent = message;
        
        const confirmBtn = document.getElementById('confirmModalBtn');
        
        // إزالة أي event listeners قديمة
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        // إضافة event listener جديد
        newConfirmBtn.addEventListener('click', () => {
            modal.hide();
            onConfirm();
        });
        
        modal.show();
    }

    // معالجة تغيير الكمية (زيادة أو نقصان)
    async function handleQuantityChange(itemId, currentQuantity, action) {
        // إذا كانت الكمية = 1 وتم الضغط على الناقص، احذف المنتج
        if (action === 'decrease' && currentQuantity === 1) {
            removeItem(itemId);
            return;
        }
        
        const newQuantity = action === 'increase' ? currentQuantity + 1 : currentQuantity - 1;
        
        if (newQuantity < 1) return;
        
        // تعطيل الأزرار لمنع التعليق
        const buttons = document.querySelectorAll(`[data-item-id="${itemId}"]`);
        buttons.forEach(btn => btn.disabled = true);
        
        // إخفاء رسائل الخطأ السابقة
        const errorDiv = document.getElementById(`error-${itemId}`);
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
        
        try {
            const response = await fetch(`/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQuantity })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // تحديث العرض
                document.querySelector(`#cart-item-${itemId} .qty-input`).value = newQuantity;
                document.querySelector(`#item-total-${itemId}`).textContent = data.itemTotal + ' ج.م';
                document.querySelector('#subtotal-amount').textContent = data.subtotal + ' ج.م';
                document.querySelector('#total-amount').textContent = data.total + ' ج.م';
                
                // تحديث عداد السلة
                updateCartCount();
            } else {
                // عرض رسالة خطأ inline
                showInlineError(itemId, data.message || 'حدث خطأ أثناء تحديث الكمية');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            showInlineError(itemId, 'حدث خطأ أثناء تحديث الكمية');
        } finally {
            // إعادة تفعيل الأزرار
            buttons.forEach(btn => btn.disabled = false);
        }
    }
    
    // عرض رسالة خطأ inline
    function showInlineError(itemId, message) {
        const errorDiv = document.getElementById(`error-${itemId}`);
        if (errorDiv) {
            errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
            errorDiv.style.display = 'block';
            
            // إخفاء الرسالة بعد 5 ثواني
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    }
    
    // حذف منتج
    function removeItem(itemId) {
        showConfirmModal(
            'حذف المنتج',
            'هل أنت متأكد من حذف هذا المنتج من السلة؟',
            async () => {
                try {
                    const response = await fetch(`/cart/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // إزالة العنصر من DOM
                        const itemElement = document.querySelector(`#cart-item-${itemId}`);
                        if (itemElement) {
                            itemElement.style.opacity = '0';
                            itemElement.style.transform = 'translateX(100px)';
                            
                            setTimeout(() => {
                                itemElement.remove();
                                
                                // تحديث عداد السلة
                                updateCartCount();
                                
                                // إذا كانت السلة فارغة، إعادة تحميل الصفحة
                                if (data.isEmpty) {
                                    location.reload();
                                } else {
                                    // تحديث المجاميع
                                    document.querySelector('#subtotal-amount').textContent = data.subtotal + ' ج.م';
                                    document.querySelector('#total-amount').textContent = data.total + ' ج.م';
                                }
                            }, 300);
                        }
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                }
            }
        );
    }
    
    // تفريغ السلة
    function clearCart() {
        showConfirmModal(
            'تفريغ السلة',
            'هل أنت متأكد من تفريغ السلة بالكامل؟',
            async () => {
                try {
                    const response = await fetch('/cart/clear', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        location.reload();
                    }
                } catch (error) {
                    console.error('Error clearing cart:', error);
                }
            }
        );
    }
    
    // تطبيق الكوبون
    async function applyCoupon() {
        const code = document.getElementById('couponCode').value.trim();
        const messageDiv = document.getElementById('coupon-message');
        const hint = document.getElementById('coupon-hint');
        const btn = document.getElementById('applyCouponBtn');
        
        // إخفاء الرسائل السابقة
        messageDiv.style.display = 'none';
        
        if (!code) {
            messageDiv.className = 'mt-2 coupon-error';
            messageDiv.innerHTML = '<i class="bi bi-exclamation-circle"></i> الرجاء إدخال كود الكوبون';
            messageDiv.style.display = 'block';
            hint.style.display = 'none';
            return;
        }
        
        // تعطيل الزر أثناء المعالجة
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> جاري التحقق...';
        
        const subtotal = parseFloat(document.getElementById('subtotal-amount').textContent.replace(/[^\d.]/g, ''));
        
        try {
            const response = await fetch('{{ route("coupon.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code, subtotal })
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageDiv.className = 'mt-2 coupon-success';
                messageDiv.innerHTML = '<i class="bi bi-check-circle"></i> ' + data.message;
                messageDiv.style.display = 'block';
                hint.style.display = 'none';
                setTimeout(() => location.reload(), 1000);
            } else {
                messageDiv.className = 'mt-2 coupon-error';
                messageDiv.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + data.message;
                messageDiv.style.display = 'block';
                hint.style.display = 'none';
                btn.disabled = false;
                btn.innerHTML = 'تطبيق';
            }
        } catch (error) {
            console.error('Error applying coupon:', error);
            messageDiv.className = 'mt-2 coupon-error';
            messageDiv.innerHTML = '<i class="bi bi-exclamation-circle"></i> حدث خطأ أثناء تطبيق الكوبون';
            messageDiv.style.display = 'block';
            hint.style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = 'تطبيق';
        }
    }
    
    // إزالة الكوبون
    async function removeCoupon() {
        try {
            const response = await fetch('{{ route("coupon.remove") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error removing coupon:', error);
        }
    }
    
    // Enter key for coupon input
    document.getElementById('couponCode')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyCoupon();
        }
    });
</script>
@endpush

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 1.5rem;">
                <h5 class="modal-title" style="color: #000; font-weight: 700;" id="confirmModalTitle">تأكيد العملية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #ffc107;"></i>
                </div>
                <p class="text-center mb-0" style="font-size: 1.1rem; color: #666;" id="confirmModalMessage">
                    هل أنت متأكد من هذا الإجراء؟
                </p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 1rem 1.5rem; gap: 0.75rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="flex: 1;">
                    <i class="bi bi-x-circle me-1"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-danger" id="confirmModalBtn" style="flex: 1;">
                    <i class="bi bi-check-circle me-1"></i>
                    تأكيد
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
