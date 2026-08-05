#!/usr/bin/env bash
# Travelify tema deploy — estonya, avustralya, italya ve yunanistan sitelerine pull
# ardından her sitenin docker container'ında WP Fastest Cache flush edilir.
set -e

SITES=("estonya" "avustralya" "italya" "yunanistan")

for site in "${SITES[@]}"; do
    DIR="/root/wordpress/sites/${site}/wp-content/themes/travelify"
    CONTAINER="wordpress-${site}-1"
    WP_PATH="/var/www/${site}"

    echo "▶ ${site} güncelleniyor..."
    ssh bmericc@192.168.0.82 "sudo git -C ${DIR} pull origin main"
    echo "  ✓ Tema güncellendi"

    echo "  ▶ ${site} WP Fastest Cache temizleniyor (${CONTAINER})..."
    if ssh bmericc@192.168.0.82 "sudo docker exec ${CONTAINER} wp --path=${WP_PATH} eval 'wpfc_clear_all_cache();' --allow-root"; then
        echo "  ✓ Cache temizlendi"
    else
        echo "  ⚠ Cache temizlenemedi (${site}) — container adını/wp-cli kurulumunu kontrol et"
    fi
done

echo ""
echo "✅ Deploy tamamlandı."
