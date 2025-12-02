<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validate and apply coupon
     */
    public function validateCoupon(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'subtotal' => 'required|numeric|min:0'
            ], [
                'code.required' => 'الرجاء إدخال كود الكوبون',
                'subtotal.required' => 'خطأ في حساب الإجمالي'
            ]);

            $coupon = Coupon::where('code', strtoupper($request->code))->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الكوبون غير صحيح'
                ], 200); // Changed to 200 to prevent browser errors
            }

            if (!$coupon->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا الكوبون غير صالح أو منتهي الصلاحية'
                ], 200);
            }

            $subtotal = floatval($request->subtotal);

            if ($subtotal < $coupon->min_order_amount) {
                return response()->json([
                    'success' => false,
                    'message' => sprintf(
                        'الحد الأدنى للطلب لاستخدام هذا الكوبون هو %.2f ج.م',
                        $coupon->min_order_amount
                    )
                ], 200);
            }

            $discount = $coupon->calculateDiscount($subtotal);

            // Store coupon in session
            session([
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'discount' => $discount
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تطبيق الكوبون بنجاح!',
                'coupon' => [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'discount' => $discount
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $e->validator->errors()->all())
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Coupon validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الكوبون. الرجاء المحاولة مرة أخرى'
            ], 200);
        }
    }

    /**
     * Remove coupon from session
     */
    public function remove()
    {
        session()->forget('coupon');

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة الكوبون'
        ]);
    }
}
