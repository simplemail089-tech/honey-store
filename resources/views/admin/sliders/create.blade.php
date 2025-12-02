@extends('admin.layout')

@section('title', 'إضافة سلايد جديد')
@section('page-title', 'إضافة سلايد جديد')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="bi bi-exclamation-triangle me-2"></i>
            فشل في إضافة السلايد
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
        <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data" id="sliderForm">
            @csrf

            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label">عنوان السلايد</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" class="form-control">
                    <small class="text-muted">الأقل رقماً يُعرض أولاً</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label">رابط السلايد (اختياري)</label>
                    <input type="url" name="link" value="{{ old('link') }}" class="form-control @error('link') is-invalid @enderror" placeholder="https://example.com/offer">
                    <small class="text-muted">الرابط الذي سينتقل له المستخدم عند الضغط على السلايد</small>
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">نص الزر</label>
                    <input type="text" name="button_text" value="{{ old('button_text', 'تسوق الآن') }}" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">صورة السلايد (مطلوبة)</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                <small class="text-muted">يُفضل أن تكون الصورة بأبعاد 1920x800 بكسل. الحد الأقصى: 2 ميجابايت</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                <!-- معاينة الصورة -->
                <div class="mt-3">
                    <img id="imagePreview" src="" alt="معاينة" style="max-width: 100%; height: auto; display: none; border-radius: 8px; border: 2px solid #ddd;">
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                    <label class="form-check-label" for="is_active">السلايد نشط</label>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.sliders') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right me-1"></i>
                    رجوع
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-check-lg me-1"></i>
                    <span class="btn-text">حفظ السلايد</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('sliderForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    // التحقق من الحقول المطلوبة
    const title = document.querySelector('input[name="title"]').value.trim();
    const image = document.querySelector('input[name="image"]').files.length;
    
    if (!title) {
        e.preventDefault();
        alert('⚠️ الرجاء إدخال عنوان السلايد');
        return false;
    }
    
    if (!image) {
        e.preventDefault();
        alert('⚠️ الرجاء اختيار صورة للسلايد');
        return false;
    }
    
    // تعطيل الزر وإظهار التحميل
    submitBtn.disabled = true;
    btnText.textContent = 'جاري الحفظ...';
    spinner.classList.remove('d-none');
});

// التحقق من حجم الصورة
document.querySelector('input[name="image"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // بالـ MB
        if (fileSize > 2) {
            alert('⚠️ حجم الصورة كبير جداً! الحد الأقصى 2 ميجابايت');
            this.value = '';
            return false;
        }
        
        // عرض معاينة الصورة
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('imagePreview');
            if (preview) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
