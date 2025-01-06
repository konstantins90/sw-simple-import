FROM php:8-fpm

# Installiere System-Abhängigkeiten
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql

# Arbeitsverzeichnis setzen
WORKDIR /var/www/html