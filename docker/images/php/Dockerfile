FROM php:8.3-fpm

ARG arch

RUN apt-get update
RUN apt-get install -y aptitude\
    wget \
    git

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD conf/www.conf /usr/local/etc/php-fpm.d/www.conf
ADD conf/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

WORKDIR /application

CMD ["php-fpm"]
