FROM composer:lts as prod-deps

WORKDIR /app

RUN --mount=type=bind,source=./composer.json,target=composer.json \
    --mount=type=bind,source=./composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM composer:lts as dev-deps

WORKDIR /app

RUN --mount=type=bind,source=./composer.json,target=composer.json \
    --mount=type=bind,source=./composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-interaction

FROM php:fpm as base

WORKDIR /var/www/webapp

RUN apt-get update -y
RUN docker-php-ext-install pdo_mysql
COPY . .

FROM base as development

COPY --from=dev-deps /app/vendor/ ./vendor

FROM development as test

RUN ./vendor/bin/phpunit tests

FROM base as production

COPY --from=prod-deps /app/vendor/ ./vendor
USER www-data