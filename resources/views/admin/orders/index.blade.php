@extends('admin.layout')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">قائمة الطلبات</h5>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' || !request('status') ? 'active' : '' }}" href="{{ route('admin.orders', ['status' => 'pending']) }}">
            <i class="bi bi-clock-history me-1"></i>
            طلبات جديدة
            @php
                $pendingCount = \App\Models\Order::where('status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $pendingCount }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'processing' ? 'active' : '' }}" href="{{ route('admin.orders', ['status' => 'processing']) }}">
            <i class="bi bi-gear me-1"></i>
            قيد المراجعة
            @php
                $processingCount = \App\Models\Order::where('status', 'processing')->count();
            @endphp
            @if($processingCount > 0)
                <span class="badge bg-info text-dark rounded-pill ms-1">{{ $processingCount }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('admin.orders', ['status' => 'completed']) }}">
            <i class="bi bi-check-circle me-1"></i>
            مكتمل
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders', ['status' => 'cancelled']) }}">
            <i class="bi bi-x-circle me-1"></i>
            ملغي
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        @if($orders->count() > 0)
            <!-- عدد النتائج -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">
                    <i class="bi bi-cart-check me-1"></i>
                    عرض {{ $orders->count() }} من أصل {{ $orders->total() }} طلب
                </p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>الهاتف</th>
                            <th>الإجمالي</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    <strong>{{ $order->order_number ?? 'ORD-' . $order->id }}</strong>
                                </td>
                                <td>
                                    <div>{{ $order->customer_name }}</div>
                                    @if($order->customer_email)
                                        <small class="text-muted">{{ $order->customer_email }}</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="tel:{{ $order->customer_phone }}" class="text-decoration-none">
                                        {{ $order->customer_phone }}
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ number_format($order->total, 2) }} ج.م</strong>
                                        @if($order->coupon_id && $order->discount_total > 0)
                                            <span class="badge bg-success ms-1" style="font-size: 0.7rem;" title="تم استخدام كوبون خصم">
                                                <i class="bi bi-tag-fill"></i>
                                                {{ $order->coupon->code ?? 'كوبون' }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($order->discount_total > 0)
                                        <small class="text-success">خصم: {{ number_format($order->discount_total, 2) }} ج.م</small>
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('Y-m-d') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary" title="عرض التفاصيل">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-success" 
                                       title="واتساب">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-check fs-1 text-muted"></i>
                <p class="text-muted mt-3 mb-1">لا توجد طلبات حتى الآن</p>
            </div>
        @endif
    </div>
</div>
@endsection
