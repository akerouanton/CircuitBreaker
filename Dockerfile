FROM php:7.0-cli-alpine

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    echo 'apc.enable_cli=1' >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini && \
    apk del .build-deps

RUN echo '@edge http://dl-cdn.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories && \
    apk --no-cache add composer@edge

ENV COMPOSER_HOME /.composer/
RUN composer global require hirak/prestissimo && \
    chmod -R 777 /.composer/
