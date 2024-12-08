FROM php:8.4-fpm

ENV PHP_IDE_CONFIG 'serverName=localhost'

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip
RUN DEBIAN_FRONTEND=noninteractive && pecl install xdebug-3.4.0

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g $PGID user && useradd -u $PUID -g $PGID -s /bin/bash -m user
USER user

ENV PATH /home/user/.composer/vendor/bin:$PATH

COPY ../../ /app
WORKDIR /app
CMD ["php-fpm", "--nodaemonize"]