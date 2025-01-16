FROM php:8.4-fpm-alpine

WORKDIR /app
COPY . ./

RUN apk add --no-cache mysql-client supervisor \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && cp config.php.example config.php

COPY .github/docker/supervisord.conf /etc/supervisord.conf

ENTRYPOINT [ "/bin/ash", ".github/docker/entrypoint.sh" ]
CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
