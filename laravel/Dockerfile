# syntax=docker/dockerfile:1
############################################
# PHP-FPM 8.2 for Laravel (Dev/Prod ready)
############################################
FROM php:8.2-fpm

# --- Build args to align container user with host (avoid file perms issues)
ARG PUID=1000
ARG PGID=1000
ARG INSTALL_XDEBUG=0   # set to 1 if you want xdebug while building

# --- System dependencies (lean set; add more only if needed)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip curl ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libicu-dev libxml2-dev \
    libssl-dev pkg-config autoconf g++ make \
 && rm -rf /var/lib/apt/lists/*

# --- PHP extensions commonly required by Laravel
# gd (with jpeg/freetype) + pdo_mysql + intl + zip + exif + bcmath + pcntl + sockets
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
      pdo_mysql bcmath gd zip intl exif pcntl sockets

# --- Redis via PECL
RUN pecl install redis \
 && docker-php-ext-enable redis

# --- Optional: Xdebug (build with --build-arg INSTALL_XDEBUG=1)
RUN if [ "$INSTALL_XDEBUG" = "1" ]; then \
      pecl install xdebug && docker-php-ext-enable xdebug && \
      { echo "xdebug.mode=develop,debug"; echo "xdebug.start_with_request=yes"; } > /usr/local/etc/php/conf.d/xdebug.ini ; \
    fi

# --- Opcache (good defaults; can be overridden by mounted local.ini)
RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.enable_cli=0"; \
  echo "opcache.jit=1255"; \
  echo "opcache.jit_buffer_size=128M"; \
  echo "opcache.validate_timestamps=1"; \
  echo "opcache.revalidate_freq=0"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# --- Reasonable PHP defaults (overridable by docker/php/local.ini from compose)
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

# --- Composer (from official image) + cache dir
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/composer

# --- Align www-data UID/GID with host (so mounted volumes stay writable)
RUN groupmod -o -g ${PGID} www-data \
 && usermod  -o -u ${PUID} -g www-data www-data

# --- App working directory
WORKDIR /var/www/html

# --- Ensure runtime dirs exist and are writable (safe if already present)
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# --- Switch to non-root by default (compose can still override with `user:`)
USER www-data

# Default CMD is php-fpm (from base image)
