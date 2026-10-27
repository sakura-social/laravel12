FROM php:8.4-fpm-alpine
WORKDIR /var/www/html
COPY . .

ARG UID=1000
ARG GID=1000

RUN docker-php-ext-install pdo pdo_mysql

# Create the group and user using the IDs passed in
RUN addgroup -g ${GID} laravel && \
    adduser -G laravel -u ${UID} -s /bin/sh -D laravel


# Fix FPM config to use the new user
RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf

RUN chown www-data:www-data /var/www/html

USER laravel
