FROM php:8.2-apache

# Instala dependências do Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip && \
    docker-php-ext-install pdo pdo_mysql zip

# Habilita mod_rewrite para URLs amigáveis do Laravel
RUN a2enmod rewrite

# Configura DocumentRoot para /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Aponta para o novo DocumentRoot no Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
