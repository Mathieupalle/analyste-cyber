FROM php:8.3-apache

# Extensions : PDO MySQL + MongoDB, et réécriture d'URL Apache
RUN apt-get update && apt-get install -y --no-install-recommends git unzip libssl-dev \
    && docker-php-ext-install pdo_mysql \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Le site est servi depuis /public (le reste du code n'est pas accessible depuis le web)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
