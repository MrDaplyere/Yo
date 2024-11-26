# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Copia los archivos del proyecto al directorio raíz del servidor
COPY . /var/www/html/

# Instala extensiones adicionales de PHP si son necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql
