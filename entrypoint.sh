#!/bin/sh
set -e

# Ensure Laravel directories exist and are writable
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache || true

# Clear caches generated during build (invalid because no .env existed)
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Build correct caches based on Railway environment variables
php artisan config:cache || true
php artisan route:cache || true

# Start FrankenPHP via Caddyfile
exec frankenphp run --adapter=caddyfile --config=/app/CaddyFile
