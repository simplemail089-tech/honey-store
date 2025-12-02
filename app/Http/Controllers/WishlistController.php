<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    /**
     * Get wishlist items
     */
    public function index()
    {
        $wishlistItems = $this->getWishlistItems();
        
        $products = Product::whereIn('id', $wishlistItems->pluck('product_id'))
            ->where('is_active', true)
            ->get();
        
        return view('shop.wishlist', compact('products', 'wishlistItems'));
    }

    /**
     * Add to wishlist
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        
        // Check if product exists and is active
        $product = Product::where('id', $productId)->where('is_active', true)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير متوفر'
            ], 404);
        }

        if (Auth::check()) {
            // User is logged in
            $exists = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج موجود بالفعل في المفضلة'
                ]);
            }

            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
        } else {
            // Guest user
            $sessionId = $this->getOrCreateSessionId();
            
            $exists = Wishlist::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج موجود بالفعل في المفضلة'
                ]);
            }

            Wishlist::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المنتج إلى المفضلة',
            'count' => $this->getWishlistCount()
        ]);
    }

    /**
     * Remove from wishlist
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;

        if (Auth::check()) {
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $sessionId = session('wishlist_session_id');
            if ($sessionId) {
                Wishlist::where('session_id', $sessionId)
                    ->where('product_id', $productId)
                    ->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تمت إزالة المنتج من المفضلة',
            'count' => $this->getWishlistCount()
        ]);
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request)
    {
        $productId = $request->product_id;
        $inWishlist = false;

        if (Auth::check()) {
            $inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->exists();
        } else {
            $sessionId = session('wishlist_session_id');
            if ($sessionId) {
                $inWishlist = Wishlist::where('session_id', $sessionId)
                    ->where('product_id', $productId)
                    ->exists();
            }
        }

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        return response()->json([
            'count' => $this->getWishlistCount()
        ]);
    }

    /**
     * Helper: Get or create session ID for guests
     */
    private function getOrCreateSessionId()
    {
        if (!session()->has('wishlist_session_id')) {
            session(['wishlist_session_id' => Str::uuid()->toString()]);
        }
        return session('wishlist_session_id');
    }

    /**
     * Helper: Get wishlist items
     */
    private function getWishlistItems()
    {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())->get();
        } else {
            $sessionId = session('wishlist_session_id');
            if ($sessionId) {
                return Wishlist::where('session_id', $sessionId)->get();
            }
            return collect([]);
        }
    }

    /**
     * Helper: Get wishlist count
     */
    private function getWishlistCount()
    {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())->count();
        } else {
            $sessionId = session('wishlist_session_id');
            if ($sessionId) {
                return Wishlist::where('session_id', $sessionId)->count();
            }
            return 0;
        }
    }
}
