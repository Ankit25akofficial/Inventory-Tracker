FROM php:8.3-cli

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
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
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force \
    && php artisan storage:link \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
