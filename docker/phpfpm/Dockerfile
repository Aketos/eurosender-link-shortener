FROM php:7.4-fpm as eurosender-app

RUN apt-get update && \
apt-get install -y libpng-dev libjpeg-dev libpq-dev libzip-dev zip unzip sudo wget zlib1g-dev libicu-dev libzstd-dev g++ && \
rm -rf /var/lib/apt/lists/*

RUN yes | pecl install igbinary redis grpc protobuf

RUN docker-php-ext-install intl && \
docker-php-ext-install gd && \
docker-php-ext-install opcache && \
docker-php-ext-install zip && \
docker-php-ext-install calendar && \
docker-php-ext-install sockets && \
docker-php-ext-install mysqli && \
docker-php-ext-install pdo && \
docker-php-ext-install pdo_mysql && \
docker-php-ext-enable igbinary && \
docker-php-ext-enable redis && \
docker-php-ext-enable pdo_mysql && \
docker-php-ext-enable grpc && \
docker-php-ext-enable protobuf

RUN rm -rf /var/www/html \
&& chmod 0777 /tmp/

FROM eurosender-app AS composer
## Copy files and install Composer
COPY . /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

## Install required packages
RUN composer install -n --optimize-autoloader --no-dev --prefer-dist; \
composer dump-autoload -n --optimize --no-dev --classmap-authoritative; \
