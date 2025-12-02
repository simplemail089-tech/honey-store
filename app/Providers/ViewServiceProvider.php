<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // مشاركة عدد عناصر السلة مع جميع الصفحات
        View::composer('*', function ($view) {
            $cartCount = $this->getCartCount();
            $view->with('cartCount', $cartCount);
        });
    }

    private function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');

        if (!$userId && !$sessionId) {
            return 0;
        }

        return CartItem::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->sum('quantity');
    }
}
