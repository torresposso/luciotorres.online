const fs = require('fs');
const path = require('path');

async function fixHeroImages() {
  const articlesDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  const results = {
    totalProcessed: 0,
    fixed: 0,
    skipped: 0,
    errors: 0,
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
  
  console.log(`Processing ${articleDirs.length} articles...\n`);
  
  for (const articleDir of articleDirs) {
    try {
      results.totalProcessed++;
      const indexFile = path.join(articleDir, 'index.md');
      const heroFile = path.join(articleDir, 'hero.jpg');
      
      if (!fs.existsSync(indexFile)) {
        results.skipped++;
        continue;
      }
      
      let content = fs.readFileSync(indexFile, 'utf8');
      
      // Extract first image reference from markdown body
      const imageRegex = /!\[.*?\]\((\.\/)?([^)]+\.(jpg|png|webp))\)/gi;
      const matches = [...content.matchAll(imageRegex)];
      
      if (matches.length === 0) {
        // No image references in body - skip
        results.skipped++;
        continue;
      }
      
      const firstImageName = matches[0][2]; // Get the filename
      const firstImageFile = path.join(articleDir, firstImageName);
      
      if (!fs.existsSync(firstImageFile)) {
        results.skipped++;
        continue;
      }
      
      // Check if hero.jpg already exists and is different from first image
      const heroExists = fs.existsSync(heroFile);
      
      if (firstImageName === 'hero.jpg') {
        // hero.jpg is already the first image - skip
        results.skipped++;
        continue;
      }
      
      // Copy first image to hero.jpg
      fs.copyFileSync(firstImageFile, heroFile);
      
      // Update references in markdown if first image was renamed
      // We need to update all references to the first image to point to hero.jpg
      const oldImageRef = `./${firstImageName}`;
      const newImageRef = './hero.jpg';
      
      // Replace all occurrences of the old image reference
      let updatedContent = content;
      let replacementCount = 0;
      
      // Use a regex to match the image reference
      const refRegex = new RegExp(`!\\[.*?\\]\\((\\./)?${firstImageName.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\)`, 'g');
      updatedContent = updatedContent.replace(refRegex, (match) => {
        replacementCount++;
        return match.replace(firstImageName, 'hero.jpg');
      });
      
      // Write updated content if changes were made
      if (updatedContent !== content) {
        fs.writeFileSync(indexFile, updatedContent);
      }
      
      results.fixed++;
      results.details.push({
        dir: articleDir,
        oldFirstImage: firstImageName,
        replacements: replacementCount,
        heroExisted: heroExists
      });
      
      if (results.fixed % 100 === 0) {
        console.log(`Fixed ${results.fixed} articles...`);
      }
      
    } catch (error) {
      console.error(`Error processing ${articleDir}:`, error.message);
      results.errors++;
    }
  }
  
  console.log('\n=== RESULTS ===');
  console.log(`Total articles processed: ${results.totalProcessed}`);
  console.log(`Articles fixed: ${results.fixed}`);
  console.log(`Articles skipped: ${results.skipped}`);
  console.log(`Errors: ${results.errors}`);
  
  if (results.fixed > 0) {
    console.log('\n=== FIXED ARTICLES (first 10) ===');
    results.details.slice(0, 10).forEach(detail => {
      console.log(`  ${detail.dir}`);
      console.log(`    Old first image: ${detail.oldFirstImage}`);
      console.log(`    References updated: ${detail.replacements}`);
      console.log(`    Hero existed before: ${detail.heroExisted ? 'Yes' : 'No'}`);
    });
    
    if (results.fixed > 10) {
      console.log(`  ... and ${results.fixed - 10} more`);
    }
  }
  
  return results;
}

// Run with dry-run mode first
async function dryRun() {
  console.log('=== DRY RUN - Checking what would be fixed ===\n');
  
  const articlesDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  const wouldFix = [];
  
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
  
  for (const articleDir of articleDirs) {
    try {
      const indexFile = path.join(articleDir, 'index.md');
      
      if (!fs.existsSync(indexFile)) continue;
      
      const content = fs.readFileSync(indexFile, 'utf8');
      
      // Extract first image reference from markdown body
      const imageRegex = /!\[.*?\]\((\.\/)?([^)]+\.(jpg|png|webp))\)/gi;
      const matches = [...content.matchAll(imageRegex)];
      
      if (matches.length === 0) continue;
      
      const firstImageName = matches[0][2];
      
      if (firstImageName !== 'hero.jpg') {
        wouldFix.push({
          dir: articleDir,
          firstImage: firstImageName
        });
      }
    } catch (error) {
      // Skip errors in dry run
    }
  }
  
  console.log(`Would fix ${wouldFix.length} articles`);
  console.log('\nFirst 10 articles that would be fixed:');
  wouldFix.slice(0, 10).forEach(item => {
    console.log(`  ${item.dir}`);
    console.log(`    First image: ${item.firstImage}`);
  });
  
  if (wouldFix.length > 10) {
    console.log(`  ... and ${wouldFix.length - 10} more`);
  }
  
  return wouldFix.length;
}

// Check command line argument
const args = process.argv.slice(2);
const isDryRun = args.includes('--dry-run') || args.includes('-d');

if (isDryRun) {
  dryRun().catch(console.error);
} else {
  console.log('WARNING: This will overwrite existing hero.jpg files and update markdown references.');
  console.log('Run with --dry-run first to see what would be changed.\n');
  
  // Ask for confirmation
  const readline = require('readline');
  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
  });
  
  rl.question('Are you sure you want to proceed? (yes/no): ', (answer) => {
    if (answer.toLowerCase() === 'yes') {
      fixHeroImages().catch(console.error).finally(() => rl.close());
    } else {
      console.log('Operation cancelled.');
      rl.close();
    }
  });
}