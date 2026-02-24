FROM php:8.3-cli-bookworm

RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libsqlite3-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-install \
        pdo_sqlite \
        mbstring \
        bcmath \
        pcntl \
        xml \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /opt/render/project/src

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative \
    && php artisan package:discover --ansi \
    && chmod +x scripts/render-start.sh

CMD ["./scripts/render-start.sh"]
