<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'specifications',
        'price',
        'compare_at_price',
        'stock',
        'min_stock_threshold',
        'is_active',
        'is_featured',
        'is_best_seller',
        'main_image',
        'images',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'images' => 'array',
        'attributes' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->approved()->avg('rating') ?: 0;
    }

    /**
     * Get reviews count
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->approved()->count();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('display_order');
    }

    /**
     * Check if product has variants
     */
    public function hasVariants(): bool
    {
        return $this->variants()->count() > 0;
    }

    /**
     * Get default variant or first variant
     */
    public function getDefaultVariant()
    {
        return $this->variants()->where('is_default', true)->first() 
            ?? $this->variants()->first();
    }

    /**
     * Get the full URL for the main image
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->main_image) {
            // إذا كانت URL كاملة (من Unsplash مثلاً)
            if (str_starts_with($this->main_image, 'http')) {
                return $this->main_image;
            }
            // إذا كانت من storage
            return asset('storage/' . $this->main_image);
        }
        // صورة افتراضية
        return 'https://via.placeholder.com/300x300/FFF8E7/D4A017?text=عسل';
    }
}
