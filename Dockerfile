FROM php:8.1-fpm


# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage

# Exponer el puerto 8081
EXPOSE 8081

CMD ["php", "artisan", "serve", "--host:0.0.0.0", "--port=8081"]