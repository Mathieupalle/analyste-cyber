FROM php:8.3-apache

# extensions pour MySQL et MongoDB
RUN apt-get update && apt-get install -y git unzip libssl-dev
RUN docker-php-ext-install pdo_mysql
RUN pecl install mongodb && docker-php-ext-enable mongodb

# pour que le .htaccess fonctionne
RUN a2enmod rewrite

# configuration d'Apache : le site est dans le dossier public
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
