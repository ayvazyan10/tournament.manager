#!/usr/bin/env bash

set -e

cd /var/www

composer install --no-interaction --prefer-dist --optimize-autoloader

php bin/console doctrine:migrations:migrate --no-interaction

exec "$@"