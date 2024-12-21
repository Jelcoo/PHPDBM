FROM php:8.4-fpm-alpine

WORKDIR /app
COPY . ./

RUN apk add --no-cache mysql-client supervisor \
    && docker-php-ext-install pdo pdo_mysql

COPY .github/docker/supervisord.conf /etc/supervisord.conf

ENTRYPOINT [ "/bin/ash", ".github/docker/entrypoint.sh" ]
CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
