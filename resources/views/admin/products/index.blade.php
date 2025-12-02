@extends('admin.layout')

@section('title', 'إدارة المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">قائمة المنتجات</h5>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        إضافة منتج جديد
    </a>
</div>

<!-- تم إزالة نظام البحث القديم - استخدم Global Search في الأعلى (Ctrl+K) -->

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
            <!-- عدد النتائج -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">
                    <i class="bi bi-box me-1"></i>
                    عرض {{ $products->count() }} من أصل {{ $products->total() }} منتج
                </p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>المخزون</th>
                            <th>الحالة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.products.edit', $product) }}'">
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->main_image)
                                        @if(str_starts_with($product->main_image, 'http'))
                                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ optional($product->category)->name ?? '-' }}</td>
                                <td>{{ number_format($product->price, 2) }} ج.م</td>
                                <td>{{ $product->stock ?? '-' }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td class="text-center" onclick="event.stopPropagation();">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.products.variants', $product) }}" class="btn btn-outline-success" title="إدارة الأحجام">
                                            <i class="bi bi-boxes me-1"></i>الحجم
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" id="delete-product-{{ $product->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger" title="حذف" onclick="showConfirmModal('حذف المنتج', 'هل أنت متأكد من حذف {{ $product->name }}؟', () => document.getElementById('delete-product-{{ $product->id }}').submit())">
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
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-box fs-1 text-muted"></i>
                <p class="text-muted mt-3 mb-1">لا توجد منتجات حتى الآن</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    إضافة أول منتج
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
