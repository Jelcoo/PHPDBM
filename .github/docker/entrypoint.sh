#!/bin/ash -e
cd /app

chmod 777 -R storage

composer install  --no-dev --optimize-autoloader

echo -e "Starting supervisord."
exec "$@"
