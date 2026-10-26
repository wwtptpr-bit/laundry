#!/bin/bash
set -e

# 1. Konfigurasi Port Dinamis Render (${PORT})
PORT_NUM=${PORT:-80}
sed -i "s/80/${PORT_NUM}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 2. Pastikan permission folder storage dan bootstrap/cache aman
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Clear cache config, route, dan view sebelum aplikasi melayani trafik
echo "Clearing Laravel cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# 4. Jalankan Apache di foreground
echo "Starting Apache on port ${PORT_NUM}..."
exec apache2-foreground

