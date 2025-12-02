@extends('admin.layout')

@section('title', 'إدارة أحجام المنتج')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2">إدارة أحجام المنتج</h2>
            <p class="text-muted mb-0">
                <strong>{{ $product->name }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.products') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-2"></i>
            رجوع للمنتجات
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Add New Variant Form -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        إضافة حجم جديد
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.variants.store', $product) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">الحجم <span class="text-danger">*</span></label>
                            <input type="text" name="size" class="form-control @error('size') is-invalid @enderror" placeholder="مثال: 250 جرام" value="{{ old('size') }}" required>
                            <small class="text-muted">أمثلة: 250g, 500g, 1kg</small>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">السعر <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" placeholder="100.00" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">المخزون <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="100" value="{{ old('stock') }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_default" value="1" class="form-check-input" id="is_default" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                الحجم الافتراضي
                            </label>
                            <small class="text-muted d-block">سيظهر هذا الحجم أولاً للعملاء</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            إضافة الحجم
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Variants List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        الأحجام المتوفرة ({{ $variants->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($variants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>الحجم</th>
                                        <th>السعر</th>
                                        <th>المخزون</th>
                                        <th>الحالة</th>
                                        <th width="200">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($variants as $variant)
                                        <tr id="variant-{{ $variant->id}}">
                                            <td>
                                                <strong>{{ $variant->size }}</strong>
                                                @if($variant->is_default)
                                                    <span class="badge bg-primary ms-2">افتراضي</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-success">{{ number_format($variant->price, 2) }} ج.م</td>
                                            <td>
                                                @if($variant->stock > 10)
                                                    <span class="badge bg-success">{{ $variant->stock }}</span>
                                                @elseif($variant->stock > 0)
                                                    <span class="badge bg-warning text-dark">{{ $variant->stock }}</span>
                                                @else
                                                    <span class="badge bg-danger">نفذ</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($variant->stock > 0)
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle-fill"></i> متوفر
                                                    </span>
                                                @else
                                                    <span class="text-danger">
                                                        <i class="bi bi-x-circle-fill"></i> غير متوفر
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editVariant({{ $variant->id }}, '{{ $variant->size }}', {{ $variant->price }}, {{ $variant->stock }}, {{ $variant->is_default ? 'true' : 'false' }})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteVariant({{ $variant->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">لم يتم إضافة أي أحجام بعد</p>
                            <p class="small text-muted">ابدأ بإضافة أحجام مختلفة للمنتج (مثل: 250g, 500g, 1kg)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Variant Modal -->
<div class="modal fade" id="editVariantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الحجم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editVariantForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الحجم</label>
                        <input type="text" name="size" id="edit_size" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">السعر</label>
                        <input type="number" name="price" id="edit_price" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">المخزون</label>
                        <input type="number" name="stock" id="edit_stock" class="form-control" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" value="1" class="form-check-input" id="edit_is_default">
                        <label class="form-check-label" for="edit_is_default">
                            الحجم الافتراضي
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editVariant(id, size, price, stock, isDefault) {
        document.getElementById('edit_size').value = size;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_stock').value = stock;
        document.getElementById('edit_is_default').checked = isDefault;
        
        document.getElementById('editVariantForm').action = `/admin/variants/${id}`;
        
        new bootstrap.Modal(document.getElementById('editVariantModal')).show();
    }

    function deleteVariant(id) {
        showConfirmModal('حذف الحجم', 'هل أنت متأكد من حذف هذا الحجم؟', () => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/variants/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            
            document.body.appendChild(form);
            form.submit();
        });
    }
</script>
@endpush
