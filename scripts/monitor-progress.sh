#!/bin/bash

# Script para monitorear el progreso de la optimización de imágenes

echo "🔍 Monitoreando progreso de optimización de imágenes..."
echo "=================================================="

while true; do
  clear
  
  # Contar imágenes totales
  TOTAL_IMAGES=$(find ../src/content -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.webp" \) | wc -l)
  
  # Contar imágenes WebP (convertidas)
  WEBP_COUNT=$(find ../src/content -type f -name "*.webp" | wc -l)
  
  # Contar imágenes JPG/PNG (por convertir)
  JPG_PNG_COUNT=$(find ../src/content -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) | wc -l)
  
  # Calcular porcentaje
  if [ $TOTAL_IMAGES -gt 0 ]; then
    PERCENTAGE=$((WEBP_COUNT * 100 / TOTAL_IMAGES))
  else
    PERCENTAGE=0
  fi
  
  # Tamaño del directorio de backup
  BACKUP_SIZE=$(du -sh ../backup/original-images 2>/dev/null | cut -f1)
  
  # Tamaño del directorio source
  SOURCE_SIZE=$(du -sh ../src/content 2>/dev/null | cut -f1)
  
  echo "📊 ESTADO ACTUAL"
  echo "================="
  echo ""
  echo "📁 Imágenes totales:      $TOTAL_IMAGES"
  echo "✅ Convertidas a WebP:    $WEBP_COUNT"
  echo "⏳ Por convertir:         $JPG_PNG_COUNT"
  echo "📈 Progreso:              $PERCENTAGE%"
  echo ""
  echo "💾 Tamaño backup:         ${BACKUP_SIZE:-0B}"
  echo "💾 Tamaño src/content:    ${SOURCE_SIZE:-0B}"
  echo ""
  echo "🕐 Última actualización:  $(date '+%H:%M:%S')"
  echo ""
  echo "=================================================="
  echo "Presiona Ctrl+C para salir"
  
  sleep 5
done