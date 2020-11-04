FROM php:7.2-cli-alpine

RUN apk update && apk add autoconf g++ git

RUN adduser -D -u 1000 appuser

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && rm -rf /root/.composer/cache

WORKDIR /app

USER appuser

