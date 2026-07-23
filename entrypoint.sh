#!/bin/sh
set -e

# Ensure SQLite file exists and has full read/write permissions
mkdir -p /var/www/html/database /var/www/html/storage/framework/views /var/www/html/storage/framework/sessions /var/www/html/storage/framework/cache /var/www/html/storage/logs
touch /var/www/html/database/database.sqlite

# Ensure permissions for webserver
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run database migrations FIRST (creates cache table, users, etc.)
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start Apache in foreground
exec apache2-foreground
