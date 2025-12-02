@extends('admin.layout')

@section('title', 'إدارة الفئات')
@section('page-title', 'إدارة الفئات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">قائمة الفئات</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        إضافة فئة جديدة
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>عدد المنتجات</th>
                            <th>الحالة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    @if($category->image)
                                        @php
                                            if (str_starts_with($category->image, 'http')) {
                                                $imageSrc = $category->image;
                                            } elseif (str_starts_with($category->image, 'storage/')) {
                                                $imageSrc = asset($category->image);
                                            } else {
                                                $imageSrc = asset('storage/' . $category->image);
                                            }
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="{{ $category->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ Str::limit($category->description, 60) }}</td>
                                <td>{{ $category->products_count ?? 0 }}</td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">نشطة</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشطة</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" id="delete-form-{{ $category->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger" title="حذف" onclick="showConfirmModal('حذف الفئة', 'هل أنت متأكد من حذف فئة {{ $category->name }}؟', () => document.getElementById('delete-form-{{ $category->id }}').submit())">
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
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags fs-1 text-muted"></i>
                <p class="text-muted mt-3 mb-1">لا توجد فئات حتى الآن</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                    إضافة أول فئة
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
