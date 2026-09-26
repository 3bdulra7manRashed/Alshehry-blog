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

# Restrict Node memory to prevent OOM on lower-spec VPS during Vite build
ENV NODE_OPTIONS="--max-old-space-size=512"

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
FROM unit:php8.2 AS runtime

# Install PHP extensions and required libraries
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Force sequential execution: wait for node-builder to finish building assets before starting extension install.
# This prevents parallel compilation CPU/RAM spikes that crash low-spec VMs (OOM / exit code 255).
COPY --from=node-builder /build/package.json /tmp/node-builder-trigger

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl gosu \
    && install-php-extensions pcntl pdo_mysql intl zip gd exif ftp bcmath redis \
    && docker-php-ext-enable opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /tmp/node-builder-trigger

# OPCache configuration — production-optimized
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
RUN mkdir -p storage/app/public \
             storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
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

# Copy Nginx Unit configuration and entrypoint
COPY unit.json /docker-entrypoint.d/unit.json
COPY docker-entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

HEALTHCHECK --interval=30s --timeout=10s --retries=5 --start-period=60s \
    CMD curl -f http://127.0.0.1:8000/up || exit 1

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
CMD ["unitd", "--no-daemon", "--control", "unix:/var/run/control.unit.sock"]
