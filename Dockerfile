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

RUN mkdir -p /var/www && \
    chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www

COPY --chown=www-data:www-data . .

USER www-data

RUN composer install \
    --no-plugins \
    --no-scripts 

RUN composer dump-autoload --optimize && \
    composer run-script post-autoload-dump

EXPOSE 9000

CMD ["php-fpm"]