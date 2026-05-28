# 1. Usamos la imagen oficial de PHP
FROM php:8.2-cli

# 2. Instalamos el conector PDO MySQL para que funcione Clever Cloud
RUN docker-php-ext-install pdo pdo_mysql

# 3. Copiamos todos los archivos de tu tienda al servidor
COPY . /app

# 4. Le decimos al servidor que trabaje desde esa carpeta
WORKDIR /app

# 5. El comando exacto que pedía tu profesor
CMD ["php", "-S", "0.0.0.0:10000"]