#!/usr/bin/env node

/**
 * Script de restauración de backup de imágenes
 * Restaura imágenes originales desde el directorio de backup
 */

const fs = require('fs').promises;
const path = require('path');
const { glob } = require('glob');

// Configuración
const CONFIG = {
  sourceDir: './src/content',
  backupDir: './backup/original-images',
  dryRun: false,
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
 * Encontrar todos los archivos de backup
 */
async function findBackupFiles() {
  console.log(`${colors.blue}🔍${colors.reset} Buscando archivos de backup en ${CONFIG.backupDir}...`);
  
  const patterns = ['**/*'];
  let allFiles = [];
  
  for (const pattern of patterns) {
    const files = await glob(pattern, { 
      cwd: CONFIG.backupDir,
      absolute: true,
      ignore: ['**/node_modules/**', '**/.git/**'],
      nodir: true,
    });
    allFiles = [...allFiles, ...files];
  }
  
  return allFiles;
}

/**
 * Restaurar un archivo desde backup
 */
async function restoreFile(backupPath) {
  try {
    const relativePath = path.relative(CONFIG.backupDir, backupPath);
    const targetPath = path.join(CONFIG.sourceDir, relativePath);
    
    // Verificar si el archivo destino existe
    let targetExists = false;
    try {
      await fs.access(targetPath);
      targetExists = true;
    } catch {
      targetExists = false;
    }
    
    // Crear directorio destino si no existe
    await fs.mkdir(path.dirname(targetPath), { recursive: true });
    
    if (!CONFIG.dryRun) {
      // Copiar archivo de backup a destino
      await fs.copyFile(backupPath, targetPath);
    }
    
    console.log(`${colors.green}✓${colors.reset} Restaurado: ${relativePath}`);
    if (targetExists) {
      console.log(`  ${colors.yellow}⚠ Reemplazado archivo existente${colors.reset}`);
    }
    
    return { success: true, replaced: targetExists };
  } catch (error) {
    console.error(`${colors.red}✗${colors.reset} Error restaurando: ${backupPath}`);
    console.error(`  ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Función principal
 */
async function main() {
  console.log(`${colors.blue}🔄 INICIANDO RESTAURACIÓN DESDE BACKUP${colors.reset}`);
  console.log(`${colors.cyan}Directorio backup:${colors.reset} ${CONFIG.backupDir}`);
  console.log(`${colors.cyan}Directorio destino:${colors.reset} ${CONFIG.sourceDir}`);
  console.log(`${colors.cyan}Dry run:${colors.reset} ${CONFIG.dryRun ? 'Sí' : 'No'}`);
  console.log('-'.repeat(60));
  
  const startTime = Date.now();
  let restored = 0;
  let errors = 0;
  
  try {
    // 1. Encontrar archivos de backup
    const backupFiles = await findBackupFiles();
    
    if (backupFiles.length === 0) {
      console.log(`${colors.yellow}⚠ No se encontraron archivos de backup${colors.reset}`);
      return;
    }
    
    console.log(`${colors.cyan}📁 Archivos de backup encontrados:${colors.reset} ${backupFiles.length}`);
    
    // 2. Restaurar archivos
    for (let i = 0; i < backupFiles.length; i++) {
      const backupFile = backupFiles[i];
      
      const result = await restoreFile(backupFile);
      if (result.success) {
        restored++;
      } else {
        errors++;
      }
      
      // Mostrar progreso
      const progress = ((i + 1) / backupFiles.length * 100).toFixed(1);
      console.log(`${colors.cyan}📊${colors.reset} Progreso: ${i + 1}/${backupFiles.length} (${progress}%)`);
    }
    
    // 3. Finalizar
    const duration = Date.now() - startTime;
    const minutes = Math.floor(duration / 60000);
    const seconds = ((duration % 60000) / 1000).toFixed(1);
    
    console.log('\n' + '='.repeat(60));
    console.log(`${colors.green}✅ RESTAURACIÓN COMPLETADA${colors.reset}`);
    console.log('='.repeat(60));
    console.log(`${colors.cyan}Archivos restaurados:${colors.reset} ${restored}`);
    console.log(`${colors.red}Errores:${colors.reset} ${errors}`);
    console.log(`${colors.cyan}Tiempo total:${colors.reset} ${minutes}m ${seconds}s`);
    
    if (CONFIG.dryRun) {
      console.log(`${colors.yellow}⚠ NOTA: Esto fue un dry run. No se restauraron archivos.${colors.reset}`);
      console.log(`${colors.yellow}   Ejecuta sin --dry-run para aplicar los cambios.${colors.reset}`);
    }
    
    console.log('='.repeat(60));
    
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
  console.log(`${colors.yellow}⚠ MODO DRY RUN ACTIVADO - No se restaurarán archivos${colors.reset}`);
}

// Ejecutar
if (require.main === module) {
  main().catch(console.error);
}

module.exports = { main };