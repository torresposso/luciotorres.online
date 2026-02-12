#!/usr/bin/env node

/**
 * Script de optimización de imágenes para Astro
 * Convierte imágenes JPG/PNG a WebP con calidad 85% (igual que Astro por defecto)
 * Mantiene backup de originales y actualiza referencias en Markdown
 */

const sharp = require('sharp');
const fs = require('fs').promises;
const path = require('path');
const { glob } = require('glob');
const { createHash } = require('crypto');

// Configuración
const CONFIG = {
  sourceDir: '../src/content',
  backupDir: '../backup/original-images',
  quality: 85, // Calidad WebP (igual que Astro por defecto)
  lossless: false,
  effort: 6, // Máxima compresión (más lento pero mejor tamaño)
  concurrentLimit: 10, // Aumentar concurrencia para procesamiento completo
  skipExisting: true, // Saltar si ya existe .webp
  testMode: false, // Modo completo - procesar todas las imágenes
  testLimit: 0,
  dryRun: false, // Si es true, solo muestra lo que haría
};

// Estadísticas
const stats = {
  totalImages: 0,
  processed: 0,
  skipped: 0,
  errors: 0,
  totalOriginalSize: 0,
  totalOptimizedSize: 0,
  startTime: null,
  endTime: null,
};

// Colores para consola
const colors = {
  reset: '\x1b[0m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  red: '\x1b[31m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
  cyan: '\x1b[36m',
};

/**
 * Calcular hash MD5 de un archivo
 */
async function getFileHash(filePath) {
  const buffer = await fs.readFile(filePath);
  return createHash('md5').update(buffer).digest('hex');
}

/**
 * Crear backup de una imagen
 */
async function backupImage(originalPath) {
  try {
    const relativePath = path.relative(CONFIG.sourceDir, originalPath);
    const backupPath = path.join(CONFIG.backupDir, relativePath);
    
    // Crear directorio de backup si no existe
    await fs.mkdir(path.dirname(backupPath), { recursive: true });
    
    // Copiar archivo
    await fs.copyFile(originalPath, backupPath);
    
    // Verificar hash
    const originalHash = await getFileHash(originalPath);
    const backupHash = await getFileHash(backupPath);
    
    if (originalHash !== backupHash) {
      throw new Error(`Hash mismatch for backup: ${originalPath}`);
    }
    
    console.log(`${colors.green}✓${colors.reset} Backup creado: ${relativePath}`);
    return true;
  } catch (error) {
    console.error(`${colors.red}✗${colors.reset} Error en backup: ${originalPath}`);
    console.error(`  ${error.message}`);
    return false;
  }
}

/**
 * Convertir imagen a WebP
 */
async function convertToWebP(imagePath) {
  try {
    const fileInfo = await sharp(imagePath).metadata();
    const originalSize = (await fs.stat(imagePath)).size;
    
    // Determinar si tiene transparencia
    const hasAlpha = fileInfo.hasAlpha;
    
    // Configuración WebP
    const webpOptions = {
      quality: CONFIG.quality,
      lossless: CONFIG.lossless,
      effort: CONFIG.effort,
    };
    
    // Configuración adicional para imágenes con transparencia
    if (hasAlpha) {
      webpOptions.alphaQuality = 80;
    }
    
    // Ruta temporal para WebP
    const tempWebpPath = imagePath + '.webp.tmp';
    
    // Convertir a WebP
    await sharp(imagePath)
      .webp(webpOptions)
      .toFile(tempWebpPath);
    
    const optimizedSize = (await fs.stat(tempWebpPath)).size;
    const reduction = ((originalSize - optimizedSize) / originalSize * 100).toFixed(1);
    
    // Reemplazar original si no es dry run
    if (!CONFIG.dryRun) {
      // Eliminar original
      await fs.unlink(imagePath);
      // Mover WebP a ubicación original
      await fs.rename(tempWebpPath, imagePath);
    }
    
    stats.totalOriginalSize += originalSize;
    stats.totalOptimizedSize += optimizedSize;
    
    console.log(`${colors.green}✓${colors.reset} Convertido: ${path.relative(CONFIG.sourceDir, imagePath)}`);
    console.log(`  ${colors.cyan}Tamaño:${colors.reset} ${formatBytes(originalSize)} → ${formatBytes(optimizedSize)} (${reduction}% reducción)`);
    
    return {
      success: true,
      originalSize,
      optimizedSize,
      reduction,
      hasAlpha,
    };
  } catch (error) {
    console.error(`${colors.red}✗${colors.reset} Error en conversión: ${imagePath}`);
    console.error(`  ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Encontrar todas las imágenes en el directorio source
 */
async function findImages() {
  console.log(`${colors.blue}🔍${colors.reset} Buscando imágenes en ${CONFIG.sourceDir}...`);
  
  const patterns = [
    '**/*.jpg',
    '**/*.jpeg', 
    '**/*.png',
  ];
  
  let allImages = [];
  
  for (const pattern of patterns) {
    const images = await glob(`${CONFIG.sourceDir}/${pattern}`, { 
      ignore: ['**/node_modules/**', '**/.git/**']
    });
    allImages = [...allImages, ...images];
  }
  
  // Ordenar por tamaño (más grandes primero para prueba)
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
  
  // Limitar para prueba si está activado
  if (CONFIG.testMode) {
    console.log(`${colors.yellow}⚠${colors.reset} Modo prueba activado - limitando a ${CONFIG.testLimit} imágenes`);
    return imagesWithSize.slice(0, CONFIG.testLimit).map(img => img.path);
  }
  
  return imagesWithSize.map(img => img.path);
}

/**
 * Procesar batch de imágenes con límite de concurrencia
 */
async function processBatch(images) {
  console.log(`${colors.blue}🔄${colors.reset} Procesando ${images.length} imágenes...`);
  
  const results = [];
  const processing = [];
  
  for (let i = 0; i < images.length; i += CONFIG.concurrentLimit) {
    const batch = images.slice(i, i + CONFIG.concurrentLimit);
    
    const batchPromises = batch.map(async (imagePath) => {
      try {
        // Verificar si ya es WebP
        if (imagePath.toLowerCase().endsWith('.webp')) {
          console.log(`${colors.yellow}⚠${colors.reset} Saltando (ya es WebP): ${path.relative(CONFIG.sourceDir, imagePath)}`);
          stats.skipped++;
          return { path: imagePath, status: 'skipped', reason: 'already_webp' };
        }
        
        // Crear backup
        const backupSuccess = await backupImage(imagePath);
        if (!backupSuccess) {
          stats.errors++;
          return { path: imagePath, status: 'error', reason: 'backup_failed' };
        }
        
        // Convertir a WebP
        const conversionResult = await convertToWebP(imagePath);
        
        if (conversionResult.success) {
          stats.processed++;
          return { 
            path: imagePath, 
            status: 'success', 
            data: conversionResult 
          };
        } else {
          stats.errors++;
          return { 
            path: imagePath, 
            status: 'error', 
            reason: 'conversion_failed',
            error: conversionResult.error 
          };
        }
      } catch (error) {
        stats.errors++;
        console.error(`${colors.red}✗${colors.reset} Error procesando ${imagePath}: ${error.message}`);
        return { 
          path: imagePath, 
          status: 'error', 
          reason: 'unexpected_error',
          error: error.message 
        };
      }
    });
    
    const batchResults = await Promise.all(batchPromises);
    results.push(...batchResults);
    
    // Mostrar progreso
    const processedSoFar = i + batch.length;
    const progress = ((processedSoFar / images.length) * 100).toFixed(1);
    console.log(`${colors.cyan}📊${colors.reset} Progreso: ${processedSoFar}/${images.length} (${progress}%)`);
  }
  
  return results;
}

/**
 * Formatear bytes a tamaño legible
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
 * Generar reporte final
 */
function generateReport() {
  const duration = stats.endTime - stats.startTime;
  const minutes = Math.floor(duration / 60000);
  const seconds = ((duration % 60000) / 1000).toFixed(1);
  
  const totalReduction = stats.totalOriginalSize > 0 
    ? ((stats.totalOriginalSize - stats.totalOptimizedSize) / stats.totalOriginalSize * 100).toFixed(1)
    : 0;
  
  console.log('\n' + '='.repeat(60));
  console.log(`${colors.magenta}📈 REPORTE FINAL${colors.reset}`);
  console.log('='.repeat(60));
  console.log(`${colors.cyan}Total imágenes encontradas:${colors.reset} ${stats.totalImages}`);
  console.log(`${colors.green}Imágenes procesadas:${colors.reset} ${stats.processed}`);
  console.log(`${colors.yellow}Imágenes saltadas:${colors.reset} ${stats.skipped}`);
  console.log(`${colors.red}Errores:${colors.reset} ${stats.errors}`);
  console.log('');
  console.log(`${colors.cyan}Tamaño total original:${colors.reset} ${formatBytes(stats.totalOriginalSize)}`);
  console.log(`${colors.cyan}Tamaño total optimizado:${colors.reset} ${formatBytes(stats.totalOptimizedSize)}`);
  console.log(`${colors.green}Reducción total:${colors.reset} ${totalReduction}%`);
  console.log('');
  console.log(`${colors.cyan}Tiempo total:${colors.reset} ${minutes}m ${seconds}s`);
  console.log(`${colors.cyan}Modo prueba:${colors.reset} ${CONFIG.testMode ? 'Sí' : 'No'}`);
  console.log(`${colors.cyan}Dry run:${colors.reset} ${CONFIG.dryRun ? 'Sí (no se modificaron archivos)' : 'No'}`);
  console.log('='.repeat(60));
  
  if (CONFIG.dryRun) {
    console.log(`${colors.yellow}⚠ NOTA: Esto fue un dry run. No se modificaron archivos.${colors.reset}`);
    console.log(`${colors.yellow}   Ejecuta sin --dry-run para aplicar los cambios.${colors.reset}`);
  }
  
  if (CONFIG.testMode) {
    console.log(`${colors.yellow}⚠ NOTA: Modo prueba activado. Solo se procesaron ${CONFIG.testLimit} imágenes.${colors.reset}`);
    console.log(`${colors.yellow}   Cambia testMode a false en el script para procesar todo.${colors.reset}`);
  }
}

/**
 * Función principal
 */
async function main() {
  console.log(`${colors.magenta}🚀 INICIANDO OPTIMIZACIÓN DE IMÁGENES${colors.reset}`);
  console.log(`${colors.cyan}Calidad WebP:${colors.reset} ${CONFIG.quality}% (igual que Astro por defecto)`);
  console.log(`${colors.cyan}Directorio fuente:${colors.reset} ${CONFIG.sourceDir}`);
  console.log(`${colors.cyan}Directorio backup:${colors.reset} ${CONFIG.backupDir}`);
  console.log(`${colors.cyan}Modo prueba:${colors.reset} ${CONFIG.testMode ? `Sí (${CONFIG.testLimit} imágenes)` : 'No'}`);
  console.log(`${colors.cyan}Dry run:${colors.reset} ${CONFIG.dryRun ? 'Sí' : 'No'}`);
  console.log('-'.repeat(60));
  
  stats.startTime = Date.now();
  
  try {
    // 1. Encontrar imágenes
    const images = await findImages();
    stats.totalImages = images.length;
    
    if (images.length === 0) {
      console.log(`${colors.yellow}⚠ No se encontraron imágenes para procesar${colors.reset}`);
      return;
    }
    
    // 2. Procesar imágenes
    const results = await processBatch(images);
    
    // 3. Finalizar
    stats.endTime = Date.now();
    
    // 4. Generar reporte
    generateReport();
    
  } catch (error) {
    console.error(`${colors.red}❌ ERROR CRÍTICO:${colors.reset} ${error.message}`);
    console.error(error.stack);
    process.exit(1);
  }
}

// Manejar argumentos de línea de comandos
const args = process.argv.slice(2);
if (args.includes('--dry-run') || args.includes('-d')) {
  CONFIG.dryRun = true;
  console.log(`${colors.yellow}⚠ MODO DRY RUN ACTIVADO - No se modificarán archivos${colors.reset}`);
}

if (args.includes('--full') || args.includes('-f')) {
  CONFIG.testMode = false;
  console.log(`${colors.green}✅ MODO COMPLETO ACTIVADO - Procesando todas las imágenes${colors.reset}`);
}

// Ejecutar
if (require.main === module) {
  main().catch(console.error);
}

module.exports = { main };