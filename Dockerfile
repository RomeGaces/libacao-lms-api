FROM dunglas/frankenphp:1-php8.3

# Install dependencies + compiler + MySQL dev headers + Node.js
RUN apt-get update \
 && apt-get install -y git curl zip unzip \
    build-essential default-mysql-client default-libmysqlclient-dev \
 && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs \
 && rm -rf /var/lib/apt/lists/*

# Install PHP MySQL extension
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Build Vue app
RUN npm install && npm run build

# Ensure Laravel storage/logs exists
RUN mkdir -p storage/logs

EXPOSE 8080

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Start Laravel + FrankenPHP server at runtime
CMD ["/usr/local/bin/entrypoint.sh"]
