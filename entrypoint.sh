#!/bin/ash

if [ -f /app/vendor/autoload.php ]; then
    echo "vendor/autoload.php already exists"
else
    echo "Installing composer dependencies"
    composer install --no-interaction --no-progress --optimize-autoloader
fi

if [ -f /app/config.php ]; then
    echo "config.php already exists"
else
    echo "Creating config.php"
    cp config.php.example config.php
    chmod -R 766 /app/config.php
fi

if [ -d /app/public/storage ]; then
    echo "public/storage already exists"
else
    echo "Creating public/storage"
    mkdir -p /app/public/storage
fi

echo "Setting permissions"
chmod -R 777 /app/public/storage

echo "Starting php-fpm"
php-fpm
