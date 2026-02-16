#!/usr/bin/env sh
set -eu

PORT_VALUE="${PORT:-10000}"
exec php artisan chat:socket 0.0.0.0 "$PORT_VALUE"
