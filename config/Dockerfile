# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Copia los archivos del proyecto al directorio raíz del servidor
COPY /hola /var/www/html/

# Establece permisos para asegurar que Apache pueda acceder a los archivos
RUN chmod -R 755 /var/www/html/

# Instala extensiones adicionales de PHP si son necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia la configuración personalizada de Apache
COPY /config/000.default.conf /etc/apache2/sites-available/000-default.conf

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Habilita la configuración del sitio
RUN a2ensite 000-default

# Reinicia Apache para aplicar los cambios de configuración
CMD ["apache2-foreground"]
