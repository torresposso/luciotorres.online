const fs = require('fs');
const path = require('path');

async function analyzeHeroImages() {
  const articlesDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  const results = {
    totalArticles: 0,
    hasHeroImageFrontmatter: 0,
    hasHeroFile: 0,
    hasBoth: 0,
    articlesWithImageReferences: 0,
    heroMatchesFirstImage: 0,
    heroDifferentFromFirstImage: 0,
    noImageReferences: 0,
    details: []
  };
  
  // Find all article directories
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
  
  walkDir(articlesDir);
  results.totalArticles = articleDirs.length;
  
  for (const articleDir of articleDirs) {
    const indexFile = path.join(articleDir, 'index.md');
    const heroFile = path.join(articleDir, 'hero.jpg');
    
    if (!fs.existsSync(indexFile)) continue;
    
    const content = fs.readFileSync(indexFile, 'utf8');
    
    // Check frontmatter
    const hasHeroImageFrontmatter = content.includes('heroImage:');
    if (hasHeroImageFrontmatter) results.hasHeroImageFrontmatter++;
    
    // Check hero file
    const hasHeroFile = fs.existsSync(heroFile);
    if (hasHeroFile) results.hasHeroFile++;
    
    if (hasHeroImageFrontmatter && hasHeroFile) results.hasBoth++;
    
    // Extract first image reference from markdown body
    const imageRegex = /!\[.*?\]\((\.\/)?([^)]+\.(jpg|png|webp))\)/gi;
    const matches = [...content.matchAll(imageRegex)];
    
    if (matches.length > 0) {
      results.articlesWithImageReferences++;
      const firstImage = matches[0][2]; // Get the filename
      
      // Check if hero.jpg exists and compare with first image
      if (hasHeroFile) {
        if (firstImage === 'hero.jpg') {
          results.heroMatchesFirstImage++;
        } else {
          results.heroDifferentFromFirstImage++;
          results.details.push({
            dir: articleDir,
            firstImage,
            heroFile: 'hero.jpg',
            status: 'different'
          });
        }
      }
    } else {
      results.noImageReferences++;
    }
  }
  
  console.log('=== HERO IMAGE ANALYSIS ===');
  console.log(`Total articles: ${results.totalArticles}`);
  console.log(`Articles with heroImage in frontmatter: ${results.hasHeroImageFrontmatter}`);
  console.log(`Articles with hero.jpg file: ${results.hasHeroFile}`);
  console.log(`Articles with both: ${results.hasBoth}`);
  console.log(`\nArticles with image references in body: ${results.articlesWithImageReferences}`);
  console.log(`Articles without image references: ${results.noImageReferences}`);
  console.log(`\nHero.jpg matches first image in article: ${results.heroMatchesFirstImage}`);
  console.log(`Hero.jpg different from first image: ${results.heroDifferentFromFirstImage}`);
  
  if (results.heroDifferentFromFirstImage > 0) {
    console.log('\n=== ARTICLES WHERE HERO.JPG IS NOT THE FIRST IMAGE ===');
    results.details.slice(0, 10).forEach(detail => {
      console.log(`  ${detail.dir}`);
      console.log(`    First image in article: ${detail.firstImage}`);
      console.log(`    Hero file: ${detail.heroFile}`);
    });
    
    if (results.heroDifferentFromFirstImage > 10) {
      console.log(`  ... and ${results.heroDifferentFromFirstImage - 10} more`);
    }
  }
  
  return results;
}

analyzeHeroImages().catch(console.error);