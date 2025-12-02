<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'rating',
        'title',
        'comment',
        'is_approved',
        'is_verified_purchase',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_verified_purchase' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Product relationship
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * User relationship (optional)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: approved reviews only
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: verified purchases only
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }
}
