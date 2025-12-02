@extends('admin.layout')

@section('title', 'إدارة السلايدر')
@section('page-title', 'إدارة السلايدر')

@section('content')
<div class="alert alert-dark alert-dismissible fade show" role="alert" style="border-right: 4px solid #D4A017;">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-lightbulb-fill" style="font-size: 1.5rem; color: #D4A017;"></i>
        <div>
            <h5 class="alert-heading mb-2">
                <i class="bi bi-stars me-2" style="color: #D4A017;"></i>
                تصميم Hero Section الجديد - 2025
            </h5>
            <p class="mb-2">
                <strong>التصميم الحالي:</strong> Hero Section بخلفية سينمائية داكنة تعرض صورة السلايدر كاملة مع نص أبيض قوي فوقها.
            </p>
            <ul class="mb-2" style="font-size: 0.95rem;">
                <li><strong>الصورة:</strong> تُعرض كخلفية كاملة بتأثير Overlay داكن</li>
                <li><strong>النص:</strong> أبيض مع ظل قوي للوضوح التام</li>
                <li><strong>الأبعاد المثالية:</strong> <code>1920x800 بكسل</code></li>
                <li><strong>نصيحة:</strong> اختر صور واضحة وجذابة لأن التصميم سينمائي احترافي</li>
            </ul>
            <p class="mb-0 text-muted small">
                <i class="bi bi-check-circle me-1"></i>
                التصميم متزامن مع Frontend - أي تغيير هنا يظهر مباشرة
            </p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">قائمة السلايدات</h4>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        إضافة سلايد جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($sliders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="100">الصورة</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>الرابط</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th class="text-center" width="150">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                                </td>
                                <td>{{ $slider->title }}</td>
                                <td>{{ Str::limit($slider->description, 50) }}</td>
                                <td>
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" target="_blank" class="text-primary">
                                            <i class="bi bi-link-45deg"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $slider->display_order }}</td>
                                <td>
                                    @if($slider->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-outline-primary" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" class="d-inline" id="delete-slider-{{ $slider->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger" title="حذف" onclick="showConfirmModal('حذف السلايد', 'هل أنت متأكد من حذف هذا السلايد؟', () => document.getElementById('delete-slider-{{ $slider->id }}').submit())">
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
                {{ $sliders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-images" style="font-size: 4rem; color: #ddd;"></i>
                <p class="mt-3 text-muted">لا توجد سلايدات حالياً</p>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                    إضافة أول سلايد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
