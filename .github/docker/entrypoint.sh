#!/bin/ash -e
cd /app

chmod 777 -R storage

echo -e "Starting supervisord."
exec "$@"
