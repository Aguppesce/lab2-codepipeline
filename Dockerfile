FROM php:8.0-apache

# Instalar dependencias del sistema y Composer
RUN apt-get update && apt-get install -y git unzip
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos de la aplicación
COPY . /var/www/html/

WORKDIR /var/www/html

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configuración corregida de Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN mkdir -p /var/www/html/config
RUN echo "# Configuración adicional" > /var/www/html/config/vhost.conf
RUN mkdir -p /var/log/apache2/example-app
COPY apache/default-site.conf /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/ServerName example-app.com/ServerName localhost/' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80
