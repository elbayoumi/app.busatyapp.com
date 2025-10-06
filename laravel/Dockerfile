# syntax=docker/dockerfile:1
############################################
# PHP-FPM 8.2 for Laravel (Dev/Prod ready)
############################################
FROM php:8.2-fpm

# --- Build arguments to align container user with host (prevent permission issues)
ARG PUID=1000
ARG PGID=1000
ARG INSTALL_XDEBUG=0   # set to 1 to include xdebug at build time

# --- System dependencies (light base)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip curl ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libicu-dev libxml2-dev \
    libssl-dev pkg-config autoconf g++ make \
 && rm -rf /var/lib/apt/lists/*

# --- Common PHP extensions for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
      pdo_mysql bcmath gd zip intl exif pcntl sockets

# --- Redis via PECL
RUN pecl install redis && docker-php-ext-enable redis

# --- Optional: Xdebug
RUN if [ "$INSTALL_XDEBUG" = "1" ]; then \
      pecl install xdebug && docker-php-ext-enable xdebug && \
      { echo "xdebug.mode=develop,debug"; echo "xdebug.start_with_request=yes"; } > /usr/local/etc/php/conf.d/xdebug.ini ; \
    fi

# --- Opcache defaults
RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.enable_cli=0"; \
  echo "opcache.jit=1255"; \
  echo "opcache.jit_buffer_size=128M"; \
  echo "opcache.validate_timestamps=1"; \
  echo "opcache.revalidate_freq=0"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# --- Reasonable PHP defaults
RUN { \
  echo "memory_limit=512M"; \
  echo "upload_max_filesize=64M"; \
  echo "post_max_size=64M"; \
  echo "max_execution_time=120"; \
  echo "expose_php=0"; \
  echo "display_errors=0"; \
  echo "log_errors=1"; \
  echo "error_reporting=E_ALL & ~E_DEPRECATED & ~E_STRICT"; \
} > /usr/local/etc/php/conf.d/99-app.ini

# --- Composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/composer

# --- Align www-data UID/GID with host
RUN groupmod -o -g ${PGID} www-data \
 && usermod  -o -u ${PUID} -g www-data www-data

# --- Application working directory
WORKDIR /var/www/html

# --- Copy project files (do this before composer to ensure it has composer.json)
COPY . /var/www/html

# --- Ensure necessary directories exist and are writable
RUN mkdir -p /var/www/html/vendor /var/www/html/storage /var/www/html/bootstrap/cache \
 && chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 775 {} \; \
 && find /var/www/html -type f -exec chmod 664 {} \; \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- Switch to non-root user
USER www-data

# --- Install composer dependencies (no-dev)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# --- Default CMD
CMD ["php-fpm"]
