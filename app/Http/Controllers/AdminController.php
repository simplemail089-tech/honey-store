<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Slider;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Admin access required');
            }
            return $next($request);
        });
    }

    // Dashboard الرئيسية
    public function dashboard()
    {
        // المبيعات والإيرادات
        $todaySales = Order::whereDate('created_at', today())->sum('total');
        $yesterdaySales = Order::whereDate('created_at', today()->subDay())->sum('total');
        $monthSales = Order::whereMonth('created_at', now()->month)->sum('total');
        $lastMonthSales = Order::whereMonth('created_at', now()->subMonth()->month)->sum('total');

        // الطلبات
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();

        // المخزون
        $lowStockProducts = Product::where('stock', '<=', 5)->where('stock', '>', 0)->get();
        $outOfStockProducts = Product::where('stock', 0)->get();
        
        // أكثر المنتجات مبيعاً (آخر 30 يوم)
        $topProducts = Product::withCount(['orderItems' => function($q) {
            $q->whereHas('order', function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            });
        }])
        ->having('order_items_count', '>', 0)
        ->orderBy('order_items_count', 'desc')
        ->take(5)
        ->get();

        // مبيعات آخر 7 أيام
        $last7DaysSales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $last7DaysSales[] = [
                'date' => $date->format('m/d'),
                'sales' => Order::whereDate('created_at', $date)->sum('total'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // أحدث الطلبات
        $recentOrders = Order::latest()->take(10)->get();

        $stats = [
            // مبيعات
            'today_sales' => $todaySales,
            'yesterday_sales' => $yesterdaySales,
            'month_sales' => $monthSales,
            'last_month_sales' => $lastMonthSales,
            'sales_growth' => $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales * 100) : 0,
            'month_growth' => $lastMonthSales > 0 ? (($monthSales - $lastMonthSales) / $lastMonthSales * 100) : 0,
            
            // طلبات
            'today_orders' => $todayOrders,
            'pending_orders' => $pendingOrders,
            'processing_orders' => $processingOrders,
            'total_orders' => Order::count(),
            
            // مخزون
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'total_products' => Product::count(),
            
            // أخرى
            'top_products' => $topProducts,
            'last_7_days_sales' => $last7DaysSales,
            'recent_orders' => $recentOrders,
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // إدارة المنتجات
    public function products(Request $request)
    {
        $query = Product::with('category');

        // بحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلتر حسب الفئة
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // فلتر حسب الحالة
        if ($request->filled('status')) {
            $isActive = $request->status == 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:max_width=2000,max_height=2000',
            // 🔒 FIX FILE UPLOAD: إضافة validation للصور الإضافية
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:max_width=2000,max_height=2000',
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'price.required' => 'سعر المنتج مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'price.min' => 'السعر يجب أن يكون صفر أو أكثر',
            'category_id.required' => 'الفئة مطلوبة',
            'category_id.exists' => 'الفئة المختارة غير موجودة',
            'stock.integer' => 'المخزون يجب أن يكون رقماً صحيحاً',
            'stock.min' => 'المخزون لا يمكن أن يكون سالباً',
            'main_image.image' => 'الملف يجب أن يكون صورة',
            'main_image.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, أو webp',
            'main_image.max' => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
            'main_image.dimensions' => 'أبعاد الصورة يجب ألا تتجاوز 2000x2000 بكسل. الصورة الحالية كبيرة جداً، يرجى تصغيرها.',
        ]);

        try {
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $path = $image->store('products', 'public');
                
                // تحسين الصورة إذا كانت كبيرة
                $this->optimizeImage(storage_path('app/public/' . $path));
                
                $validated['main_image'] = $path;
            }

            // معالجة الصور الإضافية
            if ($request->hasFile('images')) {
                $additionalImages = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $this->optimizeImage(storage_path('app/public/' . $path));
                    $additionalImages[] = $path;
                }
                $validated['images'] = $additionalImages; // Laravel سيحولها تلقائياً لـ JSON
            }

            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            
            Product::create($validated);
            
            return redirect()->route('admin.products')->with('success', '✅ تم إضافة المنتج بنجاح!');
        } catch (\Exception $e) {
            \Log::error('خطأ في إضافة المنتج: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء إضافة المنتج: ' . $e->getMessage());
        }
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:max_width=2000,max_height=2000',
            // 🔒 FIX FILE UPLOAD: validation كامل للصور الإضافية
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:max_width=2000,max_height=2000',
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'price.required' => 'سعر المنتج مطلوب',
            'category_id.required' => 'الفئة مطلوبة',
            'main_image.dimensions' => 'أبعاد الصورة يجب ألا تتجاوز 2000x2000 بكسل. الصورة الحالية كبيرة جداً، يرجى تصغيرها.',
        ]);

        try {
            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    \Storage::disk('public')->delete($product->main_image);
                }
                $image = $request->file('main_image');
                $path = $image->store('products', 'public');
                
                // تحسين الصورة
                $this->optimizeImage(storage_path('app/public/' . $path));
                
                $validated['main_image'] = $path;
            }

            // معالجة الصور الإضافية
            $currentImages = is_array($product->images) ? $product->images : [];
            
            // حذف الصور المحددة للحذف
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $index => $shouldDelete) {
                    if ($shouldDelete == '1' && isset($currentImages[$index])) {
                        \Storage::disk('public')->delete($currentImages[$index]);
                        unset($currentImages[$index]);
                    }
                }
                $currentImages = array_values($currentImages); // إعادة ترتيب المفاتيح
            }
            
            // إضافة صور جديدة
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $this->optimizeImage(storage_path('app/public/' . $path));
                    $currentImages[] = $path;
                }
            }
            
            $validated['images'] = $currentImages; // Laravel سيحولها تلقائياً لـ JSON

            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            
            $product->update($validated);
            
            return redirect()->route('admin.products')->with('success', '✅ تم تحديث المنتج بنجاح!');
        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث المنتج: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ حدث خطأ أثناء تحديث المنتج.');
        }
    }

    public function destroyProduct(Product $product)
    {
        try {
            if ($product->main_image) {
                \Storage::disk('public')->delete($product->main_image);
            }
            
            $productName = $product->name;
            $product->delete();
            
            return redirect()->route('admin.products')->with('success', '✅ تم حذف المنتج "' . $productName . '" بنجاح!');
        } catch (\Exception $e) {
            \Log::error('خطأ في حذف المنتج: ' . $e->getMessage());
            return redirect()->route('admin.products')->with('error', '❌ حدث خطأ أثناء حذف المنتج.');
        }
    }

    // إدارة الفئات
    public function categories()
    {
        $categories = Category::withCount('products')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'اسم الفئة مطلوب',
            'name.unique' => 'اسم الفئة موجود مسبقاً',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المقبولة: jpeg, png, jpg, webp',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
        ]);

        try {
            // رفع الصورة
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('categories', $filename, 'public');
                $validated['image'] = 'storage/' . $path;
            }
            
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            
            Category::create($validated);
            
            return redirect()->route('admin.categories')->with('success', '✅ تم إضافة الفئة بنجاح!');
        } catch (\Exception $e) {
            \Log::error('خطأ في إضافة الفئة: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ حدث خطأ أثناء إضافة الفئة.');
        }
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'اسم الفئة مطلوب',
            'name.unique' => 'اسم الفئة موجود مسبقاً',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المقبولة: jpeg, png, jpg, webp',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
        ]);

        try {
            // رفع الصورة الجديدة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('categories', $filename, 'public');
                $validated['image'] = 'storage/' . $path;
            }
            
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            
            $category->update($validated);
            
            return redirect()->route('admin.categories')->with('success', '✅ تم تحديث الفئة بنجاح!');
        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث الفئة: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', '❌ حدث خطأ أثناء تحديث الفئة.');
        }
    }

    public function destroyCategory(Category $category)
    {
        try {
            if ($category->products()->count() > 0) {
                return redirect()->route('admin.categories')->with('error', '⚠️ لا يمكن حذف الفئة لأنها تحتوي على منتجات. قم بحذف أو نقل المنتجات أولاً.');
            }
            
            $categoryName = $category->name;
            $category->delete();
            
            return redirect()->route('admin.categories')->with('success', '✅ تم حذف الفئة "' . $categoryName . '" بنجاح!');
        } catch (\Exception $e) {
            \Log::error('خطأ في حذف الفئة: ' . $e->getMessage());
            return redirect()->route('admin.categories')->with('error', '❌ حدث خطأ أثناء حذف الفئة.');
        }
    }

    // إدارة الطلبات
    public function orders(Request $request)
    {
        $query = Order::with('user');

        // بحث (رقم الطلب أو اسم العميل)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        // فلتر حسب الحالة (افتراضياً pending)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        // فلتر حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            $order->update(['status' => $validated['status']]);
            return redirect()->back()->with('success', '✅ تم تحديث حالة الطلب بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ حدث خطأ أثناء تحديث الحالة.');
        }
    }

    // إدارة السلايدر
    public function sliders()
    {
        $sliders = Slider::ordered()->paginate(15);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function createSlider()
    {
        return view('admin.sliders.create');
    }

    public function storeSlider(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:8192|dimensions:min_width=1200,min_height=400,max_width=2500,max_height=1500',
            'link' => 'nullable|url',
            'button_text' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer',
        ], [
            'title.required' => 'عنوان السلايد مطلوب',
            'title.max' => 'عنوان السلايد يجب ألا يتجاوز 255 حرف',
            'image.required' => 'صورة السلايد مطلوبة',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, أو webp',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 8 ميجابايت',
            'image.dimensions' => 'أبعاد صورة السلايدر يجب أن تكون بين 1200x400 و 2500x1500 بكسل. الصورة الحالية: غير مناسبة.',
            'link.url' => 'الرابط يجب أن يكون صحيحاً (مثال: https://example.com)',
            'button_text.max' => 'نص الزر يجب ألا يتجاوز 50 حرف',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً',
        ]);

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('sliders', 'public');
                
                // تحسين الصورة
                $this->optimizeImage(storage_path('app/public/' . $path));
                
                $validated['image'] = $path;
            }

            // معالجة is_active بشكل منفصل
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['button_text'] = $validated['button_text'] ?? 'تسوق الآن';
            
            Slider::create($validated);
            
            return redirect()->route('admin.sliders')->with('success', '✅ تم إضافة السلايد بنجاح! يمكنك الآن مشاهدته في الصفحة الرئيسية.');
        } catch (\Exception $e) {
            \Log::error('خطأ في إضافة السلايد: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء حفظ السلايد: ' . $e->getMessage());
        }
    }

    public function editSlider(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function updateSlider(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192|dimensions:min_width=1200,min_height=400,max_width=2500,max_height=1500',
            'link' => 'nullable|url',
            'button_text' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer',
        ], [
            'title.required' => 'عنوان السلايد مطلوب',
            'title.max' => 'عنوان السلايد يجب ألا يتجاوز 255 حرف',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, أو webp',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 8 ميجابايت',
            'image.dimensions' => 'أبعاد صورة السلايدر يجب أن تكون بين 1200x400 و 2500x1500 بكسل.',
            'link.url' => 'الرابط يجب أن يكون صحيحاً (مثال: https://example.com)',
            'button_text.max' => 'نص الزر يجب ألا يتجاوز 50 حرف',
            'display_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً',
        ]);

        try {
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($slider->image) {
                    \Storage::disk('public')->delete($slider->image);
                }
                $image = $request->file('image');
                $path = $image->store('sliders', 'public');
                
                // تحسين الصورة
                $this->optimizeImage(storage_path('app/public/' . $path));
                
                $validated['image'] = $path;
            }

            // معالجة is_active بشكل منفصل
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['button_text'] = $validated['button_text'] ?? 'تسوق الآن';
            
            $slider->update($validated);
            
            return redirect()->route('admin.sliders')->with('success', '✅ تم تحديث السلايد بنجاح! التغييرات ظاهرة الآن في الصفحة الرئيسية.');
        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث السلايد: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء تحديث السلايد: ' . $e->getMessage());
        }
    }

    public function destroySlider(Slider $slider)
    {
        try {
            // حذف الصورة
            if ($slider->image) {
                \Storage::disk('public')->delete($slider->image);
            }
            
            $sliderTitle = $slider->title;
            $slider->delete();
            
            return redirect()->route('admin.sliders')->with('success', '✅ تم حذف السلايد "' . $sliderTitle . '" بنجاح!');
        } catch (\Exception $e) {
            return redirect()->route('admin.sliders')->with('error', '❌ حدث خطأ أثناء حذف السلايد. الرجاء المحاولة مرة أخرى.');
        }
    }
    
    /**
     * تحسين الصورة وتقليل حجمها
     */
    private function optimizeImage($path)
    {
        try {
            // التحقق من وجود GD extension
            if (!extension_loaded('gd')) {
                \Log::warning('GD extension غير مفعلة - تم تخطي تحسين الصورة');
                return;
            }
            
            // التحقق من وجود الملف
            if (!file_exists($path)) {
                return;
            }
            
            // الحصول على معلومات الصورة
            $imageInfo = @getimagesize($path);
            if (!$imageInfo) {
                return;
            }
            
            list($width, $height, $type) = $imageInfo;
            
            // تحميل الصورة حسب نوعها
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $image = @imagecreatefromjpeg($path);
                    break;
                case IMAGETYPE_PNG:
                    $image = @imagecreatefrompng($path);
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagecreatefromwebp')) {
                        $image = @imagecreatefromwebp($path);
                    } else {
                        return;
                    }
                    break;
                default:
                    return;
            }
            
            if (!$image) {
                return;
            }
            
            // إعادة حفظ الصورة بجودة أفضل (تقليل الحجم)
            switch ($type) {
                case IMAGETYPE_JPEG:
                    @imagejpeg($image, $path, 85); // جودة 85%
                    break;
                case IMAGETYPE_PNG:
                    @imagepng($image, $path, 8); // ضغط مستوى 8
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagewebp')) {
                        @imagewebp($image, $path, 85); // جودة 85%
                    }
                    break;
            }
            
            @imagedestroy($image);
            
        } catch (\Exception $e) {
            \Log::error('خطأ في تحسين الصورة: ' . $e->getMessage());
        }
    }

    // ==========================================
    // إدارة التقييمات
    // ==========================================
    
    public function reviews(Request $request)
    {
        $query = ProductReview::with(['product', 'user']);

        // فلتر حسب حالة الموافقة
        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status == 'pending') {
                $query->where('is_approved', false);
            }
        }

        // فلتر حسب المنتج
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // فلتر حسب التقييم
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $products = Product::select('id', 'name')->get();

        return view('admin.reviews.index', compact('reviews', 'products'));
    }

    public function approveReview(ProductReview $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()->back()->with('success', '✅ تم الموافقة على التقييم!');
    }

    public function rejectReview(ProductReview $review)
    {
        $review->update(['is_approved' => false]);
        return redirect()->back()->with('success', '❌ تم رفض التقييم!');
    }

    // ==========================================
    // Product Variants Management
    // ==========================================

    /**
     * Show variants for a product
     */
    public function productVariants(Product $product)
    {
        $variants = $product->variants()->orderBy('display_order')->get();
        return view('admin.products.variants', compact('product', 'variants'));
    }

    /**
     * Store new variant
     */
    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_default' => 'nullable|boolean',
        ], [
            'size.required' => 'الحجم مطلوب',
            'price.required' => 'السعر مطلوب',
            'stock.required' => 'المخزون مطلوب',
        ]);

        // If this is set as default, remove default from others
        if ($request->is_default) {
            $product->variants()->update(['is_default' => false]);
        }

        // Get max display_order
        $maxOrder = $product->variants()->max('display_order') ?? 0;

        $product->variants()->create([
            'size' => $validated['size'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'is_default' => $request->is_default ?? false,
            'display_order' => $maxOrder + 1,
        ]);

        return redirect()->back()->with('success', '✅ تم إضافة الحجم بنجاح!');
    }

    /**
     * Update variant
     */
    public function updateVariant(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from others
        if ($request->is_default) {
            $variant->product->variants()->where('id', '!=', $variant->id)->update(['is_default' => false]);
        }

        $variant->update([
            'size' => $validated['size'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->back()->with('success', '✅ تم تحديث الحجم بنجاح!');
    }

    /**
     * Delete variant
     */
    public function deleteVariant(ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->back()->with('success', '❌ تم حذف الحجم!');
    }

    // ==========================================
    // Coupons Management
    // ==========================================

    /**
     * Display coupons list
     */
    public function coupons()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show create coupon form
     */
    public function createCoupon()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store new coupon
     */
    public function storeCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'كود الكوبون مطلوب',
            'code.unique' => 'كود الكوبون موجود بالفعل',
            'type.required' => 'نوع الخصم مطلوب',
            'value.required' => 'قيمة الخصم مطلوبة',
            'expires_at.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البداية',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        
        // إذا كان min_order_amount فارغاً، نضع 0 كقيمة افتراضية
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0;
        $validated['max_uses'] = $validated['max_uses'] ?? null;

        Coupon::create($validated);

        return redirect()->route('admin.coupons')->with('success', '✅ تم إضافة الكوبون بنجاح!');
    }

    /**
     * Show edit coupon form
     */
    public function editCoupon(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update coupon
     */
    public function updateCoupon(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        
        // إذا كان min_order_amount فارغاً، نضع 0 كقيمة افتراضية
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0;
        $validated['max_uses'] = $validated['max_uses'] ?? null;

        $coupon->update($validated);

        return redirect()->route('admin.coupons')->with('success', '✅ تم تحديث الكوبون بنجاح!');
    }

    /**
     * Delete coupon
     */
    public function destroyCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('success', '❌ تم حذف الكوبون!');
    }
}
