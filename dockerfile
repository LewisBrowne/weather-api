FROM php:8.1-fpm
RUN apt-get update && apt-get install -y wget libonig-dev zip zlib1g-dev libpng-dev exiftool libicu-dev
RUN docker-php-ext-configure exif
RUN docker-php-ext-configure intl
RUN docker-php-ext-install mbstring mysqli pdo pdo_mysql exif intl
RUN docker-php-ext-enable pdo_mysql intl exif

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm", "-F"]

WORKDIR /var/www/api