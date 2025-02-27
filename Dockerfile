FROM php:8.4-fpm-alpine

WORKDIR /app

RUN docker-php-ext-install pdo_mysql

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
