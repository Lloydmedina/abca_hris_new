# Use the official PHP image
FROM php:7.4-fpm

# Set the working directory
WORKDIR /var/www/html

# Install required extensions and packages
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip

RUN docker-php-ext-configure zip
RUN docker-php-ext-install pdo pdo_mysql zip

# Copy application files into the container
COPY . .

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]