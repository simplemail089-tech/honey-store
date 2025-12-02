<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'button_text',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Scope للحصول على السلايدرات النشطة فقط
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope للترتيب
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
}
