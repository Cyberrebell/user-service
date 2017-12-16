#!/bin/sh
docker-compose build

if [ ! -f composer.phar ]; then
    docker-compose run --rm user-php sh -c 'wget https://getcomposer.org/composer.phar && chmod o+w composer.phar'
fi

docker-compose run --rm user-php sh -c 'php composer.phar install; chmod -R o+w vendor/'

if [ ! -f config/config.php ]; then
    cp config/dev.config.php config/config.php
fi
