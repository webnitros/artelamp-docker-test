ARG PHP_VERSION=7.4

FROM php:7.4-fpm-alpine

COPY ./../../modx ./
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk update && apk upgrade
RUN apk add build-base autoconf libzip-dev libpng-dev libjpeg-turbo-dev libwebp-dev bash mariadb-client mc nano

RUN pecl install xdebug-3.1.0
RUN docker-php-ext-configure gd --with-jpeg --with-webp && docker-php-ext-install gd
RUN docker-php-ext-install exif
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN composer global config minimum-stability alpha
RUN composer global require modmore/gitify:^2
ENV PATH=/root/.composer/vendor/bin:$PATH

CMD php-fpm -R
