#!/bin/sh
set -e

echo "Starting AFIYI container initialization..."

# 1. Ensure all directories exist with full permissions
mkdir -p /var/www/html/database \
         /var/www/html/storage/app/public \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs

# 2. Ensure SQLite file exists
touch /var/www/html/database/database.sqlite

# 3. Ensure .env file exists
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# 4. Set full permissions for Apache user www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env

# 5. Clear all cached configurations & compiled views
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# 6. Run database migrations
php artisan migrate --force || echo "Migration warning: continuing..."

# 7. Ensure permissions once more after migrations
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env

echo "AFIYI initialization complete. Starting Apache..."

# 8. Start Apache
exec apache2-foreground
