#!/usr/bin/env node

/**
 * Script de prueba simplificado
 */

const sharp = require('sharp');
const fs = require('fs').promises;
const path = require('path');
const { glob } = require('glob');

// Configuración
const CONFIG = {
  sourceDir: '../src/content',
  backupDir: '../backup/original-images',
  quality: 85,
  testLimit: 5, // Solo 5 imágenes para prueba rápida
};

// Colores para consola
const colors = {
  reset: '\x1b[0m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  red: '\x1b[31m',
  blue: '\x1b[34m',
  cyan: '\x1b[36m',
};

/**
 * Encontrar imágenes
 */
async function findImages() {
  console.log(`${colors.blue}🔍${colors.reset} Buscando imágenes...`);
  
  const patterns = ['**/*.jpg', '**/*.jpeg', '**/*.png'];
  let allImages = [];
  
  for (const pattern of patterns) {
    const images = await glob(`${CONFIG.sourceDir}/${pattern}`, { 
      ignore: ['**/node_modules/**', '**/.git/**']
    });
    allImages = [...allImages, ...images];
  }
  
  console.log(`${colors.green}✓${colors.reset} Encontradas: ${allImages.length} imágenes`);
  
  // Ordenar por tamaño (más grandes primero)
  const imagesWithSize = await Promise.all(
    allImages.map(async (imgPath) => {
      try {
        const size = (await fs.stat(imgPath)).size;
        return { path: imgPath, size };
      } catch {
        return { path: imgPath, size: 0 };
      }
    })
  );
  
  imagesWithSize.sort((a, b) => b.size - a.size);
  
  // Limitar para prueba
  return imagesWithSize.slice(0, CONFIG.testLimit).map(img => img.path);
}

/**
 * Probar conversión de una imagen
 */
async function testConversion(imagePath) {
  try {
    console.log(`\n${colors.cyan}🧪 Probando:${colors.reset} ${path.relative(CONFIG.sourceDir, imagePath)}`);
    
    // Leer metadatos
    const metadata = await sharp(imagePath).metadata();
    const originalSize = (await fs.stat(imagePath)).size;
    
    console.log(`  ${colors.cyan}Tamaño original:${colors.reset} ${formatBytes(originalSize)}`);
    console.log(`  ${colors.cyan}Dimensiones:${colors.reset} ${metadata.width}x${metadata.height}`);
    console.log(`  ${colors.cyan}Formato:${colors.reset} ${metadata.format}`);
    console.log(`  ${colors.cyan}Transparencia:${colors.reset} ${metadata.hasAlpha ? 'Sí' : 'No'}`);
    
    // Crear archivo temporal para conversión
    const tempPath = imagePath + '.test.webp';
    
    // Configuración WebP
    const webpOptions = {
      quality: CONFIG.quality,
      lossless: false,
      effort: 6,
    };
    
    if (metadata.hasAlpha) {
      webpOptions.alphaQuality = 80;
    }
    
    // Convertir
    await sharp(imagePath)
      .webp(webpOptions)
      .toFile(tempPath);
    
    const optimizedSize = (await fs.stat(tempPath)).size;
    const reduction = ((originalSize - optimizedSize) / originalSize * 100).toFixed(1);
    
    console.log(`  ${colors.green}Tamaño WebP:${colors.reset} ${formatBytes(optimizedSize)}`);
    console.log(`  ${colors.green}Reducción:${colors.reset} ${reduction}%`);
    
    // Limpiar archivo temporal
    await fs.unlink(tempPath);
    
    return {
      success: true,
      originalSize,
      optimizedSize,
      reduction,
    };
    
  } catch (error) {
    console.error(`${colors.red}✗ Error:${colors.reset} ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Formatear bytes
 */
function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

/**
 * Función principal
 */
async function main() {
  console.log(`${colors.blue}🧪 PRUEBA DE OPTIMIZACIÓN DE IMÁGENES${colors.reset}`);
  console.log(`${colors.cyan}Calidad WebP:${colors.reset} ${CONFIG.quality}%`);
  console.log(`${colors.cyan}Límite prueba:${colors.reset} ${CONFIG.testLimit} imágenes`);
  console.log('-'.repeat(60));
  
  try {
    // 1. Encontrar imágenes
    const images = await findImages();
    
    if (images.length === 0) {
      console.log(`${colors.yellow}⚠ No se encontraron imágenes${colors.reset}`);
      return;
    }
    
    // 2. Probar conversión
    let totalOriginal = 0;
    let totalOptimized = 0;
    let successes = 0;
    let failures = 0;
    
    for (const imagePath of images) {
      const result = await testConversion(imagePath);
      
      if (result.success) {
        totalOriginal += result.originalSize;
        totalOptimized += result.optimizedSize;
        successes++;
      } else {
        failures++;
      }
    }
    
    // 3. Reporte
    console.log('\n' + '='.repeat(60));
    console.log(`${colors.green}📊 REPORTE DE PRUEBA${colors.reset}`);
    console.log('='.repeat(60));
    console.log(`${colors.cyan}Imágenes probadas:${colors.reset} ${images.length}`);
    console.log(`${colors.green}Éxitos:${colors.reset} ${successes}`);
    console.log(`${colors.red}Fallos:${colors.reset} ${failures}`);
    
    if (successes > 0) {
      const totalReduction = ((totalOriginal - totalOptimized) / totalOriginal * 100).toFixed(1);
      console.log(`\n${colors.cyan}Tamaño total original:${colors.reset} ${formatBytes(totalOriginal)}`);
      console.log(`${colors.cyan}Tamaño total optimizado:${colors.reset} ${formatBytes(totalOptimized)}`);
      console.log(`${colors.green}Reducción total:${colors.reset} ${totalReduction}%`);
    }
    
    console.log('='.repeat(60));
    console.log(`${colors.green}✅ Prueba completada${colors.reset}`);
    
  } catch (error) {
    console.error(`${colors.red}❌ ERROR:${colors.reset} ${error.message}`);
  }
}

// Ejecutar
main().catch(console.error);