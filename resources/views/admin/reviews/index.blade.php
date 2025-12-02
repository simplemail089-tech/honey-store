@extends('admin.layout')

@section('title', 'إدارة التقييمات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة التقييمات</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">الكل</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">المنتج</label>
                    <select name="product_id" class="form-select" onchange="this.form.submit()">
                        <option value="">الكل</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">التقييم</label>
                    <select name="rating" class="form-select" onchange="this.form.submit()">
                        <option value="">الكل</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} نجمة
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    @if(request()->anyFilled(['status', 'product_id', 'rating']))
                        <a href="{{ route('admin.reviews') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> إزالة الفلاتر
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card">
        <div class="card-body">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>المنتج</th>
                                <th>العميل</th>
                                <th>التقييم</th>
                                <th>التعليق</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $review->product) }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($review->product->name, 30) }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $review->customer_name }}</strong>
                                            @if($review->is_verified_purchase)
                                                <span class="badge bg-success" style="font-size: 0.7rem;">
                                                    <i class="bi bi-patch-check-fill"></i> موثق
                                                </span>
                                            @endif
                                        </div>
                                        @if($review->customer_email)
                                            <small class="text-muted">{{ $review->customer_email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="color: #FFB800;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-{{ $i <= $review->rating ? 'fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ $review->rating }}/5</small>
                                    </td>
                                    <td>
                                        @if($review->title)
                                            <strong>{{ Str::limit($review->title, 30) }}</strong><br>
                                        @endif
                                        <small class="text-muted">{{ Str::limit($review->comment, 60) }}</small>
                                    </td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">معتمد</span>
                                        @else
                                            <span class="badge bg-warning text-dark">قيد المراجعة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $review->created_at->format('Y-m-d') }}<br>
                                            {{ $review->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success" title="اعتماد">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline" id="delete-review-{{ $review->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" title="حذف" onclick="showConfirmModal('حذف التقييم', 'هل أنت متأكد من حذف هذا التقييم؟', () => document.getElementById('delete-review-{{ $review->id }}').submit())">
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

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text" style="font-size: 4rem; color: #ddd;"></i>
                    <p class="text-muted mt-3">لا توجد تقييمات بعد</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
