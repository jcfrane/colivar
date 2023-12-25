#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

cd /var/www/html

echo "Environment: ${PROJECT_APP_ENV}"
if [ "${PROJECT_APP_ENV}" = "local" ]
then
	echo "Running composer in local".
	composer install
fi

# We need this coz' environment variables are not present on build time
envsubst < .env.example > .env
php artisan migrate --force --no-interaction


if [ "${PROJECT_APP_ENV}" = "production" ]
then
	echo "Caching configurations".
	php artisan route:cache
	php artisan view:cache
fi

exec "$@"
