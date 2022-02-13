FROM php:8.1-cli

ENV COMPOSER_VERSION=2.2.6
ENV COMPOSER_CACHE_DIR /tmp/composer
ENV PATH "${PATH}:/app/bin"

RUN apt-get update && \
    apt-get install -y curl zip libzip-dev

RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin/ --version=${COMPOSER_VERSION}

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /app

CMD ["/entrypoint.sh"]
