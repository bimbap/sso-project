#!/bin/bash

# Railway startup script for Laravel
echo "🚀 Starting Laravel application on Railway..."

# Wait for database to be ready (if using managed database)
echo "⏳ Waiting for database connection..."
php -r "
\$maxAttempts = 30;
\$attempt = 0;
while (\$attempt < \$maxAttempts) {
    try {
        if (getenv('DB_CONNECTION') === 'sqlite') {
            // For SQLite, ensure directory exists
            \$dbPath = dirname(getenv('DB_DATABASE') ?: '/app/storage/database/database.sqlite');
            if (!\$dbPath) {
                mkdir(\$dbPath, 0755, true);
            }
            echo \"SQLite database ready\n\";
            break;
        } else {
            // For MySQL, test connection
            \$pdo = new PDO(
                'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );
            echo \"Database connection successful\n\";
            break;
        }
    } catch (Exception \$e) {
        \$attempt++;
        echo \"Database connection attempt \$attempt failed: \" . \$e->getMessage() . \"\n\";
        sleep(2);
    }
}
if (\$attempt >= \$maxAttempts) {
    echo \"Failed to connect to database after \$maxAttempts attempts\n\";
    exit(1);
}
"

# Run database migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

# Clear and cache configuration for production
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
php artisan storage:link || true

echo "✅ Laravel application ready!"

# Start Apache
exec apache2-foreground
