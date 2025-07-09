# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache modules
RUN a2enmod rewrite headers

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Apache configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set the Apache document root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Create the storage link
RUN php artisan storage:link || true

# Cache configuration
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]