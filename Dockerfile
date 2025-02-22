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
    libsqlite3-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-dev --no-autoloader

COPY --chown=www-data:www-data . /var/www

RUN mkdir -p database && \
    touch database/database.sqlite && \
    chown -R www-data:www-data database && \
    chmod 777 database/database.sqlite

RUN composer dump-autoload --optimize

USER www-data

EXPOSE 9000

CMD ["php-fpm"]