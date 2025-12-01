# Use FrankenPHP (built-in Caddy + PHP-FPM alternative)
FROM dunglas/frankenphp:1-php8.3

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip

# Set work directory
WORKDIR /app

# Copy app files
COPY . .

# Install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Build Vue assets (resources/js)
RUN npm install && npm run build

# Laravel optimizations
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Expose port used by FrankenPHP (Caddy)
EXPOSE 8080

# Run as production server
CMD ["php", "artisan", "frankenphp:octane", "--host=0.0.0.0", "--port=8080"]
