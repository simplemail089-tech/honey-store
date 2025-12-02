@extends('admin.layout')

@section('title', 'إضافة منتج جديد')
@section('page-title', 'إضافة منتج جديد')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="bi bi-exclamation-triangle me-2"></i>
            فشل في إضافة المنتج
        </h5>
        <hr>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المنتج</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">السعر (جنيه مصري)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">المخزون</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" class="form-control @error('stock') is-invalid @enderror">
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">الفئة</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">اختر الفئة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">الحالة</label>
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                        <label class="form-check-label" for="is_active">المنتج نشط</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف (نقاط بيعية)</label>
                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="اكتب كل نقطة في سطر جديد:
عسل طبيعي 100%
مفيد للمناعة
خالي من السكر المضاف
أجود أنواع العسل الطبيعي">{{ old('description') }}</textarea>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    اكتب كل ميزة في سطر جديد لتظهر كنقاط مع علامة ✓ ذهبية
                </small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">المواصفات (اختياري)</label>
                <textarea name="specifications" rows="4" class="form-control @error('specifications') is-invalid @enderror" placeholder="مثال:
- الوزن: 500 جرام
- المنشأ: عسل طبيعي 100%
- الصلاحية: سنتان
- التخزين: في مكان جاف وبارد">{{ old('specifications') }}</textarea>
                @error('specifications')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    اكتب كل مواصفة في سطر منفصل (سيتم عرضها في صفحة المنتج)
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الصورة الرئيسية (اختياري)</label>
                <input type="file" name="main_image" id="mainImageInput" class="form-control @error('main_image') is-invalid @enderror" accept="image/*" onchange="previewMainImage(this)">
                @error('main_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    هذه هي الصورة الأساسية التي ستظهر أولاً
                </div>
                <!-- معاينة الصورة الرئيسية -->
                <div id="mainImagePreview" style="display: none; margin-top: 1rem;">
                    <div style="position: relative; display: inline-block;">
                        <img id="mainPreviewImg" src="" alt="معاينة" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #ddd;">
                        <button type="button" onclick="clearMainImage()" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer;">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">صور إضافية (اختياري - يمكنك اختيار أكثر من صورة)</label>
                <input type="file" name="images[]" id="additionalImagesInput" multiple class="form-control @error('images.*') is-invalid @enderror" accept="image/*" onchange="previewAdditionalImages(this)">
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>استخدم Ctrl+Click</strong> لاختيار عدة صور دفعة واحدة. ستظهر جميعها في معرض الصور.
                </div>
                <!-- معاينة الصور الإضافية -->
                <div id="additionalImagesPreview" style="display: none; margin-top: 1rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;" id="additionalPreviewContainer">
                        <!-- سيتم إضافة الصور هنا -->
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                    رجوع إلى قائمة المنتجات
                </a>
                <button type="submit" class="btn btn-primary">
                    حفظ المنتج
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // معاينة الصورة الرئيسية
    function previewMainImage(input) {
        const preview = document.getElementById('mainImagePreview');
        const previewImg = document.getElementById('mainPreviewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // مسح الصورة الرئيسية
    function clearMainImage() {
        document.getElementById('mainImageInput').value = '';
        document.getElementById('mainImagePreview').style.display = 'none';
    }
    
    // معاينة الصور الإضافية
    function previewAdditionalImages(input) {
        const previewContainer = document.getElementById('additionalPreviewContainer');
        const preview = document.getElementById('additionalImagesPreview');
        
        // مسح المعاينات السابقة
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            preview.style.display = 'block';
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.style.position = 'relative';
                    imgWrapper.style.display = 'inline-block';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'صورة ' + (index + 1);
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';
                    img.style.borderRadius = '8px';
                    img.style.border = '2px solid #ddd';
                    img.style.objectFit = 'cover';
                    
                    imgWrapper.appendChild(img);
                    previewContainer.appendChild(imgWrapper);
                }
                
                reader.readAsDataURL(file);
            });
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
