<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * الحصول على session_id للسلة
     */
    private function getCartSessionId()
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }
        return Session::get('cart_session_id');
    }

    /**
     * عرض صفحة السلة
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        
        // الشحن يُحسب في صفحة الدفع بعد اختيار المنطقة
        $shipping = 0;
        $total = $subtotal;

        return view('shop.cart', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * إضافة منتج للسلة
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        $price = $product->price;
        $availableStock = $product->stock;

        // If variant is selected
        if ($request->variant_id) {
            $variant = ProductVariant::where('id', $request->variant_id)
                ->where('product_id', $product->id)
                ->firstOrFail();
            
            $price = $variant->price;
            $availableStock = $variant->stock;
        }

        // التحقق من المخزون
        if ($availableStock !== null && $availableStock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة في المخزون'
            ], 400);
        }

        $userId = Auth::id();
        $sessionId = $this->getCartSessionId();

        // البحث عن المنتج في السلة (نفس المنتج + نفس الـ variant)
        $cartItem = CartItem::where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($cartItem) {
            // تحديث الكمية
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // التحقق من المخزون مرة أخرى
            if ($availableStock !== null && $availableStock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة تتجاوز المخزون المتاح'
                ], 400);
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // إضافة منتج جديد
            $cartItem = CartItem::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'unit_price' => $price,
            ]);
        }

        // حساب عدد العناصر في السلة
        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج إلى السلة بنجاح',
            'cartCount' => $cartCount,
            'item' => [
                'id' => $cartItem->id,
                'product_name' => $product->name,
                'variant' => $variant ? $variant->size : null,
                'quantity' => $cartItem->quantity,
                'unit_price' => $price,
                'total' => $cartItem->quantity * $price,
            ]
        ]);
    }

    /**
     * تحديث كمية منتج في السلة
     */
    public function update(Request $request, $itemId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $userId = Auth::id();
            $sessionId = $this->getCartSessionId();

            $cartItem = CartItem::where('id', $itemId)
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->with(['product', 'variant'])
                ->firstOrFail();

            $product = $cartItem->product;
            
            // التحقق من المخزون (variant أو product)
            $availableStock = $cartItem->variant ? $cartItem->variant->stock : $product->stock;
            
            if ($availableStock !== null && $availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة غير متوفرة في المخزون. المتوفر: ' . $availableStock
                ], 200);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            // إعادة حساب المجموع
            $itemTotal = $cartItem->quantity * $cartItem->unit_price;
            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            
            $shipping = 0;
            $total = $subtotal;

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية بنجاح',
                'itemTotal' => number_format($itemTotal, 2),
                'subtotal' => number_format($subtotal, 2),
                'total' => number_format($total, 2),
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث السلة'
            ], 200);
        }
    }

    /**
     * حذف منتج من السلة
     */
    public function remove($itemId)
    {
        try {
            $userId = Auth::id();
            $sessionId = $this->getCartSessionId();

            $cartItem = CartItem::where('id', $itemId)
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->firstOrFail();

            $cartItem->delete();

            // إعادة حساب المجموع
            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            
            $shipping = 0;
            $total = $subtotal;
            $cartCount = $this->getCartCount();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج من السلة',
                'cartCount' => $cartCount,
                'subtotal' => number_format($subtotal, 2),
                'total' => number_format($total, 2),
                'isEmpty' => $cartItems->isEmpty(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart remove error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج'
            ], 200);
        }
    }

    /**
     * تفريغ السلة
     */
    public function clear()
    {
        try {
            $userId = Auth::id();
            $sessionId = $this->getCartSessionId();

            CartItem::where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم تفريغ السلة بنجاح',
                'cartCount' => 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart clear error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تفريغ السلة'
            ], 200);
        }
    }

    /**
     * الحصول على عدد العناصر في السلة
     */
    public function count()
    {
        $cartCount = $this->getCartCount();
        
        return response()->json([
            'success' => true,
            'count' => $cartCount,
        ]);
    }

    /**
     * الحصول على عناصر السلة
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = $this->getCartSessionId();

        return CartItem::with(['product', 'variant'])
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
    }

    /**
     * الحصول على عدد العناصر في السلة
     */
    private function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = $this->getCartSessionId();

        return CartItem::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->sum('quantity');
    }

    /**
     * الحصول على بيانات السلة للـ API (Cart Sidebar)
     */
    public function getCartData()
    {
        try {
            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            
            // Get coupon discount if exists
            $discount = session('coupon_discount', 0);
            $total = $subtotal - $discount;
            
            return response()->json([
                'success' => true,
                'items' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id ?? null,
                            'name' => $item->product->name ?? 'منتج محذوف',
                            'main_image' => $item->product->main_image ?? null,
                        ],
                        'variant_name' => $item->variant ? $item->variant->size_name : null,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->quantity * $item->unit_price,
                    ];
                }),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'count' => $cartItems->sum('quantity'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحميل السلة',
                'items' => [],
                'total' => 0,
            ], 500);
        }
    }
}
