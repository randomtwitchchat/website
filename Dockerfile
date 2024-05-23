#FROM node:20-alpine As asset_builder
#
#WORKDIR /app
#
#COPY ./package.json ./
#COPY ./package-lock.json ./
#COPY ./vite.config.js ./
#COPY ./postcss.config.js ./
#COPY ./tailwind.config.js ./
#COPY ./resources ./resources

#RUN npm install \
#    && npm run build


FROM php:fpm-alpine

WORKDIR /var/www/html

LABEL maintainer="Supernova3339 <supernova@superdev.one>"

LABEL description="RandomChat Dockerfile"

COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./vite.config.js ./
COPY ./postcss.config.js ./
COPY ./tailwind.config.js ./
COPY ./resources ./resources

RUN apk add --no-cache zip libzip-dev libpng-dev icu-dev npm # Install libpng-dev

#COPY --from=asset_builder /app/public/build ./public/build

RUN docker-php-ext-install zip \
    && docker-php-ext-install gd

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && apk add --no-cache \
    mariadb-client \
    sqlite \
    nginx

COPY . ./
RUN cp .env.example .env

RUN mkdir ./database/sqlite \
    && chown -R www-data: /var/www/html \
    && rm -rf ./docker

COPY ./docker/config/randomchat-php.ini /usr/local/etc/php/conf.d/cwam-php.ini
COPY ./docker/config/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/config/site-nginx.conf /etc/nginx/http.d/default.conf

RUN chmod +x ./docker-entrypoint.sh

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chown -R www-data:www-data *
RUN chmod -R 777 storage

EXPOSE 80
USER root
CMD ["./docker-entrypoint.sh"]
