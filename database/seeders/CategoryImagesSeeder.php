<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryImagesSeeder extends Seeder
{
    public function run(): void
    {
        // صور الفئات من Unsplash (أيقونات جميلة متعلقة بالعسل)
        $categoryImages = [
            'عسل السدر' => 'https://images.unsplash.com/photo-1587049352846-4a222e784587?w=400&h=400&fit=crop&q=80',
            'عسل الزهور' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=400&h=400&fit=crop&q=80',
            'عسل الجبلي' => 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400&h=400&fit=crop&q=80',
            'عسل القطن' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=400&fit=crop&q=80',
            'منتجات النحل' => 'https://images.unsplash.com/photo-1533985624488-88a164c90f1e?w=400&h=400&fit=crop&q=80',
        ];

        $categories = DB::table('categories')->get();

        foreach ($categories as $category) {
            // استخدام صورة مناسبة حسب اسم الفئة أو صورة افتراضية
            $image = $categoryImages[$category->name] ?? 'https://images.unsplash.com/photo-1587049352846-4a222e784587?w=400&h=400&fit=crop&q=80';
            
            DB::table('categories')
                ->where('id', $category->id)
                ->update(['image' => $image]);
        }

        $this->command->info('✅ تم تحديث ' . $categories->count() . ' فئة بالصور');
    }
}
