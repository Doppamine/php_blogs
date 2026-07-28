FROM php:8.3-fpm-alpine

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql opcache zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_HOME=/tmp/composer

COPY php.ini /usr/local/etc/php/conf.d/99-app.ini
#UID & GID Sync with host user
RUN apk add --no-cache shadow \
    && usermod -u ${UID} www-data \
    && groupmod -g ${GID} www-data

WORKDIR /var/www/html
USER www-data

EXPOSE 9000
CMD ["php-fpm"]
