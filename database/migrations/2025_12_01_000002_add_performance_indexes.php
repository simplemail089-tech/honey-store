<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة Indexes لتحسين الأداء
     */
    public function up(): void
    {
        // Cart Items - يتم البحث فيه بـ session_id كثيراً
        // session_id index قد يكون موجوداً بالفعل، لذا نتخطى الأخطاء
        try {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->index(['user_id', 'product_id', 'variant_id'], 'cart_items_compound_index');
            });
        } catch (\Exception $e) {
            // Index already exists, skip
        }

        // Orders - للفلترة السريعة في Dashboard
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        // Products - يستخدم في كل الاستعلامات
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['is_active', 'is_featured']);
            $table->index(['is_active', 'sales_count']);
        });
        
        // Product Variants - للبحث السريع
        Schema::table('product_variants', function (Blueprint $table) {
            $table->index(['product_id', 'stock']);
        });
        
        // Coupons - للبحث بـ code
        Schema::table('coupons', function (Blueprint $table) {
            $table->index(['code', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['session_id']);
            $table->dropIndex(['user_id', 'product_id', 'variant_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['is_active', 'sales_count']);
        });
        
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'stock']);
        });
        
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['code', 'is_active']);
        });
    }
};
