# Utilisation de l'image officielle PHP avec Apache
FROM php:8.1-apache

# Installation des extensions nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activation de mod_rewrite (utile pour les routes)
RUN a2enmod rewrite

# Copie des fichiers de l'application dans le conteneur
COPY ./src /var/www/html/

# Définition du répertoire de travail
WORKDIR /var/www/html

# Exposer le port 80 pour accéder à l'application
EXPOSE 80
