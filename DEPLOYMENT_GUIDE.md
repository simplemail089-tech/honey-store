# 📋 دليل رفع المشروع على InfinityFree (Demo)

## ⚠️ ملاحظة هامة
هذا الدليل مخصص لرفع نسخة تجريبية (Demo) فقط على استضافة مجانية. للإنتاج الفعلي، يُنصح باستخدام استضافة مدفوعة مع SSH.

---

## 🔧 الخطوات التقنية

### **1. تجهيز ملفات المشروع**

#### أ) نقل محتويات public إلى htdocs
```
المشكلة: InfinityFree يستخدم htdocs بدلاً من public
الحل: دمج محتويات public مع root directory
```

**الخطوات:**
1. انسخ جميع الملفات من مجلد `public/` إلى `htdocs/`
   - `index.php` → `htdocs/index.php`
   - `.htaccess` → `htdocs/.htaccess`
   - `storage/` link → سيتم إعادة إنشائه

2. انسخ باقي ملفات المشروع إلى `htdocs/`:
   ```
   htdocs/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/ (احذف هذا المجلد)
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .env
   ├── index.php (من public)
   ├── .htaccess (من public)
   └── artisan
   ```

---

### **2. تعديل index.php**

**الملف الأصلي:**
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**الملف المعدل لـ htdocs:**
```php
<?php
// index.php في htdocs root
define('LARAVEL_START', microtime(true));

// تحميل Composer
require __DIR__.'/vendor/autoload.php';

// تحميل Bootstrap
$app = require_once __DIR__.'/bootstrap/app.php';

// Run the application
$app->handleRequest(
    Illuminate\Http\Request::capture()
);
```

---

### **3. تعديل .htaccess**

**ملف .htaccess المحسّن لـ InfinityFree:**

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # منع الوصول للملفات الحساسة
    RewriteRule ^(.*)\.env$ - [F,L]
    RewriteRule ^(.*)composer\.(json|lock)$ - [F,L]
    
    # Redirect Trailing Slashes...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
    
    # منع عرض محتوى المجلدات
    Options -Indexes
</IfModule>

# حماية إضافية
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "(composer\.json|composer\.lock|package\.json|\.env)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

### **4. إنشاء Symbolic Link للـ Storage (يدوياً)**

**المشكلة:** لا يوجد SSH لتشغيل `php artisan storage:link`

**الحل:**
1. في File Manager الخاص بـ InfinityFree:
   - انتقل إلى `htdocs/storage/app/public`
   - انسخ جميع الملفات

2. أنشئ مجلد جديد:
   - `htdocs/storage_public/`
   - الصق الملفات داخله

3. عدّل في الكود كل مرجع لـ `storage/` ليصبح `storage_public/`:
   ```php
   // قبل
   asset('storage/products/image.jpg')
   
   // بعد
   asset('storage_public/products/image.jpg')
   ```

**أو استخدم طريقة أفضل:**
أنشئ ملف `create_symlink.php` في htdocs:
```php
<?php
// create_symlink.php
// قم بزيارة هذا الملف مرة واحدة عبر المتصفح ثم احذفه

$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/storage_public';

if (file_exists($link)) {
    echo "Symlink already exists!";
} else {
    // إنشاء نسخ يدوية بدلاً من symlink
    if (!file_exists($link)) {
        mkdir($link, 0755, true);
    }
    
    // انسخ الملفات
    shell_exec("cp -r $target/* $link/");
    
    echo "Storage linked successfully! Delete this file now.";
}
?>
```

---

### **5. تعديل ملف .env**

```env
APP_NAME="متجر رحيق - نسخة تجريبية"
APP_ENV=demo
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://yoursite.infinityfreeapp.com

# قاعدة البيانات - من cPanel
DB_CONNECTION=mysql
DB_HOST=sql123.infinityfree.com
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_pass

# السيشن - استخدم database بدلاً من file
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=sync

# Disable Broadcasting
BROADCAST_DRIVER=log

# البريد - معطل في النسخة التجريبية
MAIL_MAILER=log
```

---

### **6. رفع قاعدة البيانات**

1. **Export قاعدة البيانات المحلية:**
   ```bash
   php artisan migrate:fresh --seed
   mysqldump -u root -p your_db > database_backup.sql
   ```

2. **Import في InfinityFree:**
   - افتح phpMyAdmin من cPanel
   - أنشئ قاعدة بيانات جديدة
   - Import ملف `database_backup.sql`

3. **تعديل البيانات الحساسة:**
   ```sql
   -- غيّر بيانات الأدمن
   UPDATE users SET email = 'demo@rahiq.com', password = '$2y$12$...' WHERE id = 1;
   
   -- غيّر روابط الصور لو كانت localhost
   UPDATE products SET main_image = REPLACE(main_image, 'http://localhost', '');
   UPDATE categories SET image = REPLACE(image, 'http://localhost', '');
   ```

---

### **7. حماية المجلدات الحساسة**

أضف ملف `.htaccess` داخل كل مجلد حساس:

**في `storage/.htaccess`:**
```apache
Order deny,allow
Deny from all
```

**في `bootstrap/cache/.htaccess`:**
```apache
Order deny,allow
Deny from all
```

---

### **8. تحسينات الأداء**

**في `config/app.php`:**
```php
// معطل في Demo
'debug' => false,
```

**في `config/session.php`:**
```php
'driver' => env('SESSION_DRIVER', 'database'),
'secure' => true, // استخدم HTTPS
'same_site' => 'lax',
```

---

## ✅ Checklist قبل الرفع

```
□ نقل محتويات public إلى htdocs
□ تعديل index.php (المسارات)
□ تعديل .htaccess (الحماية)
□ رفع جميع الملفات عبر FTP
□ رفع قاعدة البيانات
□ تعديل .env (البيانات الصحيحة)
□ إنشاء storage link
□ اختبار تسجيل دخول الأدمن
□ اختبار إضافة منتج للسلة
□ اختبار صفحة الدفع
□ التأكد من ظهور الصور
□ إضافة إخلاء المسؤولية في Footer
```

---

## 🔐 الأمان

### **ملفات يجب حمايتها:**
```
.env
composer.json
composer.lock
package.json
storage/
bootstrap/cache/
database/
```

### **إعدادات Apache:**
تأكد من تفعيل:
- `mod_rewrite`
- `AllowOverride All`

---

## 📊 المراقبة

**بعد الرفع، راقب:**
1. **أخطاء 500:** تحقق من `storage/logs/laravel.log`
2. **بطء الموقع:** طبيعي على InfinityFree
3. **الصور:** تأكد من المسارات الصحيحة

---

## 🚨 التحذيرات

1. **InfinityFree يحذف الملفات بعد 24 ساعة من عدم النشاط** - زر الموقع يومياً
2. **حد رفع الملفات 10MB** - ضغط الصور قبل رفعها
3. **لا يوجد Cronjobs** - Queue و Schedule لن يعملا
4. **بطء عشوائي** - ضع Disclaimer في Footer

---

## 📝 ملاحظات إضافية

- **Composer:** لن تحتاجه، ارفع مجلد `vendor/` كامل
- **npm/Node:** لن تحتاجه، ارفع `public/build/` أو الـ CSS/JS المُجمّع
- **Logs:** احذف `storage/logs/*.log` أسبوعياً لتوفير المساحة

---

## 🔄 بديل أفضل للإنتاج

للعملاء الفعليين، استخدم:
- **Shared Hosting:** Hostinger, Namecheap (من 2$ شهرياً)
- **VPS:** DigitalOcean, Vultr (من 5$ شهرياً)
- **Laravel Hosting:** Laravel Forge + DigitalOcean

---

## 📞 الدعم

إذا واجهتك مشكلة:
1. تحقق من `storage/logs/laravel.log`
2. تأكد من صحة بيانات `.env`
3. تحقق من permissions المجلدات (755 للمجلدات، 644 للملفات)
