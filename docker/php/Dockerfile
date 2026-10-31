FROM php:8.3-apache

# extensions pour MySQL et MongoDB
RUN apt-get update && apt-get install -y git unzip libssl-dev
RUN docker-php-ext-install pdo_mysql
RUN pecl install mongodb && docker-php-ext-enable mongodb

# pour que le .htaccess fonctionne
RUN a2enmod rewrite

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# le site est dans le dossier public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
