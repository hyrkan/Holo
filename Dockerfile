FROM node:20-bookworm-slim AS node_builder

WORKDIR /app

COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm i; fi

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build

FROM php:8.2-fpm-bullseye AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    ca-certificates \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) pdo_mysql zip gd opcache \
  && rm -rf /var/lib/apt/lists/*

RUN printf "upload_max_filesize=16M\npost_max_size=18M\n" > /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=node_builder /app/public/build ./public/build

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R ug+rwx storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container \
  && rm -f /etc/nginx/sites-enabled/default \
  && rm -f /etc/nginx/conf.d/default.conf

EXPOSE 8080

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=20000 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=256 \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER=16

CMD ["/usr/local/bin/start-container"]
