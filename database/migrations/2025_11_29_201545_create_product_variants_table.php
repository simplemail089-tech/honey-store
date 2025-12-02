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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size'); // 250g, 500g, 1kg, etc.
            $table->decimal('price', 10, 2); // سعر هذا الحجم
            $table->integer('stock')->default(0); // مخزون هذا الحجم
            $table->boolean('is_default')->default(false); // الحجم الافتراضي
            $table->integer('display_order')->default(0); // ترتيب العرض
            $table->timestamps();
            
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
