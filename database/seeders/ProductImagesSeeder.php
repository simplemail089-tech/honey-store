<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->get();
        
        // صور عسل حقيقية من Unsplash (عالية الجودة)
        $honeyImages = [
            'https://images.unsplash.com/photo-1587049352846-4a222e784587?w=1000&h=1000&fit=crop&q=80', // عسل في برطمان
            'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=1000&h=1000&fit=crop&q=80', // عسل مع ملعقة خشبية
            'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=1000&h=1000&fit=crop&q=80', // عسل طبيعي
            'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=1000&h=1000&fit=crop&q=80', // برطمان عسل
            'https://images.unsplash.com/photo-1533985624488-88a164c90f1e?w=1000&h=1000&fit=crop&q=80', // عسل مع خلية
            'https://images.unsplash.com/photo-1600788907416-456578634209?w=1000&h=1000&fit=crop&q=80', // عسل ذهبي
            'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=1000&h=1000&fit=crop&q=80', // عسل في وعاء
            'https://images.unsplash.com/photo-1606312617993-c4f01c229e49?w=1000&h=1000&fit=crop&q=80', // عسل طبيعي
            'https://images.unsplash.com/photo-1619880278993-c2bfd5f45c4e?w=1000&h=1000&fit=crop&q=80', // عسل نحل
        ];

        foreach ($products as $index => $product) {
            $mainImage = $honeyImages[$index % count($honeyImages)];
            
            // معرض صور (4 صور مختلفة)
            $gallery = [
                $honeyImages[($index + 1) % count($honeyImages)],
                $honeyImages[($index + 2) % count($honeyImages)],
                $honeyImages[($index + 3) % count($honeyImages)],
                $honeyImages[($index + 4) % count($honeyImages)],
            ];
            
            DB::table('products')->where('id', $product->id)->update([
                'main_image' => $mainImage,
                'images' => json_encode($gallery)
            ]);
        }

        $this->command->info('✅ تم تحديث ' . $products->count() . ' منتج بصور العسل الحقيقية');
    }
}
