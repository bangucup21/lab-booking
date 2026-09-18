#!/bin/sh
# docker/php/entrypoint.sh
# Dijalankan saat container start — SETELAH .env di-inject
# Sehingga artisan cache membaca nilai production yang benar

set -e

# ── Restore public folder backup (jika .public_backup ada) ────────
# Volume `public_files` (Docker Swarm) di-mount ke /var/www/html/public
# dan menutupi aset yang sudah di-bake ke image. Kita copy dari backup
# ke shared volume supaya Nginx container lain bisa serve aset statis.
# Pakai cp -rf agar deploy baru selalu overwrite file lama (penting
# untuk aset Vite yang nama filenya hash tergantung versi build).
if [ -d "$(pwd)/.public_backup" ]; then
    echo "[entrypoint] Restoring public assets from backup (Swarm shared volume)..."
    cp -rf "$(pwd)/.public_backup/." "$(pwd)/public/"
    echo "[entrypoint] Public assets restored."
fi

echo "[entrypoint] Clearing stale cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "[entrypoint] Building production cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "[entrypoint] Running migrations (if any)..."
php artisan migrate --force --no-interaction

echo "[entrypoint] Starting PHP-FPM..."
exec "$@"
