<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'uses_count',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check start date
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        // Check expiry date
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Check max uses
        if ($this->max_uses && $this->uses_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($subtotal < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        }

        // percentage
        return ($subtotal * $this->value) / 100;
    }

    /**
     * Increment uses count
     */
    public function incrementUses(): void
    {
        $this->increment('uses_count');
    }
}
