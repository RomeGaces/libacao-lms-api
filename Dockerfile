FROM dunglas/frankenphp:1-php8.3

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip

# Install Composer manually
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Set work directory
WORKDIR /app

# Copy composer files separately for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies (without dev)
RUN composer install --no-dev --optimize-autoloader

# Copy all app files
COPY . .

# Build Vue assets (Vite)
RUN npm install && npm run build

# Laravel optimizations
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Expose FrankenPHP port
EXPOSE 8080

# Final runtime command
CMD ["php", "artisan", "frankenphp:octane", "--host=0.0.0.0", "--port=8080"]
