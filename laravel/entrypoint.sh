#!/usr/bin/env sh
set -e
cd /var/www/html

# ensure runtime dirs
mkdir -p vendor storage bootstrap/cache

# fix permissions (idempotent)
chown -R www-data:www-data /var/www/html
chmod -R ug+rwX storage bootstrap/cache

# install composer deps if missing
if [ ! -f vendor/autoload.php ]; then
  composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
fi

exec php-fpm
