FROM dunglas/frankenphp:1-php8.3

# Install dependencies + Node.js + PHP extensions early
RUN apt-get update \
 && apt-get install -y \
    git curl zip unzip \
    php8.3-mysql php8.3-gd php8.3-xml php8.3-mbstring php8.3-intl \
 && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Build Vue
RUN npm install && npm run build

# Ensure storage/logs exists
RUN mkdir -p storage/logs

EXPOSE 8080

# Add entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use runtime entrypoint (fixes config cache + env issues)
CMD ["/usr/local/bin/entrypoint.sh"]
