FROM php:8.0.9-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libicu-dev \
    git

RUN docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) \
        intl \
    && pecl install

RUN git clone --depth 1  https://github.com/edenhill/librdkafka.git \
    && cd librdkafka \
    && ./configure \
    && make \
    && make install

RUN pecl channel-update pecl.php.net \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    && rm -rf librdkafka

COPY --from=composer:2.0.11 /usr/bin/composer /usr/bin/composer

COPY . /app

WORKDIR /app

RUN composer install --no-interaction

