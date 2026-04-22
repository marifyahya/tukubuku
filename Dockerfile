FROM php:8.3-fpm-alpine

# Install system dependencies with minimal packages
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    zip \
    icu-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        intl \
        bcmath \
        opcache \
        exif \
        pcntl \
        gd

# Install redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install dependencies (optimized for production)
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    && rm -rf /root/.composer

# Copy application code
COPY . .

# Optimize opcache for production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Set proper permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Change to non-root user for security
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]