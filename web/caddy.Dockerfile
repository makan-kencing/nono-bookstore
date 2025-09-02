FROM caddy:alpine

WORKDIR /var/www/webapp

COPY public ./public
