const { readFileSync, writeFileSync, readdirSync, existsSync } = require('fs');
const { join } = require('path');

const OLD_PATTERN = /https:\/\/bundled-saddlebag-tnqki7w\.t3\.storageapi\.dev\//g;
const NEW_BASE_URL = 'https://cdn.luciotorres.online/unsafe/plain/s3://bundled-saddlebag-tnqki7w/';

function updateFile(filePath) {
  if (!filePath.endsWith('.md') && !filePath.endsWith('.mdx')) return;
  
  if (!existsSync(filePath)) return;
  
  const content = readFileSync(filePath, 'utf8');
  const newContent = content.replace(OLD_PATTERN, NEW_BASE_URL);
  
  if (content !== newContent) {
    writeFileSync(filePath, newContent);
    console.log(`✅ Updated: ${filePath}`);
  }
}

function walkDir(dir) {
  const files = readdirSync(dir, { withFileTypes: true });
  
  for (const file of files) {
    const fullPath = join(dir, file.name);
    
    if (file.isDirectory()) {
      walkDir(fullPath);
    } else if (file.isFile()) {
      updateFile(fullPath);
    }
  }
}

const contentDir = './src/content/articulos';
console.log('🔄 Updating image URLs...\n');
walkDir(contentDir);
console.log('\n✨ Done! All URLs updated to use imgproxy.');
