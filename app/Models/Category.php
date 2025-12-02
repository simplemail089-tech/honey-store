<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'display_order',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the full URL for the category image
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            // إذا كانت URL كاملة (من Unsplash مثلاً)
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            // إذا كانت من storage
            return asset('storage/' . $this->image);
        }
        // صورة افتراضية
        return 'https://via.placeholder.com/400x400/FFF8E7/D4A017?text=فئة';
    }
}
