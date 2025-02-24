FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN mkdir -p /var/www/storage/logs \
    /var/www/storage/framework/cache/data \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/storage/framework/testing \
    /var/www/bootstrap/cache \
    /var/www/tests/Unit \
    /var/www/tests/Feature \
    /var/www/database

RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

USER www-data

COPY --chown=www-data:www-data composer.* ./

RUN composer install \
    --no-scripts 

COPY --chown=www-data:www-data . .

RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump

EXPOSE 9000

CMD ["php-fpm"]