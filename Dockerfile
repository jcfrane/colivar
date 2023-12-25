### This Dockerfile is based on my pre-built base image that is hosted in Dockerhub.
### https://github.com/jcfrane/docker-php

FROM jcfrane/php:8.3-bullseye

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./opt/php/php.ini /usr/local/etc/php/conf.d/php.ini
WORKDIR /var/www/html
COPY ./ .

RUN composer install --optimize-autoloader --no-dev

COPY /opt/php/entrypoint.sh /usr/local/bin/
COPY /opt/php/queue-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]

