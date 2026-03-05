#!/bin/sh
set -e

echo "🔧 Setting up storage directories..."

# Create required directories
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Fix permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Run standard Laravel optimizations in production
if [ "$APP_ENV" = "production" ]; then
    echo "Running in production mode..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
else
    echo "Running in Development mode - clearing caches..."
    php artisan config:clear
    php artisan route:clear
fi

php artisan optimize:clear

# Wait for Database
echo "Waiting for database connection..."
until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; do
    echo "Database is unavailable - sleeping..."
    sleep 2
done
echo "Database is ready!"

# Run migrations
php artisan migrate --force

exec "$@"
