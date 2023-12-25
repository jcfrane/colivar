FROM nginx:stable-alpine

WORKDIR /var/www/html

COPY opt/nginx/conf /etc/nginx/conf.d
COPY opt/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
