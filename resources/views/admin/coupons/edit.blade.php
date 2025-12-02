@extends('admin.layout')

@section('title', 'تعديل الكوبون')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>تعديل الكوبون: {{ $coupon->code }}</h2>
        <a href="{{ route('admin.coupons') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-2"></i>
            رجوع للقائمة
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">كود الكوبون <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase;">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">نوع الخصم <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>خصم ثابت (ج.م)</option>
                            <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">قيمة الخصم <span class="text-danger">*</span></label>
                        <input type="number" name="value" step="0.01" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $coupon->value) }}" required>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">الحد الأدنى للطلب (ج.م)</label>
                        <input type="number" name="min_order_amount" step="0.01" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                        @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">الحد الأقصى للاستخدام</label>
                        <input type="number" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror" value="{{ old('max_uses', $coupon->max_uses) }}">
                        <small class="text-muted">المستخدم حالياً: {{ $coupon->uses_count ?? 0 }}</small>
                        @error('max_uses')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">تاريخ البداية</label>
                        <input type="date" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at', $coupon->starts_at ? \Carbon\Carbon::parse($coupon->starts_at)->format('Y-m-d') : '') }}">
                        @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">تاريخ الانتهاء</label>
                        <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') }}">
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">
                                نشط
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i>
                        حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.coupons') }}" class="btn btn-secondary px-4">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
