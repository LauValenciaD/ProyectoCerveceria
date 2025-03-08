# 1️⃣ Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2️⃣ Instala extensiones necesarias para MySQL y MySQLi
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 3️⃣ Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# 4️⃣ Establece permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# 5️⃣ Habilita mod_rewrite de Apache si usas URLs amigables
RUN a2enmod rewrite

# 6️⃣ Expone el puerto 80
EXPOSE 80

# 7️⃣ Inicia Apache
CMD ["apache2-foreground"]
