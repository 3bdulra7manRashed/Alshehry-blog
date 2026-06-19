#!/bin/sh
set -e

# Function to wait for database connection
wait_for_db() {
    if [ "$DB_CONNECTION" = "mysql" ]; then
        echo "Waiting for MySQL ($DB_HOST)..."
        until php -r "try { new PDO('mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD'); exit(0); } catch (Exception \$e) { exit(1); }"; do
            echo "MySQL is unavailable - sleeping..."
            sleep 2
        done
        echo "MySQL is up!"
    fi
}

# 1. Ensure .env exists for Artisan CLI consistency
if [ ! -f ".env" ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# 2. Fix permissions for storage and cache (Crucial for volumes)
echo "Fixing permissions..."
mkdir -p storage/app/public \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

# If running as root (which we are at start), fix ownership
if [ "$(id -u)" = "0" ]; then
    chown -R unit:unit storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
fi

# 3. Handle Queue Worker mode
if [ "$1" = "php" ] && [ "$2" = "artisan" ] && [ "$3" = "queue:work" ]; then
    echo "Running as Queue Worker..."
    wait_for_db
    exec "$@"
fi

# 4. Web Application specific tasks
if [ "$1" = "unitd" ]; then
    echo "Running as Web Application..."

    wait_for_db

    # Generate APP_KEY if missing (Safe for production as it won't overwrite existing key)
    if [ -z "$APP_KEY" ] && ! grep -q "APP_KEY=base64:" .env; then
        echo "Generating application key..."
        php artisan key:generate --force
    fi

    # Run migrations
    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        echo "Running migrations..."
        php artisan migrate --force
    fi

    # Run Seeder if requested (or if it's a fresh install)
    if [ "${RUN_SEEDER:-false}" = "true" ]; then
        echo "Running seeders..."
        php artisan db:seed --class=AdminUserSeeder --force
    fi

    # Optimize for production
    if [ "${APP_ENV:-production}" = "production" ]; then
        echo "Caching configuration and routes..."
        # We use --force or similar if needed, but standard commands work
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan event:cache
    fi
fi

echo "Starting: $@"
exec /usr/local/bin/docker-entrypoint.sh "$@"
