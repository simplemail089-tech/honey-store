<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // مشاركة الأقسام مع جميع الصفحات للـ Mega Menu
        View::composer('shop.layout', function ($view) {
            $menuCategories = Category::where('is_active', true)
                ->withCount('products')
                ->orderBy('display_order')
                ->limit(8)
                ->get();
            
            $view->with('menuCategories', $menuCategories);
        });
    }
}
