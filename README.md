# weather-api
A Laravel 10.6.2 API to power a basic weather app.

## Prerequisites
* Docker
* Docker-compose

## Getting Started
Modify your hosts file to include:

`127.0.0.1 api.local`

Then run the following commands
```
docker-compose up -d
docker exec -it api-php /bin/bash
cd /root
bash install_laravel.sh
```

## Generating API Documentation
```
docker exec -it api-php /bin/bash
php artisan scribe:generate
```

Then visit http://api.local/docs/index.html