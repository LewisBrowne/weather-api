#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

apt-get install git -y

cd /var/www/
if [ -f api/vendor/laravel/framework/composer.json ]; then
    chown -R www-data:www-data api
    chmod -R 755 api
    cp .env api/
    cd api/
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache	
    echo -e "${GREEN}Laravel is already installed, skipping.${NC}"
else
    echo -e "Installing Laravel...${NC}"
    
    #
    mkdir tmp/
    cd tmp/
    composer create-project --prefer-dist laravel/laravel api
    cp -R -n api/ /var/www/
    cd /var/www/
    chown -R www-data:www-data api
    chmod -R 755 api
    cp .env api/
    chmod -R 775 api/storage
    chmod -R 775 api/bootstrap/cache	
    
    rm -rf tmp/
    echo -e "${GREEN}Laravel is now installed.${NC}"

    echo -e "Installing Laravel Telescope...${NC}"
    cd /var/www/api/
    composer require laravel/telescope
    php artisan telescope:install
    php artisan migrate
    echo -e "${GREEN}Laravel Telescope is now installed.${NC}"

    cd /root/
fi