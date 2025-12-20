FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for writable directory
RUN mkdir -p /var/www/html/writable/cache \
    /var/www/html/writable/logs \
    /var/www/html/writable/session \
    /var/www/html/writable/uploads \
    /var/www/html/writable/debugbar \
    && chmod -R 777 /var/www/html/writable

# Create router script for PHP built-in server (handles CI4 routing)
RUN echo '<?php \
$uri = urldecode(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)); \
if ($uri !== "/" && file_exists(__DIR__ . $uri)) { return false; } \
$_SERVER["SCRIPT_NAME"] = "/index.php"; \
require_once __DIR__ . "/index.php";' > /var/www/html/public/router.php

# Expose port
EXPOSE 8080

# Start PHP built-in server
CMD php -S 0.0.0.0:${PORT:-8080} -t public public/router.php
