<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة حقل icon للأقسام
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('description');
        });

        // إضافة حقل is_new للمنتجات
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_new')->default(false)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_new');
        });
    }
};
