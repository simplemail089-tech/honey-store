@extends('admin.layout')

@section('title', 'إضافة فئة جديدة')
@section('page-title', 'إضافة فئة جديدة')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">اسم الفئة</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">ترتيب العرض (اختياري)</label>
                    <input type="number" name="display_order" value="{{ old('display_order') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="is_active" class="form-select">
                        <option value="1" @selected(old('is_active', '1') == '1')>نشطة</option>
                        <option value="0" @selected(old('is_active') == '0')>غير نشطة</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف (اختياري)</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
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
            </div>

            @include('admin.partials.image-guidelines')

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                    رجوع إلى قائمة الفئات
                </a>
                <button type="submit" class="btn btn-primary">
                    حفظ الفئة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
