# 1) pull a PHP-FPM image
FROM php:8.4-fpm

# 2) install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
      git unzip libicu-dev libpq-dev libonig-dev libzip-dev \
  && docker-php-ext-install \
      pdo pdo_pgsql intl zip opcache \
  && pecl install apcu \
  && docker-php-ext-enable apcu

# 3) install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) set working dir & copy sources
WORKDIR /var/www
COPY . .

# 5) install PHP deps
RUN composer install --no-interaction --prefer-dist \
  && chown -R www-data:www-data var vendor

# 6) expose fpm socket or port
EXPOSE 9000

# 7) default command
CMD ["php-fpm"]
