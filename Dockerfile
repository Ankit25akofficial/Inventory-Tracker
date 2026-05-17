FROM php:8.3-cli

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip dos2unix \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install \
        pdo_mysql mbstring bcmath gd zip exif pcntl xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy app files
COPY . .

# Install PHP dependencies (no dev, optimized)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Setup storage directories and permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} \
             storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Fix Windows CRLF line endings and make executable
RUN dos2unix start.sh && chmod +x start.sh

EXPOSE 8080

CMD ["bash", "start.sh"]
