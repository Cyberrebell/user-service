FROM nginx:latest

RUN apt-get update \
    && apt-get install -y ssl-cert \
    && make-ssl-cert generate-default-snakeoil \
    && apt-get clean

COPY default.conf /etc/nginx/conf.d/default.conf
