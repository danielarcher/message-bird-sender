FROM php:7.2-fpm

RUN docker-php-ext-install sysvmsg

RUN pecl install xdebug

RUN docker-php-ext-enable xdebug

CMD ["php-fpm"]

EXPOSE 9000