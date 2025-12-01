FROM dunglas/frankenphp:1-php8.3

# Install system dependencies + Node.js
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Set app directory
WORKDIR /app

# Copy app code
COPY . .

# Install PHP dependencies WITHOUT running composer scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Laravel framework optimizations
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Build Vue/Vite assets
RUN npm install && npm run build

# Expose FrankenPHP port
EXPOSE 8080

# Run Laravel Octane using FrankenPHP
CMD ["php", "artisan", "frankenphp:octane", "--host=0.0.0.0", "--port=8080"]
