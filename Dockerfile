# Gunakan base image resmi PHP 8.2 dengan Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies dan library untuk ekstensi PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    zip \
    ca-certificates \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi & install ekstensi PHP yang dibutuhkan Laravel dan TiDB
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

# Aktifkan modul rewrite Apache
RUN a2enmod rewrite

# Konfigurasi VirtualHost Apache agar DocumentRoot mengarah ke folder public/
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Salin Composer binary dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Salin seluruh kode proyek ke dalam container
COPY . /var/www/html

# Jalankan composer install untuk production
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=php

# Atur kepemilikan dan permission untuk folder storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Salin dan set executable entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh && chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose default port (Render akan meng-inject variabel $PORT saat runtime)
EXPOSE 80

# Jalankan entrypoint script yang menangani $PORT dinamis dan cache clear sebelum Apache berjalan
CMD ["/usr/local/bin/docker-entrypoint.sh"]

