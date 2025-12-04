# 🚂 دليل الرفع على Railway

## الخطوة 1: رفع الكود على GitHub

```bash
cd "c:\Users\Abdallah\Desktop\honey store\project-name"
git add .
git commit -m "Ready for Railway deployment"
git push origin main
```

## الخطوة 2: إنشاء مشروع على Railway

1. اذهب إلى [railway.app](https://railway.app)
2. **New Project** → **Deploy from GitHub repo**
3. اختر repository المشروع

## الخطوة 3: إضافة قاعدة بيانات MySQL

1. في صفحة المشروع، انقر **+ Add Service**
2. اختر **MySQL**
3. انتظر حتى تكتمل الإضافة

## الخطوة 4: إعداد Environment Variables ⚠️ مهم جداً

في Railway، اذهب إلى **Variables** وأضف:

```
APP_NAME=رحيق
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://YOUR_APP.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### توليد APP_KEY:
شغّل هذا الأمر محلياً:
```bash
php artisan key:generate --show
```
انسخ الناتج وضعه في APP_KEY

## الخطوة 5: تحديث APP_URL

بعد أول deploy، انسخ الرابط الذي يعطيك إياه Railway وحدّث APP_URL

## ⚠️ حل مشكلة الروابط لا تعمل

إذا الروابط لا تعمل، تأكد من:

1. **APP_URL صحيح** - يجب أن يكون نفس رابط Railway بالضبط
2. **APP_KEY موجود** - بدونه لن يعمل شيء
3. **Database متصلة** - تحقق من logs

### للتحقق من الـ Logs:
في Railway، اذهب إلى **Deployments** → انقر على آخر deployment → **View Logs**

## الخطوة 6: تشغيل Migrations

Railway يشغّل migrations تلقائياً. لكن إذا لم تعمل:
1. اذهب إلى **Settings** → **Deploy**
2. تأكد أن Start Command يحتوي على `php artisan migrate --force`

---

## ✅ متطلبات الإنتاج

- [x] Trust Proxies (تم إضافته)
- [x] Force HTTPS (تم إضافته)
- [x] Session driver = file
- [x] Storage link
- [x] Nixpacks config
