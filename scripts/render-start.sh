#!/usr/bin/env bash
set -euo pipefail

cd /opt/render/project/src

if [[ -z "${APP_KEY:-}" ]]; then
  echo "APP_KEY is missing. Set APP_KEY on Render or keep render.yaml APP_KEY generateValue." >&2
  exit 1
fi

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

if [[ "${DB_CONNECTION:-sqlite}" == "sqlite" ]]; then
  DB_PATH="${DB_DATABASE:-/var/data/database.sqlite}"
  mkdir -p "$(dirname "${DB_PATH}")"
  touch "${DB_PATH}"
fi

php artisan migrate --force
php artisan config:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
