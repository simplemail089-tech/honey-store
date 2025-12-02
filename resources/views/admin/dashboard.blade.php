@extends('admin.layout')

@section('title', 'لوحة التحكم الرئيسية')
@section('page-title', 'لوحة التحكم')

@section('content')

<!-- تم نقل التنبيهات إلى Notification Bell في Topbar -->

<!-- إحصائيات مدمجة -->
<div class="row mb-4">
    <!-- طلبات اليوم -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">طلبات اليوم</p>
                        <h2 class="mb-0 text-primary">{{ $stats['today_orders'] }} <small class="fs-6 text-muted">طلب</small></h2>
                        <div class="mt-2 d-flex gap-3">
                            @if($stats['today_orders'] > 0)
                                <div>
                                    <i class="bi bi-currency-dollar text-success me-1"></i>
                                    <strong class="text-success">{{ number_format($stats['today_sales'], 0) }}</strong>
                                    <small class="text-muted">ج.م</small>
                                </div>
                                <div>
                                    <small class="text-muted">متوسط:</small>
                                    <strong class="text-dark">{{ number_format($stats['today_sales'] / $stats['today_orders'], 0) }} ج.م</strong>
                                </div>
                            @else
                                <small class="text-muted">لا توجد طلبات اليوم</small>
                            @endif
                        </div>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-cart-check fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- طلبات تحتاج مراجعة -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">طلبات تحتاج مراجعه</p>
                        <h2 class="mb-0 text-warning">{{ $stats['pending_orders'] }}</h2>
                        <div class="mt-3">
                            @if($stats['pending_orders'] > 0)
                                <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-clipboard-check me-1"></i>
                                    راجع الطلبات
                                </a>
                            @else
                                <small class="text-muted">✓ كل الطلبات تمت مراجعتها</small>
                            @endif
                        </div>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-clipboard-check fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مركز الإجراءات + أكثر المنتجات مبيعاً -->
<div class="row mb-4">
    <!-- مركز الإجراءات العاجلة -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-clipboard-check text-warning me-2"></i>
                    <strong>طلبات ومنتجات تحتاج مراجعة</strong>
                </h6>
                <small class="text-muted">راجع هذه النقاط لزيادة المبيعات</small>
            </div>
            <div class="card-body p-0">
                @php
                    $urgentTasks = [];
                    
                    // منتجات نفذت
                    if($stats['out_of_stock_products']->count() > 0) {
                        foreach($stats['out_of_stock_products']->take(3) as $product) {
                            $urgentTasks[] = [
                                'type' => 'danger',
                                'icon' => 'x-circle',
                                'title' => $product->name . ' نفذ من المخزون',
                                'description' => 'العملاء يبحثون عن هذا المنتج',
                                'action_text' => 'أعد الطلب الآن',
                                'action_url' => route('admin.products.edit', $product),
                                'priority' => 1
                            ];
                        }
                    }
                    
                    // طلبات معلقة
                    if($stats['pending_orders'] > 0) {
                        $oldPendingOrders = \App\Models\Order::where('status', 'pending')
                                                              ->where('created_at', '<', now()->subHours(24))
                                                              ->count();
                        if($oldPendingOrders > 0) {
                            $urgentTasks[] = [
                                'type' => 'warning',
                                'icon' => 'clock-history',
                                'title' => $oldPendingOrders . ' طلب معلق منذ أكثر من 24 ساعة',
                                'description' => 'اتصل بالعملاء لتأكيد الطلبات',
                                'action_text' => 'عرض الطلبات',
                                'action_url' => route('admin.orders', ['status' => 'pending']),
                                'priority' => 2
                            ];
                        }
                    }
                    
                    // منتجات مخزون منخفض
                    if($stats['low_stock_products']->count() > 0) {
                        foreach($stats['low_stock_products']->take(2) as $product) {
                            $urgentTasks[] = [
                                'type' => 'info',
                                'icon' => 'exclamation-triangle',
                                'title' => $product->name . ' - المخزون: ' . $product->stock,
                                'description' => 'أوشك على النفاذ',
                                'action_text' => 'تحديث المخزون',
                                'action_url' => route('admin.products.edit', $product),
                                'priority' => 3
                            ];
                        }
                    }
                    
                    // مراجعات تحتاج موافقة
                    $pendingReviews = \App\Models\ProductReview::where('is_approved', false)->count();
                    if($pendingReviews > 0) {
                        $urgentTasks[] = [
                            'type' => 'success',
                            'icon' => 'star',
                            'title' => $pendingReviews . ' تقييم جديد بانتظار الموافقة',
                            'description' => 'التقييمات تزيد الثقة وتدفع المبيعات',
                            'action_text' => 'مراجعة التقييمات',
                            'action_url' => route('admin.reviews'),
                            'priority' => 4
                        ];
                    }
                    
                    usort($urgentTasks, function($a, $b) {
                        return $a['priority'] - $b['priority'];
                    });
                @endphp
                
                @if(count($urgentTasks) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($urgentTasks as $task)
                            <a href="{{ $task['action_url'] }}" class="list-group-item list-group-item-action border-start border-{{ $task['type'] }} border-3 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-start gap-3 flex-grow-1">
                                        <div class="rounded-circle bg-{{ $task['type'] }} bg-opacity-10 p-2">
                                            <i class="bi bi-{{ $task['icon'] }} fs-5 text-{{ $task['type'] }}"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $task['title'] }}</strong>
                                            <small class="text-muted">{{ $task['description'] }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="btn btn-sm btn-{{ $task['type'] }}">
                                            {{ $task['action_text'] }}
                                            <i class="bi bi-arrow-left ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success fs-1"></i>
                        <p class="text-muted mt-3 mb-0">✓ ممتاز! كل شيء تمام</p>
                        <small class="text-muted">لا توجد طلبات أو منتجات تحتاج مراجعة</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- أكثر المنتجات مبيعاً -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">الأكثر مبيعاً (30 يوم)</h6>
            </div>
            <div class="card-body">
                @if($stats['top_products']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stats['top_products'] as $product)
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex align-items-center">
                                    @if($product->main_image)
                                        <img src="{{ $product->image_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small fw-bold">{{ Str::limit($product->name, 20) }}</p>
                                        <small class="text-muted">{{ $product->order_items_count }} مبيع</small>
                                    </div>
                                    <span class="badge bg-success">{{ number_format($product->price, 0) }} ج.م</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted my-4">لا توجد مبيعات حتى الآن</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- الطلبات الأخيرة -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    <strong>الطلبات الأخيرة</strong>
                </h6>
                <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>
                    عرض الكل
                </a>
            </div>
            <div class="card-body p-0">
                @if($stats['recent_orders']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_orders'] as $order)
                            <a href="{{ route('admin.orders.show', $order) }}" class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                        <div>
                                            <strong class="d-block">#{{ $order->order_number ?? $order->id }}</strong>
                                            <small class="text-muted">{{ $order->customer_name ?? 'عميل غير معروف' }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-end">
                                            <strong class="d-block">{{ number_format($order->total, 0) }} ج.م</strong>
                                            <small class="text-muted" dir="rtl">{{ $order->created_at->locale('ar')->translatedFormat('j F Y') }}</small>
                                        </div>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">جديد</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">قيد المراجعة</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">مكتمل</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">ملغي</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                        @endif
                                        <i class="bi bi-chevron-left text-muted"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-cart-x text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h6 class="text-muted">لا توجد طلبات حتى الآن</h6>
                        <p class="text-muted small mb-0">الطلبات الجديدة ستظهر هنا</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
