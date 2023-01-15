FROM php:8.1.0-fpm

RUN apt-get update
RUN apt-get install -y curl

#install some base extensions
RUN apt-get install -y \
    zip

# node 18.x install
RUN curl -sL https://deb.nodesource.com/setup_18.x
RUN apt install -y nodejs

# Composer install
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /home/app

CMD ["php-fpm"]