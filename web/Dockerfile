FROM php:fpm as base

WORKDIR /var/www/webapp

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install pdo_mysql