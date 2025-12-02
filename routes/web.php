<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CouponController;

// الواجهة الأمامية للمتجر
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/products', [ShopController::class, 'products'])->name('products');
Route::get('/products/{product:id}', [ShopController::class, 'show'])->name('products.show');

// صفحة من نحن
Route::get('/about', [ShopController::class, 'about'])->name('about');

// API Routes
Route::get('/api/products/{id}', [ShopController::class, 'getProduct'])->name('api.products.show');
Route::get('/api/search', [ShopController::class, 'search'])->name('api.search');
Route::get('/api/cart', [CartController::class, 'getCartData'])->name('api.cart');

// مسارات السلة
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear'); // قبل {item}
    Route::put('/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
});

// مسارات التقييمات
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::post('/', [ReviewController::class, 'store'])->name('store');
    Route::get('/product/{product}', [ReviewController::class, 'index'])->name('index');
});

// مسارات المفضلة (Wishlist)
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add', [WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [WishlistController::class, 'remove'])->name('remove');
    Route::get('/check', [WishlistController::class, 'check'])->name('check');
    Route::get('/count', [WishlistController::class, 'count'])->name('count');
});

// مسارات الكوبونات
Route::prefix('coupon')->name('coupon.')->group(function () {
    Route::post('/validate', [CouponController::class, 'validateCoupon'])->name('validate');
    Route::post('/remove', [CouponController::class, 'remove'])->name('remove');
});

// مسارات الطلبات
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/process', [OrderController::class, 'store'])->name('process');
});
Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');

// مسارات التوثيق
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// مسارات لوحة التحكم (الأدمن فقط) - محمية بـ Middleware
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // المسار الرئيسي يوجه إلى dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // إدارة المنتجات
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    
    // إدارة الفئات
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    
    // إدارة الطلبات
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // إدارة السلايدر
    Route::get('/sliders', [AdminController::class, 'sliders'])->name('sliders');
    Route::get('/sliders/create', [AdminController::class, 'createSlider'])->name('sliders.create');
    Route::post('/sliders', [AdminController::class, 'storeSlider'])->name('sliders.store');
    Route::get('/sliders/{slider}/edit', [AdminController::class, 'editSlider'])->name('sliders.edit');
    Route::put('/sliders/{slider}', [AdminController::class, 'updateSlider'])->name('sliders.update');
    Route::delete('/sliders/{slider}', [AdminController::class, 'destroySlider'])->name('sliders.destroy');
    
    // إدارة التقييمات
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::post('/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminController::class, 'rejectReview'])->name('reviews.reject');
    
    // الأحجام (Variants)
    Route::get('/products/{product}/variants', [AdminController::class, 'productVariants'])->name('products.variants');
    Route::post('/products/{product}/variants', [AdminController::class, 'storeVariant'])->name('variants.store');
    Route::put('/variants/{variant}', [AdminController::class, 'updateVariant'])->name('variants.update');
    Route::delete('/variants/{variant}', [AdminController::class, 'deleteVariant'])->name('variants.delete');
    
    // الكوبونات (Coupons)
    Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons');
    Route::get('/coupons/create', [AdminController::class, 'createCoupon'])->name('coupons.create');
    Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [AdminController::class, 'editCoupon'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [AdminController::class, 'updateCoupon'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [AdminController::class, 'destroyCoupon'])->name('coupons.destroy');
});
