const fs = require('fs');
const path = require('path');

async function checkHeroImages() {
  const articlesDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  const missingHero = [];
  const hasHero = [];
  
  // Find all article directories
  const articleDirs = [];
  
  function walkDir(dir) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });
    for (const entry of entries) {
      const fullPath = path.join(dir, entry.name);
      if (entry.isDirectory()) {
        // Check if this is an article directory (contains index.md)
        const hasIndex = fs.existsSync(path.join(fullPath, 'index.md'));
        if (hasIndex) {
          articleDirs.push(fullPath);
        } else {
          walkDir(fullPath);
        }
      }
    }
  }
  
  walkDir(articlesDir);
  
  console.log(`Found ${articleDirs.length} article directories`);
  
  for (const articleDir of articleDirs) {
    const indexFile = path.join(articleDir, 'index.md');
    const heroFile = path.join(articleDir, 'hero.jpg');
    
    if (!fs.existsSync(indexFile)) continue;
    
    const content = fs.readFileSync(indexFile, 'utf8');
    
    // Check if heroImage is in frontmatter
    const hasHeroImage = content.includes('heroImage:');
    
    // Check if hero.jpg file exists
    const hasHeroFile = fs.existsSync(heroFile);
    
    if (hasHeroImage && !hasHeroFile) {
      missingHero.push(articleDir);
    } else if (hasHeroImage && hasHeroFile) {
      hasHero.push(articleDir);
    }
  }
  
  console.log(`\nArticles with heroImage in frontmatter: ${hasHero.length}`);
  console.log(`Articles missing hero.jpg file: ${missingHero.length}`);
  
  if (missingHero.length > 0) {
    console.log('\nArticles missing hero.jpg:');
    missingHero.forEach(dir => console.log(`  ${dir}`));
    
    // Check for other images in these directories
    console.log('\nChecking for other images in directories missing hero.jpg:');
    for (const dir of missingHero) {
      const files = fs.readdirSync(dir);
      const images = files.filter(f => 
        f.endsWith('.jpg') || f.endsWith('.png') || f.endsWith('.webp')
      );
      if (images.length > 0) {
        console.log(`  ${dir}: ${images.length} images found`);
        console.log(`    Images: ${images.join(', ')}`);
      } else {
        console.log(`  ${dir}: NO images found`);
      }
    }
  }
  
  return { missingHero, hasHero };
}

checkHeroImages().catch(console.error);