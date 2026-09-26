# =============================================================================
# Stage 1: Install Composer dependencies
# =============================================================================
FROM composer:2.8 AS composer-builder

WORKDIR /build

# Prefer IPv4 to avoid IPv6 DNS resolution timeouts in containerized networks
RUN echo "precedence ::ffff:0:0/96 100" >> /etc/gai.conf 2>/dev/null || true

ENV COMPOSER_IPRESOLVE_V4=1
ENV COMPOSER_PROCESS_TIMEOUT=600

# Copy dependency files first for layer caching
COPY composer.json composer.lock ./

# Support optional GitHub token via build arg to bypass rate-limiting if provided
ARG GITHUB_TOKEN=""
RUN if [ -n "$GITHUB_TOKEN" ]; then composer config --global github-oauth.github.com "$GITHUB_TOKEN"; fi \
    && composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs


# =============================================================================
# Stage 2: PHP extensions (isolated from Coolify ARG injection)
# =============================================================================
# This stage has NO COPY from other stages and NO application-specific inputs,
# so its cache is only invalidated when the base image or extension list changes.
# Coolify injects ARGs into every stage, but since none of them are referenced
# here, BuildKit treats them as unused and does NOT bust the cache.
FROM unit:php8.2 AS php-extensions

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl ca-certificates \
    && curl -fsSL -o /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/download/2.7.23/install-php-extensions \
    && chmod 0755 /usr/local/bin/install-php-extensions \
    && install-php-extensions pcntl pdo_mysql intl zip gd exif ftp bcmath redis \
    && docker-php-ext-enable opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# =============================================================================
# Stage 3: Production runtime (Nginx Unit + PHP 8.2)
# Frontend assets are pre-built and tracked in git (zero Node overhead on server)
# =============================================================================
FROM php-extensions AS runtime

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

# Copy application code (including pre-built public/build/ assets)
COPY . .

# Run Composer dump-autoload now that artisan and full source exist
COPY --from=composer-builder /usr/bin/composer /usr/local/bin/composer
RUN composer dump-autoload --optimize --no-dev --no-interaction --ignore-platform-reqs \
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
