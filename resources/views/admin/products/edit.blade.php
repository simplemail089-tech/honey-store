@extends('admin.layout')

@section('title', 'تعديل المنتج')
@section('page-title', 'تعديل المنتج')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المنتج</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">السعر (جنيه مصري)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">المخزون</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror">
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
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
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
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" @checked(old('is_active', $product->is_active))>
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
أجود أنواع العسل الطبيعي">{{ old('description', $product->description) }}</textarea>
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
- التخزين: في مكان جاف وبارد">{{ old('specifications', $product->specifications) }}</textarea>
                @error('specifications')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    اكتب كل مواصفة في سطر منفصل (سيتم عرضها في صفحة المنتج)
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الصورة الرئيسية</label>
                @if($product->main_image)
                    <div class="mb-2">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width: 200px; height: auto; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية</small>
                @error('main_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">صور إضافية</label>
                
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <div class="mb-3">
                        <label class="small text-muted">الصور الحالية:</label>
                        <div class="row g-2">
                            @foreach($product->images as $index => $image)
                                <div class="col-auto">
                                    <div class="position-relative">
                                        <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" 
                                             alt="صورة {{ $index + 1 }}" 
                                             class="rounded"
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <button type="button" 
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                onclick="showConfirmModal('حذف الصورة', 'هل تريد حذف هذه الصورة؟', () => { document.getElementById('delete-image-{{ $index }}').value = '1'; this.parentElement.remove(); })"
                                                style="font-size: 10px; padding: 2px 6px;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <input type="hidden" name="delete_images[{{ $index }}]" id="delete-image-{{ $index }}" value="0">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <input type="file" name="images[]" id="additionalImagesInput" multiple class="form-control @error('images.*') is-invalid @enderror" accept="image/*" onchange="previewAdditionalImages(this)">
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>استخدم Ctrl+Click</strong> لاختيار عدة صور جديدة دفعة واحدة. الصور الحالية لن تُحذف إلا إذا ضغطت على زر (X).
                </div>
                <!-- معاينة الصور الجديدة -->
                <div id="additionalImagesPreview" style="display: none; margin-top: 1rem;">
                    <label class="small text-muted mb-2">معاينة الصور الجديدة:</label>
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
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // معاينة الصور الإضافية الجديدة
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
                    img.alt = 'صورة جديدة ' + (index + 1);
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.borderRadius = '8px';
                    img.style.border = '2px solid #28a745';
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
