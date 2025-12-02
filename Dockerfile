FROM dunglas/frankenphp:1-php8.3

# Install system dependencies + Node.js
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Discover Laravel packages
RUN php artisan package:discover

# Build Vue app
RUN npm install && npm run build

# Expose port used by FrankenPHP
EXPOSE 8080

# Run Laravel with FrankenPHP (no Octane)
CMD ["frankenphp", "run", "--config=/app/frankenphp.php", "--port=${PORT}", "--worker"]
