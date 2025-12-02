@extends('admin.layout')

@section('title', 'تعديل الفئة')
@section('page-title', 'تعديل الفئة')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">اسم الفئة</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $category->display_order) }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" @checked(old('is_active', $category->is_active))>
                        <label class="form-check-label" for="is_active">الفئة نشطة</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">صورة الفئة</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    المقاس المثالي: 400×400 بكسل | الحد الأقصى: 2MB | الصيغ المقبولة: JPG, PNG, WebP
                </div>
                @if($category->image)
                    <div class="mt-2">
                        <p class="small text-muted">الصورة الحالية:</p>
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="rounded" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                    </div>
                @endif
            </div>

            @include('admin.partials.image-guidelines')

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                    رجوع إلى قائمة الفئات
                </a>
                <button type="submit" class="btn btn-primary">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
