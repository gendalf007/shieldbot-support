#!/usr/bin/env bash
#
# Deploy script for LM-ocr.
# Run from the project root after pulling new code.
#
# Usage:
#   ./deploy.sh              # pulls + installs + builds
#   SKIP_PULL=1 ./deploy.sh  # for the very first deploy or when you pulled manually
#   SKIP_NPM=1 ./deploy.sh   # if assets are pre-built locally and committed

set -euo pipefail

cd "$(dirname "$0")"

if [[ "${SKIP_PULL:-0}" != "1" ]]; then
    echo "==> git pull"
    git pull --ff-only origin main
fi

echo "==> composer install"
composer install --no-interaction --prefer-dist --optimize-autoloader

if [[ ! -f .env ]]; then
    echo "==> .env missing, copying from .env.example"
    cp .env.example .env
    php artisan key:generate
    echo "    ! Open .env and set OPENAI_API_KEY, then re-run this script."
    exit 1
fi

if [[ ! -f database/database.sqlite ]]; then
    echo "==> creating empty database/database.sqlite"
    mkdir -p database
    touch database/database.sqlite
fi

echo "==> php artisan migrate --force"
php artisan migrate --force

echo "==> clearing caches"
php artisan config:clear
php artisan route:clear
php artisan view:clear

if [[ "${SKIP_NPM:-0}" != "1" ]]; then
    if command -v npm >/dev/null 2>&1; then
        echo "==> npm install + build"
        npm install --no-audit --no-fund
        npm run build
    else
        echo "==> npm not found, skipping asset build (form will use Tailwind CDN fallback)"
    fi
fi

echo "==> fixing storage permissions"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo
echo "✓ Deploy complete. Open the site in your browser."
