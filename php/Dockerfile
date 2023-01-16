FROM php:8.1.0-fpm

RUN apt-get update
RUN apt-get install -y curl

#install some base extensions
RUN apt-get install -y \
    zip

RUN docker-php-ext-install pdo_mysql

# Composer install
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /home/app

CMD ["php-fpm"]