# weather-api
A Laravel 10.6.2 API to power a basic weather app.

## Getting Started
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