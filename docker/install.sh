#!/bin/bash

docker-compose up -d --build nginx postgres
docker-compose exec workspace bash -c "composer install -d /var/www -vvv"
docker-compose exec workspace bash -c "php /var/www/artisan key:generate"

docker-compose exec workspace bash -c "php /var/www/artisan migrate"
