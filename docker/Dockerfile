FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY .. .

RUN chown -R www-data:www-data /var/www

RUN chmod +x /var/www/docker/entrypoint.sh

ENTRYPOINT ["sh", "/var/www/docker/entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]