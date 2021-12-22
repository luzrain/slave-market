FROM php:8.1-cli

ARG ZIP_VERSION=1.20.0
ARG XDEBUG_VERSION=3.1.1
ARG COMPOSER_VERSION=2.1.11

RUN apt-get update && apt-get install -y curl git

RUN pecl install xdebug-${XDEBUG_VERSION} \
    && docker-php-ext-enable xdebug

RUN apt install -y libzip-dev \
    && pecl install zip-${ZIP_VERSION} \
    && docker-php-ext-enable --ini-name 20-zip.ini zip

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- \
    --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION}

WORKDIR /app
