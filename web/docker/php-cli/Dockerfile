FROM composer:1.9 as composer

FROM php:7.3-cli

RUN pecl install xdebug \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer
