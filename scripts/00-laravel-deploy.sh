#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan view:cache

echo "Caching routes..."
php artisan route:cache
php artisan config:clear

echo "Running migrations..."
php artisan migrate --force

echo "Running npm..."
npm install
npm run build

echo "Link Storage..."
php artisan storage:link --force