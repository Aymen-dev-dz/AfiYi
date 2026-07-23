#!/bin/sh
set -e

# Ensure database, storage, and .env exist
mkdir -p /var/www/html/database \
         /var/www/html/storage/app/public \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs

# Touch SQLite file
touch /var/www/html/database/database.sqlite

# Ensure .env file exists so artisan commands can write to it if needed
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# Check if APP_KEY is missing or invalid, generate via --show if needed
case "$APP_KEY" in
  base64:*) 
    echo "Valid APP_KEY detected."
    ;;
  *) 
    echo "Generating valid Laravel APP_KEY..."
    NEW_KEY=$(php artisan key:generate --show --no-interaction)
    export APP_KEY="$NEW_KEY"
    echo "APP_KEY=$NEW_KEY" >> /var/www/html/.env
    ;;
esac

# Clear configuration cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure permissions for Apache www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env

# Run database migrations
php artisan migrate --force

# Start Apache in foreground
exec apache2-foreground
