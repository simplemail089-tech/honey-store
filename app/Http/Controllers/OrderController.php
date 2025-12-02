<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض صفحة الدفع
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        
        // الشحن سيُحسب ديناميكياً بناءً على المدينة
        $shippingRates = $this->getShippingRates();
        
        return view('shop.checkout', compact('cartItems', 'subtotal', 'shippingRates'));
    }
    
    /**
     * حساب تكلفة الشحن بناءً على المدينة
     */
    private function calculateShipping($city)
    {
        $shippingRates = $this->getShippingRates();
        
        // البحث عن المدينة في جميع المحافظات
        $cityLower = mb_strtolower(trim($city));
        
        foreach ($shippingRates as $state => $cities) {
            foreach ($cities as $cityData) {
                if (mb_strtolower($cityData['name']) === $cityLower) {
                    return $cityData['cost'];
                }
            }
        }
        
        // إذا لم تُعثر على المدينة، استخدم سعر افتراضي
        return 50;
    }
    
    /**
     * الحصول على أسعار الشحن مرتبة حسب المحافظات والمدن
     */
    private function getShippingRates()
    {
        return [
            'القاهرة' => [
                ['name' => 'مدينة نصر', 'cost' => 35],
                ['name' => 'مصر الجديدة', 'cost' => 35],
                ['name' => 'المعادي', 'cost' => 35],
                ['name' => 'حلوان', 'cost' => 40],
                ['name' => 'الزمالك', 'cost' => 35],
                ['name' => 'التجمع الخامس', 'cost' => 40],
                ['name' => 'الشروق', 'cost' => 45],
            ],
            'الجيزة' => [
                ['name' => 'الدقي', 'cost' => 35],
                ['name' => 'المهندسين', 'cost' => 35],
                ['name' => 'فيصل', 'cost' => 35],
                ['name' => '6 أكتوبر', 'cost' => 40],
                ['name' => 'الشيخ زايد', 'cost' => 40],
                ['name' => 'الهرم', 'cost' => 35],
            ],
            'الإسكندرية' => [
                ['name' => 'المنتزه', 'cost' => 45],
                ['name' => 'سموحة', 'cost' => 45],
                ['name' => 'ميامي', 'cost' => 45],
                ['name' => 'العجمي', 'cost' => 50],
                ['name' => 'برج العرب', 'cost' => 50],
            ],
            'الدقهلية' => [
                ['name' => 'المنصورة', 'cost' => 40],
                ['name' => 'طلخا', 'cost' => 40],
                ['name' => 'ميت غمر', 'cost' => 45],
            ],
            'الشرقية' => [
                ['name' => 'الزقازيق', 'cost' => 40],
                ['name' => 'العاشر من رمضان', 'cost' => 40],
                ['name' => 'بلبيس', 'cost' => 45],
            ],
            'القليوبية' => [
                ['name' => 'بنها', 'cost' => 35],
                ['name' => 'شبرا الخيمة', 'cost' => 35],
                ['name' => 'القناطر الخيرية', 'cost' => 40],
            ],
            'البحيرة' => [
                ['name' => 'دمنهور', 'cost' => 45],
                ['name' => 'كفر الدوار', 'cost' => 45],
            ],
            'المنوفية' => [
                ['name' => 'شبين الكوم', 'cost' => 40],
                ['name' => 'السادات', 'cost' => 40],
            ],
            'الغربية' => [
                ['name' => 'طنطا', 'cost' => 40],
                ['name' => 'المحلة الكبرى', 'cost' => 40],
            ],
            'كفر الشيخ' => [
                ['name' => 'كفر الشيخ', 'cost' => 45],
                ['name' => 'دسوق', 'cost' => 45],
            ],
        ];
    }

    /**
     * معالجة الطلب
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address_line1' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة');
        }

        DB::beginTransaction();
        
        try {
            // ✅ FIX #1 & #2: إعادة حساب الأسعار والتحقق من المخزون من Database
            $subtotal = 0;
            $priceErrors = [];
            $stockErrors = [];
            
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $variant = $cartItem->variant;
                
                // ✅ جلب السعر الفعلي من Database
                $actualPrice = $variant ? $variant->price : $product->price;
                
                // ✅ التحقق من تطابق السعر (حماية من التلاعب)
                if (abs($actualPrice - $cartItem->unit_price) > 0.01) {
                    $priceErrors[] = $product->name;
                }
                
                // ✅ التحقق من المخزون مع قفل الصف (Row Locking)
                if ($variant) {
                    $availableStock = ProductVariant::where('id', $variant->id)
                        ->lockForUpdate()
                        ->value('stock');
                        
                    if ($availableStock === null || $availableStock < $cartItem->quantity) {
                        $stockErrors[] = "{$product->name} ({$variant->size})";
                    }
                } else {
                    $availableStock = Product::where('id', $product->id)
                        ->lockForUpdate()
                        ->value('stock');
                        
                    if ($availableStock === null || $availableStock < $cartItem->quantity) {
                        $stockErrors[] = $product->name;
                    }
                }
                
                // استخدام السعر الفعلي في الحساب
                $subtotal += $cartItem->quantity * $actualPrice;
            }
            
            // إذا كانت هناك مشاكل، إلغاء العملية
            if (!empty($priceErrors)) {
                DB::rollback();
                return back()
                    ->with('error', 'تم تحديث أسعار بعض المنتجات. الرجاء مراجعة سلة التسوق.')
                    ->withInput();
            }
            
            if (!empty($stockErrors)) {
                DB::rollback();
                return back()
                    ->with('error', 'المنتجات التالية غير متوفرة بالكمية المطلوبة: ' . implode(', ', $stockErrors))
                    ->withInput();
            }
            
            // الحصول على معلومات الكوبون من الـ Session
            $couponData = session('coupon');
            $couponId = null;
            $discount = 0;
            
            if ($couponData) {
                $couponId = $couponData['id'] ?? null;
                // ✅ إعادة حساب الخصم (لا نثق في session)
                $coupon = Coupon::find($couponId);
                if ($coupon && $coupon->isValid()) {
                    $discount = $coupon->calculateDiscount($subtotal);
                } else {
                    // الكوبون لم يعد صالحاً
                    $couponId = null;
                    $discount = 0;
                    session()->forget('coupon');
                }
            }
            
            // حساب الشحن بناءً على المدينة
            $shipping = $this->calculateShipping($request->shipping_city);
            
            // الإجمالي النهائي = المجموع الفرعي - الخصم + الشحن
            $total = $subtotal - $discount + $shipping;

            // إنشاء رقم الطلب
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // إنشاء الطلب
            $order = Order::create([
                'user_id' => Auth::id(),
                'coupon_id' => $couponId,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'shipping_total' => $shipping,
                'total' => $total,
                'currency' => 'EGP',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address_line1' => $request->shipping_address_line1,
                'shipping_address_line2' => $request->shipping_address_line2,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_country' => 'مصر',
                'notes' => $request->notes,
            ]);

            // إنشاء عناصر الطلب
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $variant = $cartItem->variant;
                
                // ✅ استخدام السعر الفعلي من Database
                $actualPrice = $variant ? $variant->price : $product->price;
                
                // بناء snapshot كامل
                $snapshot = [
                    'name' => $product->name,
                    'image' => $product->main_image,
                    'category' => $product->category->name ?? null,
                ];
                
                // إضافة معلومات الـ variant إذا كان موجوداً
                if ($variant) {
                    $snapshot['variant'] = [
                        'size' => $variant->size ?? null,
                        'price' => $variant->price ?? null,
                    ];
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $actualPrice, // ✅ السعر الفعلي
                    'total_price' => $cartItem->quantity * $actualPrice,
                    'snapshot' => $snapshot,
                ]);

                // ✅ FIX #2: خصم المخزون بأمان (مع where condition)
                if ($variant) {
                    $updated = ProductVariant::where('id', $variant->id)
                        ->where('stock', '>=', $cartItem->quantity)
                        ->decrement('stock', $cartItem->quantity);
                        
                    if (!$updated) {
                        DB::rollback();
                        return back()
                            ->with('error', "نفد المخزون من '{$product->name}' ({$variant->size}) أثناء العملية")
                            ->withInput();
                    }
                } else {
                    $updated = Product::where('id', $product->id)
                        ->where('stock', '>=', $cartItem->quantity)
                        ->decrement('stock', $cartItem->quantity);
                        
                    if (!$updated) {
                        DB::rollback();
                        return back()
                            ->with('error', "نفد المخزون من '{$product->name}' أثناء العملية")
                            ->withInput();
                    }
                }
                
                // ✅ تحديث sales_count للترتيب حسب الأكثر مبيعاً
                $product->increment('sales_count', $cartItem->quantity);
            }

            // ✅ FIX #3: تحديث عدد استخدامات الكوبون
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->incrementUses();
                }
            }

            // تفريغ السلة
            $this->clearCart();
            
            // مسح الكوبون من الـ Session بعد استخدامه
            session()->forget('coupon');

            DB::commit();

            // تحضير رسالة واتساب
            $whatsappMessage = $this->prepareWhatsAppMessage($order);
            // ✅ FIX #4: استخدام config بدلاً من hardcoded value
            $whatsappNumber = config('app.whatsapp_number');
            
            return redirect()->route('order.success', $order->id)
                ->with('whatsapp_message', $whatsappMessage)
                ->with('whatsapp_number', $whatsappNumber);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating order: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إنشاء الطلب. الرجاء المحاولة مرة أخرى')->withInput();
        }
    }

    /**
     * صفحة نجاح الطلب
     */
    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // 🔒 FIX IDOR: التحقق الآمن من ملكية الطلب
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');
        
        // التحقق من أن الطلب يخص المستخدم الحالي (مسجل أو guest)
        if ($userId) {
            // مستخدم مسجل: يجب أن يكون الطلب له
            if ($order->user_id !== $userId) {
                abort(403, 'غير مصرح لك بعرض هذا الطلب');
            }
        } else {
            // guest: يجب أن يكون session_id متطابق
            // نضيف عمود session_id للـ orders table في migration منفصل
            // للآن نمنع الوصول تماماً للـ guests بعد تسجيل الخروج
            abort(403, 'يجب تسجيل الدخول لعرض تفاصيل الطلب');
        }
        
        return view('shop.order-success', compact('order'));
    }

    /**
     * الحصول على عناصر السلة
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');

        // ✅ FIX #5: إضافة 'variant' لحل N+1 Query Problem
        return CartItem::with(['product.category', 'variant'])
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
     * تفريغ السلة
     */
    private function clearCart()
    {
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');

        CartItem::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();
    }

    /**
     * تحضير رسالة واتساب
     */
    private function prepareWhatsAppMessage($order)
    {
        $message = "🍯 *طلب جديد من رحيق*\n\n";
        $message .= "📋 *رقم الطلب:* {$order->order_number}\n";
        $message .= "👤 *العميل:* {$order->customer_name}\n";
        $message .= "📱 *الهاتف:* {$order->customer_phone}\n";
        
        if ($order->customer_email) {
            $message .= "📧 *البريد:* {$order->customer_email}\n";
        }
        
        $message .= "\n📍 *عنوان التوصيل:*\n";
        $message .= "{$order->shipping_address_line1}\n";
        if ($order->shipping_address_line2) {
            $message .= "{$order->shipping_address_line2}\n";
        }
        $message .= "{$order->shipping_city}, {$order->shipping_country}\n";
        
        $message .= "\n🛍️ *المنتجات:*\n";
        foreach ($order->items as $item) {
            $message .= "• {$item->snapshot['name']} × {$item->quantity} = {$item->total_price} ج.م\n";
        }
        
        $message .= "\n💰 *الفاتورة:*\n";
        $message .= "المجموع الفرعي: {$order->subtotal} ج.م\n";
        $message .= "الشحن: {$order->shipping_total} ج.م\n";
        $message .= "*الإجمالي: {$order->total} ج.م*\n";
        
        if ($order->notes) {
            $message .= "\n📝 *ملاحظات:*\n{$order->notes}\n";
        }
        
        $message .= "\n✅ *الطلب قيد المعالجة وسيتم التواصل معك قريباً*";
        
        return urlencode($message);
    }
}
