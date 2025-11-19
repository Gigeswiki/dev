#!/usr/bin/env bash
set -euo pipefail

CONFIG_SOURCE="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)/config/nginx/ekart.conf"
SITE_NAME="/etc/nginx/sites-available/ekart"
LINK_NAME="/etc/nginx/sites-enabled/ekart"

if [ "$EUID" -ne 0 ]; then
  echo "Bu script'in nginx dizinlerine yazabilmesi için sudo ile çalıştırılması gerekir." >&2
  exit 1
fi

cp "$CONFIG_SOURCE" "$SITE_NAME"
ln -sf "$SITE_NAME" "$LINK_NAME"
nginx -t
systemctl reload nginx

echo "Nginx konfigürasyonu güncellendi ve reload edildi."
