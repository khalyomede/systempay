FROM php:7.2-alpine

RUN apk add --no-cache composer $PHPIZE_DEPS
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug 

USER www-data
