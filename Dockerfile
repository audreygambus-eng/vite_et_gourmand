FROM php:8.3-apache

# Activation du module Apache nécessaire aux routes Symfony 
RUN a2enmod rewrite

# Installation des dépendances système et des extensions PHP 
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Installation de l'extension MongoDB 
RUN pecl install mongodb-1.21.5 && docker-php-ext-enable mongodb

# Récupération de Composer depuis son image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuration d'Apache pour servir l'application depuis le dossier public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . .

# Installation des dépendances PHP 
RUN composer install --no-interaction