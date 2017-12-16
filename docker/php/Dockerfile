FROM php:7.1-fpm-alpine

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable opcache xdebug \
    && rm -rf /tmp/*

COPY xdebug.ini /usr/local/etc/php/conf.d/
