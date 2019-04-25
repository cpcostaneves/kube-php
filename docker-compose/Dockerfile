FROM php:7.3-apache

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
#RUN pecl install pdo_pgsql
RUN pecl install redis-4.0.1 && docker-php-ext-enable redis

# COPY src/ /var/www/html


