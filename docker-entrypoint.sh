#!/bin/sh
set -e

# Ensure all files and directories created by processes are group-writable (775/664)
umask 0002

# Function to wait for database connection
# Uses a dedicated PHP script to avoid shell-escaping issues with passwords
wait_for_db() {
    if [ "${DB_CONNECTION:-mysql}" = "mysql" ]; then
        echo "Waiting for MySQL (${DB_HOST:-mysql}:${DB_PORT:-3306})..."
        until php -r '
            $host = getenv("DB_HOST") ?: "mysql";
            if ($host === "127.0.0.1" || $host === "localhost") {
                $host = "mysql";
            }
            $port = getenv("DB_PORT") ?: "3306";
            $db   = getenv("DB_DATABASE") ?: "alshehri_blog";
            $user = getenv("DB_USERNAME") ?: "laravel";
            $pass = getenv("DB_PASSWORD") ?: "";
            try {
                new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                exit(0);
            } catch (Exception $e) {
                fwrite(STDERR, "MySQL Connection error on {$host}:{$port} (user '{$user}', db '{$db}'): " . $e->getMessage() . "\n");
                exit(1);
            }
        '; do
            echo "MySQL is unavailable - sleeping 2s..."
            sleep 2
        done
        echo "MySQL is up!"
    fi
}

# Function for background workers to wait until database migrations are ready
wait_for_migrations() {
    if [ "${DB_CONNECTION:-mysql}" = "mysql" ]; then
        echo "Waiting for database migrations to be applied..."
        until php -r '
            $host = getenv("DB_HOST") ?: "mysql";
            if ($host === "127.0.0.1" || $host === "localhost") {
                $host = "mysql";
            }
            $port = getenv("DB_PORT") ?: "3306";
            $db   = getenv("DB_DATABASE") ?: "alshehri_blog";
            $user = getenv("DB_USERNAME") ?: "laravel";
            $pass = getenv("DB_PASSWORD") ?: "";
            try {
                $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                $stmt = $pdo->query("SHOW TABLES LIKE \"migrations\"");
                if ($stmt && $stmt->fetch()) {
                    exit(0);
                }
                fwrite(STDERR, "Migrations table not yet created\n");
                exit(1);
            } catch (Exception $e) {
                fwrite(STDERR, "Migration check error: " . $e->getMessage() . "\n");
                exit(1);
            }
        '; do
            echo "Migrations not ready yet - sleeping 2s..."
            sleep 2
        done
        echo "Migrations are ready!"
    fi
}

# 1. Ensure .env exists for Artisan CLI consistency
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        echo "Creating .env from .env.example..."
        cp .env.example .env
    else
        echo "WARNING: No .env or .env.example found, creating empty .env"
        touch .env
    fi
fi

# 2. Fix permissions for storage and cache (Crucial for Docker volumes)
echo "Ensuring storage and cache directories exist with correct permissions..."
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

if [ "$(id -u)" = "0" ]; then
    chown -R unit:unit storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
fi

# 3. Create storage symlink for uploaded public media if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "Creating public storage symlink..."
    php artisan storage:link --force || true
fi

# 4. Generate APP_KEY if missing (Safe for production as it won't overwrite existing key)
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# 5. Handle Queue Worker & Scheduler modes (CLI commands)
if [ "$1" = "php" ] && [ "$2" = "artisan" ]; then
    echo "Starting artisan command: $@"
    wait_for_db
    wait_for_migrations
    exec "$@"
fi

# 6. Web Application specific tasks (NGINX Unit)
if [ "$1" = "unitd" ]; then
    echo "Starting Web Application initialization..."
    wait_for_db

    # Run database migrations
    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        echo "Running database migrations..."
        php artisan migrate --force
    fi

    # Run essential seeders if requested (Roles, Super Admin, Deleted User Placeholder)
    if [ "${RUN_SEEDER:-false}" = "true" ]; then
        echo "Running initial seeders..."
        php artisan db:seed --class=RolesAndPermissionsSeeder --force || true
        php artisan db:seed --class=AdminUserSeeder --force || true
        php artisan db:seed --class=DeletedUserSeeder --force || true
    fi

    # Optimize for production
    if [ "${APP_ENV:-production}" = "production" ]; then
        echo "Caching configuration, routes, and views for production..."
        php artisan optimize:clear
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan event:cache
    fi

    # Re-verify permissions for unit user after caching
    if [ "$(id -u)" = "0" ]; then
        chown -R unit:unit storage bootstrap/cache
        chmod -R 775 storage bootstrap/cache
    fi
fi

echo "Starting process: $@"
if [ -x "/usr/local/bin/docker-entrypoint.sh" ]; then
    exec /usr/local/bin/docker-entrypoint.sh "$@"
else
    exec "$@"
fi
