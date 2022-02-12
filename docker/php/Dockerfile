FROM php:8.1-cli

ARG ZIP_VERSION=1.20.0
ARG XDEBUG_VERSION=3.1.1
ARG COMPOSER_VERSION=2.2.6

RUN apt-get update && apt-get install -y curl

RUN pecl install xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug

RUN apt install -y libzip-dev && \
    pecl install zip-${ZIP_VERSION} && \
    docker-php-ext-enable --ini-name 20-zip.ini zip

RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin/ --version=${COMPOSER_VERSION}

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./entrypoint.sh /entrypoint.sh

ENV PATH "${PATH}:/app/bin"

WORKDIR /app

RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
