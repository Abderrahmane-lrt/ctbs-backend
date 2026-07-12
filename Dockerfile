FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip xml gd \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM base AS development

ARG UID=1000
ARG GID=1000

RUN groupmod -g ${GID} www-data && \
    usermod -u ${UID} -g ${GID} www-data

RUN chown -R www-data:www-data /var/www/html /var/lib/nginx /var/log/nginx

USER www-data

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off; pid /tmp/nginx.pid;"