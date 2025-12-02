FROM dunglas/frankenphp:1-php8.3

# Install system dependencies + Node.js
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy app code
COPY . .

# Install PHP dependencies WITHOUT running composer scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN php artisan octane:install --server=frankenphp --yes
# Discover packages and optimize Laravel
RUN php artisan package:discover \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Build Vue/Vite assets
RUN npm install && npm run build

EXPOSE 8080

CMD ["sh", "-c", "php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=${PORT}"]
