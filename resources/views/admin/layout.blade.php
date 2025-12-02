<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'لوحة التحكم') - رحيق</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --border-radius: 8px;
            --spacing-sm: 16px;
            --spacing-md: 24px;
            --spacing-lg: 32px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 2px 8px rgba(0,0,0,0.1);
            --shadow-hover: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        body {
            background-color: var(--bg-body);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: #111827; /* أزرق داكن هادئ */
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        
        .main-content {
            min-height: 100vh;
            padding: 0;
        }
        
        .topbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 30px;
            margin-bottom: 30px;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
            background: var(--bg-card);
        }
        
        .card-header {
            background: var(--bg-card) !important;
            border-bottom: 1px solid #f0f0f0;
            padding: var(--spacing-sm);
        }
        
        .card-body {
            padding: var(--spacing-md);
        }
        
        /* Hover только для clickable cards */
        a.card:hover,
        .list-group-item-action:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-1px);
        }
        
        .stat-card {
            background-color: #ffffff;
            border-left: 4px solid #3b82f6; /* أزرق هادئ */
            color: #111827;
        }
        
        .stat-card.products {
            border-left-color: #10b981; /* أخضر هادئ */
        }
        
        .stat-card.categories {
            border-left-color: #f59e0b; /* برتقالي هادئ */
        }
        
        .stat-card.orders {
            border-left-color: #6366f1; /* بنفسجي هادئ */
        }
        
        /* Sidebar Sections */
        .nav-section-title {
            padding: 8px 20px 4px;
            margin-top: 8px;
        }
        
        .nav-section-title:first-child {
            margin-top: 0;
        }
        
        /* Global Search */
        .search-box {
            position: relative;
        }
        
        .search-box .form-control:focus {
            border-color: #D4A017;
            box-shadow: 0 0 0 0.2rem rgba(212, 160, 23, 0.1);
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050;
            margin-top: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .search-result-item:hover {
            background: #f8f9fa;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        /* Notifications */
        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
        }
        
        /* Empty States */
        .empty-state {
            text-align: center;
            padding: var(--spacing-lg);
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: var(--spacing-sm);
        }
        
        .empty-state h6 {
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            font-size: 0.875rem;
            margin-bottom: 0;
        }
        
        /* Spacing System */
        .gap-sm { gap: var(--spacing-sm) !important; }
        .gap-md { gap: var(--spacing-md) !important; }
        .gap-lg { gap: var(--spacing-lg) !important; }
        
        .p-sm { padding: var(--spacing-sm) !important; }
        .p-md { padding: var(--spacing-md) !important; }
        .p-lg { padding: var(--spacing-lg) !important; }
        
        .mb-sm { margin-bottom: var(--spacing-sm) !important; }
        .mb-md { margin-bottom: var(--spacing-md) !important; }
        .mb-lg { margin-bottom: var(--spacing-lg) !important; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-white text-center mb-4">
                    <i class="bi bi-shop"></i>
                    رحيق
                </h4>
                
                <nav class="nav flex-column">
                    <!-- العمليات اليومية -->
                    <div class="nav-section-title">
                        <small class="text-white-50 fw-bold">العمليات اليومية</small>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-house-door me-2"></i>
                            لوحة التحكم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders', ['status' => 'pending']) }}">
                            <i class="bi bi-cart-check me-2"></i>
                            الطلبات
                            @php
                                $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                            @endphp
                            @if($pendingOrders > 0)
                                <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size: 0.7rem;">{{ $pendingOrders }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- المخزون -->
                    <div class="nav-section-title mt-3">
                        <small class="text-white-50 fw-bold">المخزون</small>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                            <i class="bi bi-box-seam me-2"></i>
                            المنتجات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                            <i class="bi bi-grid me-2"></i>
                            الفئات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}" href="{{ route('admin.coupons') }}">
                            <i class="bi bi-tag me-2"></i>
                            الكوبونات
                        </a>
                    </li>
                    
                    <!-- العملاء -->
                    <div class="nav-section-title mt-3">
                        <small class="text-white-50 fw-bold">العملاء</small>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews') }}">
                            <i class="bi bi-star me-2"></i>
                            التقييمات
                            @php
                                $pendingReviews = \App\Models\ProductReview::where('is_approved', false)->count();
                            @endphp
                            @if($pendingReviews > 0)
                                <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.7rem;">{{ $pendingReviews }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- المحتوى -->
                    <div class="nav-section-title mt-3">
                        <small class="text-white-50 fw-bold">المحتوى</small>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}" href="{{ route('admin.sliders') }}">
                            <i class="bi bi-images me-2"></i>
                            السلايدر
                        </a>
                    </li>
                </nav>
                
                <div class="mt-auto pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Bar -->
                <div class="topbar">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@yield('page-title', 'لوحة التحكم')</h5>
                        <div class="d-flex align-items-center gap-3">
                            <!-- Global Search -->
                            <div class="search-box">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="search" 
                                           class="form-control border-start-0" 
                                           id="globalSearch"
                                           placeholder="ابحث عن طلب، منتج، عميل... (Ctrl+K)"
                                           style="width: 300px;">
                                </div>
                                <div id="searchResults" class="search-results" style="display: none;"></div>
                            </div>
                            
                            <!-- Notifications -->
                            <div class="dropdown">
                                <button class="btn btn-link position-relative p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-bell fs-5 text-muted"></i>
                                    @php
                                        $lowStockCount = \App\Models\Product::where('stock', '>', 0)
                                                                              ->where('stock', '<=', 5)
                                                                              ->count();
                                        $outOfStockCount = \App\Models\Product::where('stock', 0)->count();
                                        $totalAlerts = $lowStockCount + $outOfStockCount;
                                    @endphp
                                    @if($totalAlerts > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                            {{ $totalAlerts }}
                                        </span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 320px;">
                                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                                        <strong>تنبيهات المخزون</strong>
                                        <span class="badge bg-danger rounded-pill">{{ $totalAlerts }}</span>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if($outOfStockCount > 0)
                                        @php
                                            $outOfStockProducts = \App\Models\Product::where('stock', 0)->take(3)->get();
                                        @endphp
                                        @foreach($outOfStockProducts as $product)
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('admin.products.edit', $product) }}">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-x-circle text-danger me-2"></i>
                                                        <div>
                                                            <small class="fw-bold">{{ Str::limit($product->name, 25) }}</small><br>
                                                            <small class="text-danger">نفذ من المخزون</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                    @if($lowStockCount > 0)
                                        @php
                                            $lowStockProducts = \App\Models\Product::where('stock', '>', 0)
                                                                                     ->where('stock', '<=', 5)
                                                                                     ->take(3)
                                                                                     ->get();
                                        @endphp
                                        @foreach($lowStockProducts as $product)
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('admin.products.edit', $product) }}">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                                        <div>
                                                            <small class="fw-bold">{{ Str::limit($product->name, 25) }}</small><br>
                                                            <small class="text-muted">المخزون: {{ $product->stock }} فقط</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                    @if($totalAlerts == 0)
                                        <li class="dropdown-item text-center text-muted py-3">
                                            <i class="bi bi-check-circle fs-4"></i><br>
                                            <small>لا توجد تنبيهات</small>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            
                            <span class="text-muted">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Page Content -->
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="confirmModalTitle">تأكيد العملية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmModalMessage">هل أنت متأكد من هذه العملية؟</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmModalBtn">تأكيد</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="adminToast" class="toast" role="alert">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2" id="toastIcon"></i>
                <strong class="me-auto" id="toastTitle">نجاح</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastBody">
                تمت العملية بنجاح
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Global Search System
        const searchInput = document.getElementById('globalSearch');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        // Keyboard shortcut (Ctrl+K)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
            // Escape to close
            if (e.key === 'Escape') {
                searchResults.style.display = 'none';
            }
        });

        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        function performSearch(query) {
            // AJAX search
            fetch(`{{ route('admin.dashboard') }}?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
            });
        }

        function displayResults(data) {
            if (!data || (!data.orders && !data.products)) {
                searchResults.innerHTML = '<div class="search-result-item text-center text-muted">لا توجد نتائج</div>';
                searchResults.style.display = 'block';
                return;
            }

            let html = '';
            
            // Orders
            if (data.orders && data.orders.length > 0) {
                html += '<div class="px-3 py-2 bg-light"><strong class="small">الطلبات</strong></div>';
                data.orders.forEach(order => {
                    html += `
                        <a href="/admin/orders/${order.id}" class="search-result-item text-decoration-none text-dark d-block">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="small">#${order.order_number}</strong>
                                    <br><small class="text-muted">${order.customer_name}</small>
                                </div>
                                <span class="badge bg-primary">${order.total} ج.م</span>
                            </div>
                        </a>
                    `;
                });
            }
            
            // Products
            if (data.products && data.products.length > 0) {
                html += '<div class="px-3 py-2 bg-light"><strong class="small">المنتجات</strong></div>';
                data.products.forEach(product => {
                    html += `
                        <a href="/admin/products/${product.id}/edit" class="search-result-item text-decoration-none text-dark d-block">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="small">${product.name}</strong>
                                    <br><small class="text-muted">المخزون: ${product.stock ?? 'غير محدد'}</small>
                                </div>
                                <span class="badge bg-success">${product.price} ج.م</span>
                            </div>
                        </a>
                    `;
                });
            }
            
            searchResults.innerHTML = html;
            searchResults.style.display = 'block';
        }

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    
        // Confirmation Modal System
        function showConfirmModal(title, message, onConfirm) {
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;
            
            const confirmBtn = document.getElementById('confirmModalBtn');
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            newConfirmBtn.addEventListener('click', () => {
                modal.hide();
                onConfirm();
            });
            
            modal.show();
        }
        
        // Toast Notification System
        function showToast(message, type = 'success') {
            const toast = document.getElementById('adminToast');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastBody = document.getElementById('toastBody');
            
            // Set icon and title based on type
            if (type === 'success') {
                toastIcon.className = 'bi bi-check-circle-fill text-success me-2';
                toastTitle.textContent = 'نجاح';
            } else if (type === 'error') {
                toastIcon.className = 'bi bi-x-circle-fill text-danger me-2';
                toastTitle.textContent = 'خطأ';
            } else if (type === 'warning') {
                toastIcon.className = 'bi bi-exclamation-triangle-fill text-warning me-2';
                toastTitle.textContent = 'تنبيه';
            } else if (type === 'info') {
                toastIcon.className = 'bi bi-info-circle-fill text-info me-2';
                toastTitle.textContent = 'معلومة';
            }
            
            toastBody.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
        
        // Auto show toasts from session
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>
