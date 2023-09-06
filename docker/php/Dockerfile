FROM php:8.1-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get -qq update \
    && apt-get install -y \
        libzip-dev \
        libicu-dev \
        unzip

RUN docker-php-ext-install zip \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_mysql

WORKDIR /app