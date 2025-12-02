<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CompleteDataSeeder extends Seeder
{
    public function run(): void
    {
        // تعطيل فحص الـ foreign keys مؤقتاً
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // تنظيف البيانات القديمة
        Slider::truncate();
        Product::truncate();
        Category::truncate();
        
        // إعادة تفعيل فحص الـ foreign keys
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // إنشاء المستخدم الأدمن
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'المدير',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // إنشاء الفئات مع الأيقونات
        $categories = [
            [
                'name' => 'عسل طبيعي',
                'slug' => 'natural-honey',
                'description' => 'عسل طبيعي 100% من أجود أنواع النحل',
                'icon' => 'droplet-fill',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'عسل السدر',
                'slug' => 'sidr-honey',
                'description' => 'عسل السدر الفاخر من جبال اليمن',
                'icon' => 'stars',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'عسل الزهور',
                'slug' => 'flower-honey',
                'description' => 'عسل زهور متنوعة من الحقول الطبيعية',
                'icon' => 'flower1',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'منتجات النحل',
                'slug' => 'bee-products',
                'description' => 'منتجات طبيعية من خلية النحل',
                'icon' => 'shop',
                'is_active' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // إنشاء المنتجات
        $products = [
            // عسل طبيعي
            [
                'name' => 'عسل نحل طبيعي - 500 جرام',
                'slug' => 'natural-honey-500g',
                'description' => 'عسل نحل طبيعي 100% من أجود المناحل المحلية، غني بالفيتامينات والمعادن',
                'price' => 250,
                'category_id' => 1,
                'stock' => 50,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=800&h=800&fit=crop',
            ],
            [
                'name' => 'عسل نحل جبلي - 1 كيلو',
                'slug' => 'mountain-honey-1kg',
                'description' => 'عسل جبلي نادر من المرتفعات، ذو فوائد صحية عالية',
                'price' => 480,
                'category_id' => 1,
                'stock' => 30,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&h=800&fit=crop',
            ],
            
            // عسل السدر
            [
                'name' => 'عسل السدر اليمني الفاخر - 500 جرام',
                'slug' => 'yemeni-sidr-honey-500g',
                'description' => 'عسل السدر الأصلي من اليمن، يعتبر من أغلى وأجود أنواع العسل في العالم',
                'price' => 850,
                'category_id' => 2,
                'stock' => 20,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=800&h=800&fit=crop',
            ],
            [
                'name' => 'عسل السدر الجبلي - 1 كيلو',
                'slug' => 'mountain-sidr-honey-1kg',
                'description' => 'عسل سدر جبلي نقي، معروف بخصائصه العلاجية المميزة',
                'price' => 1650,
                'category_id' => 2,
                'stock' => 15,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1516714819001-8ee7a13b71d7?w=800&h=800&fit=crop',
            ],
            
            // عسل الزهور
            [
                'name' => 'عسل الزهور البرية - 500 جرام',
                'slug' => 'wild-flower-honey-500g',
                'description' => 'عسل زهور برية متنوعة، طعم مميز ورائحة زكية',
                'price' => 180,
                'category_id' => 3,
                'stock' => 60,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1575014562837-c3e0b1080a69?w=800&h=800&fit=crop',
            ],
            [
                'name' => 'عسل البرسيم - 750 جرام',
                'slug' => 'clover-honey-750g',
                'description' => 'عسل برسيم طبيعي، خفيف المذاق ومفيد للصحة',
                'price' => 280,
                'category_id' => 3,
                'stock' => 45,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1600788907416-456578634209?w=800&h=800&fit=crop',
            ],
            
            // منتجات النحل
            [
                'name' => 'غذاء ملكات النحل - 100 جرام',
                'slug' => 'royal-jelly-100g',
                'description' => 'غذاء ملكات طبيعي، مقوي عام ومنشط للحيوية',
                'price' => 350,
                'category_id' => 4,
                'stock' => 25,
                'is_active' => true,
                'is_featured' => true,
                'main_image' => 'https://images.unsplash.com/photo-1622106652310-4f3b3f5c5c5e?w=800&h=800&fit=crop',
            ],
            [
                'name' => 'شمع النحل الطبيعي - 200 جرام',
                'slug' => 'beeswax-200g',
                'description' => 'شمع نحل طبيعي 100%، يستخدم في العديد من الأغراض الصحية والتجميلية',
                'price' => 150,
                'category_id' => 4,
                'stock' => 40,
                'is_active' => true,
                'main_image' => 'https://images.unsplash.com/photo-1587735243474-0b5e13daf734?w=800&h=800&fit=crop',
            ],
            [
                'name' => 'حبوب اللقاح - 250 جرام',
                'slug' => 'bee-pollen-250g',
                'description' => 'حبوب لقاح طبيعية غنية بالبروتينات والفيتامينات',
                'price' => 220,
                'category_id' => 4,
                'stock' => 35,
                'is_active' => true,
                'main_image' => 'https://images.unsplash.com/photo-1519735777090-ec97543ce8bf?w=800&h=800&fit=crop',
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // إنشاء السلايدر
        $sliders = [
            [
                'title' => 'عسل طبيعي 100% من أجود المناحل',
                'description' => 'اكتشف أجود أنواع العسل الطبيعي من مصادر موثوقة',
                'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=1920&h=600&fit=crop',
                'link' => '/products',
                'button_text' => 'تسوق الآن',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'عسل السدر اليمني الفاخر',
                'description' => 'من جبال اليمن الشامخة - جودة لا مثيل لها',
                'image' => 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=1920&h=600&fit=crop',
                'link' => '/products',
                'button_text' => 'اطلب الآن',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'منتجات النحل الطبيعية',
                'description' => 'غذاء ملكات - شمع - حبوب لقاح وأكثر',
                'image' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=1920&h=600&fit=crop',
                'link' => '/products',
                'button_text' => 'استكشف المزيد',
                'is_active' => true,
                'display_order' => 3,
            ],
        ];

        foreach ($sliders as $sliderData) {
            Slider::create($sliderData);
        }

        $this->command->info('✅ تم إضافة جميع البيانات التجريبية بنجاح!');
        $this->command->info('📊 الإحصائيات:');
        $this->command->info('   - المستخدمين: ' . User::count());
        $this->command->info('   - الفئات: ' . Category::count());
        $this->command->info('   - المنتجات: ' . Product::count());
        $this->command->info('   - السلايدر: ' . Slider::count());
    }
}
