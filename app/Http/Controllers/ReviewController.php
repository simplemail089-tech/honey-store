<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
        ], [
            'product_id.required' => 'المنتج مطلوب',
            'product_id.exists' => 'المنتج غير موجود',
            'rating.required' => 'التقييم مطلوب',
            'rating.integer' => 'التقييم يجب أن يكون رقماً',
            'rating.min' => 'التقييم يجب أن يكون 1 على الأقل',
            'rating.max' => 'التقييم يجب ألا يزيد عن 5',
            'customer_name.required' => 'الاسم مطلوب',
            'customer_name.max' => 'الاسم يجب ألا يزيد عن 255 حرف',
            'customer_email.email' => 'البريد الإلكتروني غير صحيح',
            'title.max' => 'عنوان التقييم يجب ألا يزيد عن 255 حرف',
            'comment.required' => 'التعليق مطلوب',
            'comment.min' => 'التعليق يجب أن يكون 10 أحرف على الأقل',
        ]);

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('product_id', $validated['product_id'])
            ->where(function($query) use ($validated) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('customer_email', $validated['customer_email']);
                }
            })
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا المنتج مسبقاً'
            ], 422);
        }

        $review = ProductReview::create([
            'product_id' => $validated['product_id'],
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_approved' => false, // يحتاج موافقة من Dashboard
        ]);

        return response()->json([
            'success' => true,
            'message' => 'شكراً لتقييمك! سيتم مراجعته قريباً'
        ]);
    }

    /**
     * Get reviews for a product
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'average_rating' => $product->average_rating,
            'total_reviews' => $product->reviews_count,
        ]);
    }
}
