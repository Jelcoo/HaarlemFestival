FROM php:8.4-fpm-alpine

WORKDIR /app

RUN docker-php-ext-install pdo_mysql

RUN apk add --no-cache git unzip zip libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev zlib-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd zip

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "post_max_size=128M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=128M" >> /usr/local/etc/php/conf.d/custom.ini
