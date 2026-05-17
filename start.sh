#!/bin/bash
set -e

# Strip any accidental whitespace/tabs from critical env vars
export APP_ENV=$(echo "$APP_ENV" | xargs)
export DB_HOST=$(echo "$DB_HOST" | xargs)
export DB_PORT=$(echo "$DB_PORT" | xargs)
export DB_DATABASE=$(echo "$DB_DATABASE" | xargs)
export DB_USERNAME=$(echo "$DB_USERNAME" | xargs)
export DB_PASSWORD=$(echo "$DB_PASSWORD" | xargs)
export APP_KEY=$(echo "$APP_KEY" | xargs)

# If individual DB vars are broken/empty, fall back to parsing MYSQL_URL
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
    if [ -n "$MYSQL_URL" ]; then
        echo "Falling back to MYSQL_URL for DB config..."
        export DB_HOST=$(echo "$MYSQL_URL" | sed -E 's|mysql://[^:]+:[^@]+@([^:]+):.*|\1|')
        export DB_PORT=$(echo "$MYSQL_URL" | sed -E 's|mysql://[^:]+:[^@]+@[^:]+:([^/]+)/.*|\1|')
        export DB_DATABASE=$(echo "$MYSQL_URL" | sed -E 's|mysql://[^:]+:[^@]+@[^/]+/(.+)|\1|')
        export DB_USERNAME=$(echo "$MYSQL_URL" | sed -E 's|mysql://([^:]+):.*|\1|')
        export DB_PASSWORD=$(echo "$MYSQL_URL" | sed -E 's|mysql://[^:]+:([^@]+)@.*|\1|')
    fi
fi

echo "Connecting to DB: $DB_HOST:$DB_PORT/$DB_DATABASE as $DB_USERNAME"

# Run migrations
php artisan migrate --force

# Auto-seed if no users exist (first deploy only)
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | grep -E '^[0-9]+$' | tail -1)
echo "Current user count: $USER_COUNT"
if [ -z "$USER_COUNT" ] || [ "$USER_COUNT" = "0" ]; then
    echo "No users found - running database seeder..."
    php artisan db:seed --force
    echo "Seeding complete!"
fi

# Create storage symlink
php artisan storage:link 2>/dev/null || true

echo "Starting Laravel server on port ${PORT:-8080}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
