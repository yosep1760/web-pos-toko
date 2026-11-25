#!/bin/bash
set -e

PORT=${PORT:-10000}
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

php artisan config:clear
php artisan migrate --force

if [ "$RUN_SEED" = "true" ]; then
  php artisan db:seed --force || true
fi

php artisan optimize:clear
php artisan optimize

exec apache2-foreground