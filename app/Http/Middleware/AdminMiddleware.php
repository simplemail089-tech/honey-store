<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * التحقق من أن المستخدم أدمن
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول للوصول إلى لوحة التحكم');
        }

        // التحقق من صلاحية الأدمن
        if (auth()->user()->role !== 'admin') {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
