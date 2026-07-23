#!/bin/sh
set -e

# Ensure database and storage directories exist
mkdir -p /var/www/html/database \
         /var/www/html/storage/app/public \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs

# Touch SQLite file
touch /var/www/html/database/database.sqlite

# Clear all cached configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure permissions for Apache www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Run database migrations
php artisan migrate --force

# Start Apache in foreground
exec apache2-foreground
