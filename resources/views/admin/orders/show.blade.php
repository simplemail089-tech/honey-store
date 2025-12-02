@extends('admin.layout')

@section('title', 'تفاصيل الطلب #' . $order->id)
@section('page-title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<div class="row">
    <!-- معلومات الطلب الأساسية -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cart-check me-2"></i>
                    الطلب رقم: {{ $order->order_number ?? '#' . $order->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">تاريخ الطلب</p>
                        <p class="fw-bold">{{ $order->created_at->format('Y-m-d h:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">الحالة</p>
                        <p>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">قيد الانتظار</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info">قيد المراجعة</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">مكتمل</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">ملغي</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3"><i class="bi bi-box-seam me-2"></i>المنتجات المطلوبة</h6>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>المنتج</th>
                                <th style="width: 100px;">الصورة</th>
                                <th style="width: 100px;">السعر</th>
                                <th style="width: 80px;">الكمية</th>
                                <th style="width: 120px;">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product ? $item->product->name : ($item->snapshot['name'] ?? 'منتج محذوف') }}</strong>
                                        @if(isset($item->snapshot['variant']['size']))
                                            <br>
                                            <span class="badge bg-info" style="font-size: 0.8rem;">
                                                <i class="bi bi-basket"></i>
                                                الحجم: {{ $item->snapshot['variant']['size'] }}
                                            </span>
                                        @endif
                                        @if($item->product)
                                            <br>
                                            <small class="text-muted">رمز: #{{ $item->product->id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product && $item->product->main_image)
                                            @if(str_starts_with($item->product->main_image, 'http'))
                                                <img src="{{ $item->product->main_image }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td><strong>{{ number_format($item->total_price, 2) }} ج.م</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">لا توجد منتجات في هذا الطلب</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>المجموع الفرعي:</strong></td>
                                <td><strong>{{ number_format($order->subtotal ?? 0, 2) }} ج.م</strong></td>
                            </tr>
                            @if($order->coupon_id && $order->discount_total > 0)
                            <tr class="table-success">
                                <td colspan="4" class="text-end">
                                    <strong>
                                        <i class="bi bi-tag-fill me-1"></i>
                                        الخصم (كوبون: {{ $order->coupon->code ?? 'غير متاح' }}):
                                    </strong>
                                </td>
                                <td><strong class="text-success">-{{ number_format($order->discount_total, 2) }} ج.م</strong></td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-end"><strong>الشحن:</strong></td>
                                <td><strong>{{ number_format($order->shipping_total ?? 0, 2) }} ج.م</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"><strong>الإجمالي الكلي:</strong></td>
                                <td><strong class="fs-5">{{ number_format($order->total, 2) }} ج.م</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات العميل والشحن -->
    <div class="col-lg-4">
        <!-- معلومات العميل -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>معلومات العميل</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong><i class="bi bi-person-fill text-primary me-2"></i>الاسم:</strong><br>
                    <span class="ms-4">{{ $order->customer_name }}</span>
                </p>
                <p class="mb-2">
                    <strong><i class="bi bi-telephone-fill text-success me-2"></i>الهاتف:</strong><br>
                    <span class="ms-4">
                        <a href="tel:{{ $order->customer_phone }}" class="text-decoration-none">{{ $order->customer_phone }}</a>
                    </span>
                </p>
                @if($order->customer_email)
                    <p class="mb-2">
                        <strong><i class="bi bi-envelope-fill text-info me-2"></i>البريد:</strong><br>
                        <span class="ms-4">
                            <a href="mailto:{{ $order->customer_email }}" class="text-decoration-none">{{ $order->customer_email }}</a>
                        </span>
                    </p>
                @endif
            </div>
        </div>

        <!-- عنوان الشحن -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>عنوان الشحن</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong><i class="bi bi-building text-warning me-2"></i>المدينة:</strong><br>
                    <span class="ms-4">{{ $order->shipping_city }}</span>
                </p>
                <p class="mb-0">
                    <strong><i class="bi bi-house text-danger me-2"></i>العنوان:</strong><br>
                    <span class="ms-4">{{ $order->shipping_address_line1 }}</span>
                    @if($order->shipping_address_line2)
                        <br>
                        <span class="ms-4">{{ $order->shipping_address_line2 }}</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- معلومات الدفع -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-credit-card me-2"></i>معلومات الدفع</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>طريقة الدفع:</strong><br>
                    <span class="ms-4">
                        @if($order->payment_method == 'cash_on_delivery')
                            <span class="badge bg-success">الدفع عند الاستلام</span>
                        @else
                            {{ $order->payment_method }}
                        @endif
                    </span>
                </p>
                <p class="mb-0">
                    <strong>حالة الدفع:</strong><br>
                    <span class="ms-4">
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">مدفوع</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="badge bg-warning">قيد الانتظار</span>
                        @else
                            <span class="badge bg-secondary">غير مدفوع</span>
                        @endif
                    </span>
                </p>
            </div>
        </div>

        <!-- إجراءات -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gear me-2"></i>إجراءات</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="mb-3">
                    @csrf
                    @method('PUT')
                    <label class="form-label small fw-bold">تحديث حالة الطلب:</label>
                    <select name="status" class="form-select mb-2">
                        <option value="processing" @selected($order->status == 'processing')>
                            <i class="bi bi-gear"></i> قيد المراجعة
                        </option>
                        <option value="completed" @selected($order->status == 'completed')>
                            <i class="bi bi-check-circle"></i> مكتمل
                        </option>
                        <option value="cancelled" @selected($order->status == 'cancelled')>
                            <i class="bi bi-x-circle"></i> ملغي
                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-1"></i>تحديث الحالة
                    </button>
                </form>

                <hr>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text=مرحباً {{ $order->customer_name }}، بخصوص طلبك رقم {{ $order->id }}" 
                   target="_blank" 
                   class="btn btn-success btn-sm w-100 mb-2">
                    <i class="bi bi-whatsapp me-1"></i>تواصل عبر واتساب
                </a>

                <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-arrow-right me-1"></i>رجوع إلى قائمة الطلبات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
