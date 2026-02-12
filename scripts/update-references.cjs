#!/usr/bin/env node

/**
 * Script para actualizar referencias de imágenes en archivos Markdown
 * Cambia extensiones .jpg/.png a .webp en referencias
 */

const fs = require('fs').promises;
const path = require('path');
const { glob } = require('glob');

// Configuración
const CONFIG = {
  sourceDir: '../src/content',
  dryRun: true, // Por defecto dry run
  testMode: true, // Solo probar con algunos archivos
  testLimit: 3,
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
 * Encontrar archivos Markdown
 */
async function findMarkdownFiles() {
  console.log(`${colors.blue}🔍${colors.reset} Buscando archivos Markdown...`);
  
  const files = await glob(`${CONFIG.sourceDir}/**/*.md`, {
    ignore: ['**/node_modules/**', '**/.git/**']
  });
  
  console.log(`${colors.green}✓${colors.reset} Encontrados: ${files.length} archivos`);
  
  if (CONFIG.testMode) {
    console.log(`${colors.yellow}⚠${colors.reset} Modo prueba - limitando a ${CONFIG.testLimit} archivos`);
    return files.slice(0, CONFIG.testLimit);
  }
  
  return files;
}

/**
 * Actualizar referencias en un archivo Markdown
 */
async function updateFileReferences(filePath) {
  try {
    const content = await fs.readFile(filePath, 'utf8');
    const relativePath = path.relative(CONFIG.sourceDir, filePath);
    
    // Patrones a buscar
    const patterns = [
      // ![](./imagen.jpg)
      /!\[\]\((\.\/[^)]+\.(jpg|jpeg|png))\)/gi,
      // heroImage: "./imagen.jpg"
      /heroImage:\s*["'](\.\/[^"']+\.(jpg|jpeg|png))["']/gi,
      // src="./imagen.jpg"
      /src=["'](\.\/[^"']+\.(jpg|jpeg|png))["']/gi,
    ];
    
    let updatedContent = content;
    let changes = 0;
    
    for (const pattern of patterns) {
      let match;
      const matches = [];
      
      // Reset lastIndex para cada búsqueda
      pattern.lastIndex = 0;
      
      while ((match = pattern.exec(content)) !== null) {
        matches.push({
          fullMatch: match[0],
          filename: match[1],
          extension: match[2],
          index: match.index,
        });
      }
      
      // Procesar matches en orden inverso (para no afectar índices)
      for (const match of matches.reverse()) {
        const newFilename = match.filename.replace(/\.(jpg|jpeg|png)$/i, '.webp');
        const newMatch = match.fullMatch.replace(match.filename, newFilename);
        
        updatedContent = updatedContent.substring(0, match.index) + 
                        newMatch + 
                        updatedContent.substring(match.index + match.fullMatch.length);
        
        changes++;
        
        console.log(`  ${colors.cyan}${match.filename} → ${newFilename}${colors.reset}`);
      }
    }
    
    if (changes > 0) {
      console.log(`${colors.green}✓${colors.reset} ${relativePath}: ${changes} cambios`);
      
      if (!CONFIG.dryRun) {
        await fs.writeFile(filePath, updatedContent, 'utf8');
      }
    } else {
      console.log(`${colors.yellow}⚠${colors.reset} ${relativePath}: Sin cambios necesarios`);
    }
    
    return { success: true, changes };
    
  } catch (error) {
    console.error(`${colors.red}✗${colors.reset} Error en ${filePath}: ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Renombrar archivos de imagen a .webp
 */
async function renameImageFiles() {
  console.log(`\n${colors.blue}📁${colors.reset} Buscando archivos de imagen para renombrar...`);
  
  const patterns = ['**/*.jpg', '**/*.jpeg', '**/*.png'];
  let renamed = 0;
  let errors = 0;
  
  for (const pattern of patterns) {
    const files = await glob(`${CONFIG.sourceDir}/${pattern}`, {
      ignore: ['**/node_modules/**', '**/.git/**']
    });
    
    for (const filePath of files) {
      try {
        // Verificar si es realmente WebP
        const buffer = await fs.readFile(filePath);
        const isWebP = buffer.slice(0, 4).toString() === 'RIFF' && 
                      buffer.slice(8, 12).toString() === 'WEBP';
        
        if (isWebP) {
          const newPath = filePath.replace(/\.(jpg|jpeg|png)$/i, '.webp');
          const relativePath = path.relative(CONFIG.sourceDir, filePath);
          const newRelativePath = path.relative(CONFIG.sourceDir, newPath);
          
          console.log(`  ${colors.cyan}${relativePath} → ${newRelativePath}${colors.reset}`);
          
          if (!CONFIG.dryRun) {
            await fs.rename(filePath, newPath);
          }
          
          renamed++;
        }
      } catch (error) {
        console.error(`${colors.red}✗${colors.reset} Error renombrando ${filePath}: ${error.message}`);
        errors++;
      }
    }
  }
  
  return { renamed, errors };
}

/**
 * Función principal
 */
async function main() {
  console.log(`${colors.blue}🔄 ACTUALIZACIÓN DE REFERENCIAS${colors.reset}`);
  console.log(`${colors.cyan}Directorio:${colors.reset} ${CONFIG.sourceDir}`);
  console.log(`${colors.cyan}Dry run:${colors.reset} ${CONFIG.dryRun ? 'Sí' : 'No'}`);
  console.log(`${colors.cyan}Modo prueba:${colors.reset} ${CONFIG.testMode ? `Sí (${CONFIG.testLimit} archivos)` : 'No'}`);
  console.log('-'.repeat(60));
  
  const startTime = Date.now();
  let totalChanges = 0;
  let processedFiles = 0;
  let fileErrors = 0;
  
  try {
    // 1. Encontrar archivos Markdown
    const markdownFiles = await findMarkdownFiles();
    
    if (markdownFiles.length === 0) {
      console.log(`${colors.yellow}⚠ No se encontraron archivos Markdown${colors.reset}`);
      return;
    }
    
    // 2. Actualizar referencias en archivos Markdown
    console.log(`\n${colors.blue}📄${colors.reset} Actualizando referencias en archivos Markdown...`);
    
    for (const filePath of markdownFiles) {
      const result = await updateFileReferences(filePath);
      
      if (result.success) {
        processedFiles++;
        totalChanges += result.changes;
      } else {
        fileErrors++;
      }
    }
    
    // 3. Renombrar archivos de imagen
    const renameResult = await renameImageFiles();
    
    // 4. Reporte final
    const duration = Date.now() - startTime;
    const seconds = (duration / 1000).toFixed(1);
    
    console.log('\n' + '='.repeat(60));
    console.log(`${colors.green}📊 REPORTE FINAL${colors.reset}`);
    console.log('='.repeat(60));
    console.log(`${colors.cyan}Archivos procesados:${colors.reset} ${processedFiles}`);
    console.log(`${colors.cyan}Referencias actualizadas:${colors.reset} ${totalChanges}`);
    console.log(`${colors.cyan}Archivos renombrados:${colors.reset} ${renameResult.renamed}`);
    console.log(`${colors.red}Errores:${colors.reset} ${fileErrors + renameResult.errors}`);
    console.log(`${colors.cyan}Tiempo total:${colors.reset} ${seconds}s`);
    
    if (CONFIG.dryRun) {
      console.log(`\n${colors.yellow}⚠ NOTA: Esto fue un dry run. No se modificaron archivos.${colors.reset}`);
      console.log(`${colors.yellow}   Ejecuta con --apply para aplicar los cambios.${colors.reset}`);
    }
    
    if (CONFIG.testMode) {
      console.log(`${colors.yellow}⚠ NOTA: Modo prueba activado. Solo se procesaron ${CONFIG.testLimit} archivos.${colors.reset}`);
      console.log(`${colors.yellow}   Cambia testMode a false para procesar todo.${colors.reset}`);
    }
    
    console.log('='.repeat(60));
    
  } catch (error) {
    console.error(`${colors.red}❌ ERROR CRÍTICO:${colors.reset} ${error.message}`);
    console.error(error.stack);
  }
}

// Manejar argumentos de línea de comandos
const args = process.argv.slice(2);
if (args.includes('--apply') || args.includes('-a')) {
  CONFIG.dryRun = false;
  console.log(`${colors.green}✅ MODO APLICACIÓN ACTIVADO - Se modificarán archivos${colors.reset}`);
}

if (args.includes('--full') || args.includes('-f')) {
  CONFIG.testMode = false;
  console.log(`${colors.green}✅ MODO COMPLETO ACTIVADO - Procesando todos los archivos${colors.reset}`);
}

// Ejecutar
if (require.main === module) {
  main().catch(console.error);
}

module.exports = { main };