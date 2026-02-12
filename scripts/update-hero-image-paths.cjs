const fs = require('fs');
const path = require('path');

async function updateHeroImagePaths() {
  const contentDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  
  console.log('=== Updating heroImage frontmatter to absolute paths ===\n');
  
  let updatedCount = 0;
  let errorCount = 0;
  
  // Find all markdown files
  const mdFiles = [];
  
  function walkDir(dir) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });
    for (const entry of entries) {
      const fullPath = path.join(dir, entry.name);
      if (entry.isDirectory()) {
        walkDir(fullPath);
      } else if (entry.name.endsWith('.md')) {
        mdFiles.push(fullPath);
      }
    }
  }
  
  walkDir(contentDir);
  
  console.log(`Found ${mdFiles.length} markdown files\n`);
  
  for (const mdFile of mdFiles) {
    try {
      const content = fs.readFileSync(mdFile, 'utf8');
      const articlePath = path.relative(contentDir, path.dirname(mdFile));
      
      // Check if heroImage exists and is a relative path
      if (content.includes('heroImage: "./hero.jpg"')) {
        const newPath = `/articulos/${articlePath}/hero.jpg`;
        const updatedContent = content.replace(
          'heroImage: "./hero.jpg"',
          `heroImage: "${newPath}"`
        );
        
        fs.writeFileSync(mdFile, updatedContent);
        updatedCount++;
        
        if (updatedCount <= 10) {
          console.log(`Updated: ${articlePath}`);
        } else if (updatedCount === 11) {
          console.log('... and more');
        }
      }
    } catch (error) {
      console.error(`Error updating ${mdFile}:`, error.message);
      errorCount++;
    }
  }
  
  console.log(`\n=== SUMMARY ===`);
  console.log(`Files updated: ${updatedCount}`);
  console.log(`Errors: ${errorCount}`);
}

updateHeroImagePaths().catch(console.error);