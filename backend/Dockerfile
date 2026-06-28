FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf g++ make \
    && docker-php-ext-install \
        pdo pdo_pgsql bcmath mbstring zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf g++ make

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

# vendor is mounted from host via bind mount (./backend:/var/www/backend)
# composer install must be run on the host before starting the stack

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
