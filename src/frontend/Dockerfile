FROM php:8.0-apache
WORKDIR /var/www/html

RUN apt-get update -y && api-get install -y libmariadb-dev
RUN docker-php-ext-install mysqli
