#!/bin/ash -e
cd /app

chmod 777 -R storage

composer install  --no-dev --optimize-autoloader

cp config.php.example config.php

echo -e "Starting supervisord."
exec "$@"
