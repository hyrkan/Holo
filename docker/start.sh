#!/usr/bin/env bash
set -euo pipefail

PORT="${PORT:-8080}"

cat >/etc/nginx/conf.d/laravel.conf <<NGINX_CONF
server {
    listen ${PORT};
    server_name _;
    root /var/www/html/public;
    index index.php index.html;
    client_max_body_size 20m;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include /etc/nginx/fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT \$realpath_root;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX_CONF

cd /var/www/html

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwx storage bootstrap/cache || true

php artisan storage:link || true

if [[ "${RUN_MIGRATIONS:-false}" == "true" ]]; then
  php artisan migrate --force || true
fi

php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php-fpm -D
exec nginx -g "daemon off;"
