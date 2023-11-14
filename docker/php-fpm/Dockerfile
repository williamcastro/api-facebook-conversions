FROM composer:2 AS vendor
WORKDIR /var/www/html
COPY /src/composer* ./
RUN composer install \
  #--no-dev \
  --no-interaction \
  --prefer-dist \
  --ignore-platform-reqs \
  --optimize-autoloader \
  --apcu-autoloader \
  --ansi \
  --no-scripts

FROM php:8.3-rc-fpm
