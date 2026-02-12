#!/bin/bash

echo "=== Build started at $(date) ==="

# Verificar si imgproxy está configurado
if [ -n "$IMGPROXY_URL" ] && [ -n "$IMGPROXY_KEY" ] && [ -n "$IMGPROXY_SALT" ]; then
    echo "[Build] IMGPROXY_URL: ${#IMGPROXY_URL} chars"
    echo "[Build] IMGPROXY_KEY: ${#IMGPROXY_KEY} chars"
    echo "[Build] IMGPROXY_SALT: ${#IMGPROXY_SALT} chars"
    echo "[Build] Imgproxy enabled"
else
    echo "[Build] WARNING: IMGPROXY variables not set, using fallback"
    echo "[Build] IMGPROXY_URL=${IMGPROXY_URL:-NOT SET}"
    echo "[Build] IMGPROXY_KEY=${IMGPROXY_KEY:-NOT SET}"
    echo "[Build] IMGPROXY_SALT=${IMGPROXY_SALT:-NOT SET}"
fi

echo "=== Running bun build ==="
bun run build

echo "=== Build completed at $(date) ==="
