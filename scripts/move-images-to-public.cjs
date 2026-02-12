const fs = require('fs');
const path = require('path');

async function moveImagesToPublic() {
  const contentDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  const publicDir = path.join(__dirname, '..', 'public', 'articulos');
  
  console.log('=== Moving images from src/content to public/ ===\n');
  
  // Create public/articulos directory if it doesn't exist
  if (!fs.existsSync(publicDir)) {
    fs.mkdirSync(publicDir, { recursive: true });
    console.log(`Created: ${publicDir}`);
  }
  
  // Find all article directories with images
  const articleDirs = [];
  
  function walkDir(dir) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });
    for (const entry of entries) {
      const fullPath = path.join(dir, entry.name);
      if (entry.isDirectory()) {
        const hasIndex = fs.existsSync(path.join(fullPath, 'index.md'));
        if (hasIndex) {
          articleDirs.push(fullPath);
        } else {
          walkDir(fullPath);
        }
      }
    }
  }
  
  walkDir(contentDir);
  
  console.log(`Found ${articleDirs.length} article directories\n`);
  
  let imageCount = 0;
  let errorCount = 0;
  
  for (const articleDir of articleDirs) {
    const files = fs.readdirSync(articleDir);
    const articlePath = path.relative(contentDir, articleDir);
    const targetDir = path.join(publicDir, articlePath);
    
    // Create target directory structure
    if (!fs.existsSync(targetDir)) {
      fs.mkdirSync(targetDir, { recursive: true });
    }
    
    // Find and copy image files
    const imageFiles = files.filter(f => 
      f.endsWith('.jpg') || f.endsWith('.png') || f.endsWith('.webp')
    );
    
    for (const imageFile of imageFiles) {
      const sourcePath = path.join(articleDir, imageFile);
      const targetPath = path.join(targetDir, imageFile);
      
      try {
        fs.copyFileSync(sourcePath, targetPath);
        imageCount++;
        
        if (imageCount <= 20) {
          console.log(`Copied: ${articlePath}/${imageFile}`);
        } else if (imageCount === 21) {
          console.log('... and more images');
        }
      } catch (error) {
        console.error(`Error copying ${articlePath}/${imageFile}:`, error.message);
        errorCount++;
      }
    }
  }
  
  console.log(`\n=== SUMMARY ===`);
  console.log(`Images copied: ${imageCount}`);
  console.log(`Errors: ${errorCount}`);
  console.log(`\nImages are now in: public/articulos/`);
  
  // Now update the markdown files to use absolute paths
  console.log('\n=== Updating markdown references ===\n');
  
  let updatedCount = 0;
  
  for (const articleDir of articleDirs) {
    const indexFile = path.join(articleDir, 'index.md');
    
    if (!fs.existsSync(indexFile)) continue;
    
    const content = fs.readFileSync(indexFile, 'utf8');
    const articlePath = path.relative(contentDir, articleDir);
    const oldContent = content;
    
    // Replace relative image paths with absolute paths
    // Pattern: ![](./image.jpg) -> ![](/articulos/year/month/slug/image.jpg)
    const updatedContent = content.replace(
      /!\[([^\]]*)\]\(\.\/([^)]+\.(?:jpg|png|webp))\)/g,
      (match, alt, imageName) => {
        return `![${alt}](/articulos/${articlePath}/${imageName})`;
      }
    );
    
    // Write updated content if changes were made
    if (updatedContent !== oldContent) {
      fs.writeFileSync(indexFile, updatedContent);
      updatedCount++;
    }
  }
  
  console.log(`Markdown files updated: ${updatedCount}`);
  
  console.log('\n✅ Done! Images are now in public/ and markdown references updated.');
  console.log('\nNext steps:');
  console.log('1. Run npm run build to rebuild the site');
  console.log('2. Test that images load correctly');
  console.log('3. Optionally remove images from src/content/articulos/ to save space');
}

moveImagesToPublic().catch(console.error);