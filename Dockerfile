# =============================================================================
# Stage 1: Install Composer dependencies
# =============================================================================
FROM composer:2.8 AS composer-builder

WORKDIR /build

# Copy dependency files first for layer caching
COPY composer.json composer.lock ./

# Install production dependencies without scripts (artisan doesn't exist yet)
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts


# =============================================================================
# Stage 2: Build frontend assets
# =============================================================================
FROM node:20-slim AS node-builder

WORKDIR /build

# Copy dependency files first for layer caching
COPY package.json package-lock.json ./

# Install npm dependencies (cached unless package files change)
RUN npm ci --prefer-offline --no-audit

# Copy only files needed for the Vite build
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources/ resources/

# Tailwind content config also scans these paths for CSS class detection:
#   - vendor/laravel/framework/.../Pagination views (pagination CSS classes)
#   - storage/framework/views (compiled Blade cache — empty at build time)
COPY --from=composer-builder /build/vendor/laravel/framework/src/Illuminate/Pagination/resources/views/ \
     vendor/laravel/framework/src/Illuminate/Pagination/resources/views/
RUN mkdir -p storage/framework/views

# Build production assets
RUN npm run build


# =============================================================================
# Stage 3: Production runtime
# =============================================================================
FROM unit:1.34.1-php8.3 AS runtime

# Install PHP extensions and required libraries
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       curl \
       libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pcntl opcache pdo pdo_mysql intl zip gd exif ftp bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# OPCache configuration — production-optimized, no JIT
# JIT is removed because:
#   - Laravel is I/O-bound (DB, HTTP, templates), not CPU-bound
#   - JIT reserves large memory buffers per worker process (was 256M each)
#   - JIT adds startup overhead with no measurable benefit for web frameworks
#   - validate_timestamps=0 is safe in containers (code doesn't change at runtime)
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_file_override=1" >> /usr/local/etc/php/conf.d/opcache.ini

# PHP runtime configuration
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/php-runtime.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/php-runtime.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/php-runtime.ini

WORKDIR /var/www/html

# Create storage directories with correct permissions
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
             storage/framework/views storage/logs bootstrap/cache \
    && chown -R unit:unit storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy Composer dependencies from builder stage
COPY --from=composer-builder /build/vendor/ vendor/

# Copy application code
COPY . .

# Copy compiled frontend assets from Node builder stage
COPY --from=node-builder /build/public/build/ public/build/

# Run Composer dump-autoload now that artisan and full source exist
COPY --from=composer-builder /usr/bin/composer /usr/local/bin/composer
RUN composer dump-autoload --optimize --no-interaction \
    && rm -f /usr/local/bin/composer

# Set final permissions
RUN chown -R unit:unit storage bootstrap/cache . \
    && chmod -R 775 storage bootstrap/cache

# Copy Nginx Unit configuration
COPY unit.json /docker-entrypoint.d/unit.json

EXPOSE 8000

CMD ["unitd", "--no-daemon"]