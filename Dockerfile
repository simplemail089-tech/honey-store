# 1. نستخدم صورة PHP مع Apache جاهزة
FROM php:8.2-apache

# 2. تسطيب البرامج اللازمة (Node.js, NPM, Zip)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    curl \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 3. تفعيل Mod Rewrite الخاص بـ Laravel
RUN a2enmod rewrite

# 4. تسطيب إضافات PHP المطلوبة
RUN docker-php-ext-install pdo_mysql zip bcmath

# 5. ضبط مجلد العمل
WORKDIR /var/www/html

# 6. نسخ ملفات المشروع
COPY . .

# 7. تسطيب Composer وتشغيل التثبيت
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 8. تسطيب NPM وبناء ملفات التصميم (Vite)
RUN npm install
RUN npm run build

# 9. ضبط صلاحيات مجلدات Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. تعديل إعدادات Apache ليقرأ من مجلد public ويسمع للمنفذ الصحيح
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 11. تشغيل السيرفر
CMD ["apache2-foreground"]