# Utiliser l'image officielle PHP 7 avec Apache
FROM php:7.4-apache

# Installer les extensions PHP nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de ton projet dans le conteneur
COPY . /var/www/html

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf


# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Donner les permissions à Apache pour servir les fichiers
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Exposer le port 80
EXPOSE 80

# Installer les dépendances via Composer
#RUN composer install --no-scripts --no-interaction
RUN composer install --no-dev --optimize-autoloader

RUN composer dump-env dev

# Lancer Apache en premier plan
CMD ["apache2-foreground"]