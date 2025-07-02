FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git\
    curl\
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    locales \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl pdo pdo_mysql mbstring exif pcntl bcmath xml

WORKDIR /var/www/html
COPY  . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache


EXPOSE 9000
