#!/bin/sh
cd /var/www/html

echo 'Queue starting'

envsubst < .env.example > .env
chmod 777 -Rf /var/www/html

php artisan queue:listen --queue=notifications,listeners,messages -v