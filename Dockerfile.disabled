FROM php:8.2.12-apache-bullseye

# Set environment variables for better builds
ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

# Install system dependencies in a single layer for better caching
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    sqlite3 \
    zip \
    unzip \
    libzip-dev \
    ca-certificates \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && curl -sS https://getcomposer.org/installer | php -- --version=2.6.5 --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better layer caching)
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copy application files
COPY . .

# Copy and make startup script executable
COPY railway-start.sh /usr/local/bin/railway-start.sh
RUN chmod +x /usr/local/bin/railway-start.sh

# Run post-autoload scripts
RUN composer dump-autoload --optimize

# Create storage directories and set permissions
RUN mkdir -p storage/logs \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/database \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache configuration
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 8080

# Start with Railway startup script
CMD ["/usr/local/bin/railway-start.sh"]
