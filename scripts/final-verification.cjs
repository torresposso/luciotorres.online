const fs = require('fs');
const path = require('path');

async function verifyAllHeroImages() {
  const articlesDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  const results = {
    totalArticles: 0,
    perfectMatch: 0,
    hasHeroButNoImages: 0,
    issues: []
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
    
    if (!fs.existsSync(indexFile) || !fs.existsSync(heroFile)) {
      results.issues.push({
        dir: articleDir,
        issue: 'Missing index.md or hero.jpg'
      });
      continue;
    }
    
    const content = fs.readFileSync(indexFile, 'utf8');
    
    // Check heroImage in frontmatter
    if (!content.includes('heroImage:')) {
      results.issues.push({
        dir: articleDir,
        issue: 'No heroImage in frontmatter'
      });
      continue;
    }
    
    // Extract first image reference (handling both regular and link-wrapped images)
    // Pattern 1: ![](./image.jpg)
    // Pattern 2: [![](./image.jpg)](link)
    const imageRegex = /(?:!\[.*?\]\(\.\/)?([^)]+\.(jpg|png|webp))\)/gi;
    const matches = [...content.matchAll(imageRegex)];
    
    if (matches.length === 0) {
      // Article has hero.jpg but no image references in body
      results.hasHeroButNoImages++;
      continue;
    }
    
    const firstImageName = matches[0][1]; // Get the filename
    
    // Check if hero.jpg is the first image
    if (firstImageName !== 'hero.jpg') {
      results.issues.push({
        dir: articleDir,
        issue: `Hero.jpg is not first image (first is: ${firstImageName})`
      });
    } else {
      results.perfectMatch++;
    }
  }
  
  console.log('=== FINAL VERIFICATION ===');
  console.log(`Total articles: ${results.totalArticles}`);
  console.log(`Perfect matches (hero.jpg is first image): ${results.perfectMatch}`);
  console.log(`Articles with hero but no body images: ${results.hasHeroButNoImages}`);
  console.log(`Issues found: ${results.issues.length}`);
  
  if (results.issues.length > 0) {
    console.log('\n=== ISSUES ===');
    results.issues.forEach(issue => {
      console.log(`  ${issue.dir}: ${issue.issue}`);
    });
  }
  
  // Calculate success rate
  const successRate = ((results.perfectMatch + results.hasHeroButNoImages) / results.totalArticles * 100).toFixed(2);
  console.log(`\nSuccess rate: ${successRate}%`);
  
  return results;
}

verifyAllHeroImages().catch(console.error);