FROM composer AS composer

FROM php:8.2-fpm AS base

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update
# dependencies for gd
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libxpm-dev zlib1g-dev
# dependencies for zip
RUN apt-get install -y libzip-dev
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/webapp

COPY . .

RUN pecl install xdebug
RUN docker-php-ext-configure gd --enable-gd --with-webp --with-jpeg --with-xpm --with-freetype
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
RUN docker-php-ext-enable xdebug gd

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-dev \
    --prefer-dist
