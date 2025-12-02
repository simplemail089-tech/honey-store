<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // الصفحة الرئيسية
    public function index()
    {
        $sliders = Slider::active()->ordered()->get();
        
        // الأكثر مبيعاً - المنتجات التي لديها مبيعات فعلية أو مميزة كـ best seller
        $bestSellers = Product::where('is_active', true)
            ->where(function($q) {
                $q->where('sales_count', '>', 0)
                  ->orWhere('is_best_seller', true);
            })
            ->with(['category', 'variants'])
            ->orderByDesc('sales_count')
            ->orderByDesc('is_best_seller')
            ->take(8)
            ->get();

        // إذا لم يوجد منتجات بمبيعات، نعرض أحدث المنتجات
        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('is_active', true)
                ->with(['category', 'variants'])
                ->latest()
                ->take(8)
                ->get();
        }

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('display_order')
            ->get();

        return view('shop.index', compact('sliders', 'bestSellers', 'categories'));
    }

    // صفحة المنتجات
    public function products(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'variants']);

        // فلترة حسب الفئة
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // الترتيب
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->withCount('products')->get();

        return view('shop.products', compact('products', 'categories'));
    }

    // صفحة تفاصيل المنتج
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'variants']);

        // منتجات مشابهة
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // التقييمات المعتمدة
        $reviews = $product->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // إحصائيات التقييمات
        $reviewStats = [
            'average' => round($product->average_rating, 1),
            'total' => $product->reviews_count,
            'distribution' => []
        ];

        // توزيع النجوم
        for ($i = 5; $i >= 1; $i--) {
            $count = $product->reviews()->approved()->where('rating', $i)->count();
            $percentage = $product->reviews_count > 0 ? round(($count / $product->reviews_count) * 100) : 0;
            $reviewStats['distribution'][$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return view('shop.show', compact('product', 'relatedProducts', 'reviews', 'reviewStats'));
    }

    // API: Get product for Quick View
    public function getProduct($id)
    {
        $product = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->findOrFail($id);
        
        return response()->json($product);
    }

    // API: Live Search
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->with('category')
            ->take(8)
            ->get(['id', 'name', 'price', 'main_image', 'category_id']);
        
        return response()->json($products);
    }

    // صفحة من نحن
    public function about()
    {
        return view('shop.about');
    }

}
