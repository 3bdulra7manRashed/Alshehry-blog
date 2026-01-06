# ==============================================================================
# Laravel Production Dockerfile with NGINX Unit
# Optimized for Coolify deployment
# ==============================================================================
FROM unit:1.34.1-php8.3

# Install system dependencies and PHP extensions
RUN apt update && apt install -y \
    curl unzip git libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pcntl opcache pdo pdo_mysql intl zip gd exif ftp bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt clean && rm -rf /var/lib/apt/lists/*

# PHP production configuration with OPcache JIT
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit_buffer_size=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time=60" >> /usr/local/etc/php/conf.d/custom.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install Node.js for building assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt install -y nodejs \
    && apt clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Create required directories with correct permissions
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R unit:unit /var/www/html

# Copy dependency files first for better layer caching
COPY --chown=unit:unit composer.json composer.lock ./
COPY --chown=unit:unit package.json package-lock.json ./

# Install Composer dependencies (production mode, no scripts yet)
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# Install npm dependencies
RUN npm ci --prefer-offline --no-audit

# Copy application code
COPY --chown=unit:unit . .

# Run Composer scripts (package discovery, etc.)
RUN composer dump-autoload --optimize --no-interaction

# Build frontend assets and clean up
RUN npm run build && rm -rf node_modules && npm cache clean --force

# Set final permissions
RUN chown -R unit:unit storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy NGINX Unit configuration
COPY unit.json /docker-entrypoint.d/unit.json

# Create startup script for cache warming
RUN echo '#!/bin/bash\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan storage:link 2>/dev/null || true\n\
exec unitd --no-daemon' > /start.sh \
    && chmod +x /start.sh

# Health check for Coolify (using wget which is available, or check process)
HEALTHCHECK --interval=30s --timeout=10s --start-period=90s --retries=3 \
    CMD wget -q --spider http://127.0.0.1:8000/ || pgrep unitd > /dev/null || exit 1

EXPOSE 8000

CMD ["/start.sh"]
