FROM php:7.2-fpm-alpine

RUN docker-php-ext-install sysvmsg

CMD ["php-fpm"]

EXPOSE 9000