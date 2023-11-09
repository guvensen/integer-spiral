FROM php:8.2.0-fpm-alpine3.17

RUN apk update && apk add --no-cache\
    build-base \
    git \
    curl \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libpq-dev \
    zip \
    unzip \
    oniguruma-dev \
    vim


RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www-php8

RUN chown -R www-data:www-data /var/www-php8

# Expose the listening port
EXPOSE 9000
