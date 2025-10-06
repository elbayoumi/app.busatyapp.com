#!/usr/bin/env sh
set -e
cd /var/www/html

# ensure necessary dirs
mkdir -p vendor storage bootstrap/cache

# fix permissions
chown -R www-data:www-data vendor storage bootstrap/cache
chmod -R ug+rwX vendor storage bootstrap/cache

# install composer deps if missing
if [ ! -f vendor/autoload.php ]; then
  echo "🔧 Installing composer dependencies..."
  composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
fi

echo "✅ Laravel container ready — starting php-fpm..."
exec php-fpm
