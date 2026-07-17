#!/usr/bin/env bash
# Travelify tema deploy — estonya, avustralya ve italya sitelerine pull
set -e

SITES=("estonya" "avustralya" "italya")

for site in "${SITES[@]}"; do
    DIR="/root/wordpress/sites/${site}/wp-content/themes/travelify"
    echo "▶ ${site} güncelleniyor..."
    ssh bmericc@192.168.0.82 "sudo git -C ${DIR} pull origin main"
    echo "  ✓ Tamamlandı"
done

echo ""
echo "✅ Deploy tamamlandı."
