#!/bin/bash

# Entrypoint script for Laravel in production

echo "🚀 Starting Laravel application..."

# Wait for database to be ready (if using external DB)
echo "⏳ Waiting for database connection..."
php artisan tinker --execute="DB::connection()->getPdo();" || echo "⚠️  Database not ready, continuing..."

# Generate app key if not exists
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "📋 Running database migrations..."
php artisan migrate --force

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Cache configurations for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions one more time
echo "🔒 Setting final permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
chmod -R 775 storage bootstrap/cache

echo "✅ Laravel application ready!"

# Start Apache
exec apache2-foreground
