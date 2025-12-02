@extends('admin.layout')

@section('title', 'إدارة الكوبونات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة الكوبونات</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            إضافة كوبون جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($coupons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>الكود</th>
                                <th>النوع</th>
                                <th>القيمة</th>
                                <th>الحد الأدنى</th>
                                <th>الاستخدامات</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الحالة</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $coupon->code }}</strong>
                                    </td>
                                    <td>
                                        @if($coupon->type == 'fixed')
                                            <span class="badge bg-success">خصم ثابت</span>
                                        @else
                                            <span class="badge bg-info">نسبة مئوية</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">
                                        @if($coupon->type == 'fixed')
                                            {{ number_format($coupon->value, 2) }} ج.م
                                        @else
                                            {{ $coupon->value }}%
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->min_order_amount)
                                            {{ number_format($coupon->min_order_amount, 2) }} ج.م
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $coupon->uses_count ?? 0 }} 
                                        @if($coupon->max_uses)
                                            / {{ $coupon->max_uses }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->expires_at)
                                            {{ \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') }}
                                            @if(\Carbon\Carbon::parse($coupon->expires_at)->isPast())
                                                <span class="badge bg-danger">منتهي</span>
                                            @endif
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->is_active && (!$coupon->expires_at || !\Carbon\Carbon::parse($coupon->expires_at)->isPast()))
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline" id="delete-coupon-{{ $coupon->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger" onclick="showConfirmModal('حذف الكوبون', 'هل أنت متأكد من حذف الكوبون {{ $coupon->code }}؟', () => document.getElementById('delete-coupon-{{ $coupon->id }}').submit())">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $coupons->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-tag display-1 text-muted"></i>
                    <p class="text-muted mt-3">لا توجد كوبونات بعد</p>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle me-2"></i>
                        إضافة أول كوبون
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
