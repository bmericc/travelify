#!/usr/bin/env bash
# Travelify tema deploy — estonya, avustralya, italya ve yunanistan sitelerine pull
# ardından her sitenin docker container'ında W3 Total Cache flush edilir.
set -e

SITES=("estonya" "avustralya" "italya" "yunanistan")

for site in "${SITES[@]}"; do
    DIR="/root/wordpress/sites/${site}/wp-content/themes/travelify"
    CONTAINER="wordpress-${site}-1"

    echo "▶ ${site} güncelleniyor..."
    ssh bmericc@192.168.0.82 "sudo git -C ${DIR} pull origin main"
    echo "  ✓ Tema güncellendi"

    echo "  ▶ ${site} W3 Total Cache temizleniyor (${CONTAINER})..."
    if ssh bmericc@192.168.0.82 "sudo docker exec ${CONTAINER} wp w3-total-cache flush all --allow-root"; then
        echo "  ✓ Cache temizlendi"
    else
        echo "  ⚠ Cache temizlenemedi (${site}) — container adını/wp-cli kurulumunu kontrol et"
    fi
done

echo ""
echo "✅ Deploy tamamlandı."
