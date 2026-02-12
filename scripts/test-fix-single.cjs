const fs = require('fs');
const path = require('path');

async function testSingleArticle() {
  // Test on a single article
  const testArticle = 'src/content/articulos/2018/07/alumbrado-publico-de-cartagena-una-bomba-secreta';
  const articleDir = path.join(__dirname, '..', testArticle);
  
  console.log(`Testing on: ${articleDir}\n`);
  
  // Backup original files
  const indexFile = path.join(articleDir, 'index.md');
  const heroFile = path.join(articleDir, 'hero.jpg');
  
  const indexBackup = path.join(articleDir, 'index.md.backup');
  const heroBackup = path.join(articleDir, 'hero.jpg.backup');
  
  fs.copyFileSync(indexFile, indexBackup);
  fs.copyFileSync(heroFile, heroBackup);
  
  console.log('Backups created:');
  console.log(`  ${indexBackup}`);
  console.log(`  ${heroBackup}\n`);
  
  // Read original content
  let content = fs.readFileSync(indexFile, 'utf8');
  console.log('Original hero.jpg size:', fs.statSync(heroFile).size, 'bytes');
  
  // Extract first image reference
  const imageRegex = /!\[.*?\]\((\.\/)?([^)]+\.(jpg|png|webp))\)/gi;
  const matches = [...content.matchAll(imageRegex)];
  
  if (matches.length > 0) {
    const firstImageName = matches[0][2];
    const firstImageFile = path.join(articleDir, firstImageName);
    
    console.log(`First image in article: ${firstImageName}`);
    console.log('First image size:', fs.statSync(firstImageFile).size, 'bytes');
    
    // Copy first image to hero.jpg
    fs.copyFileSync(firstImageFile, heroFile);
    console.log(`\nCopied ${firstImageName} to hero.jpg`);
    console.log('New hero.jpg size:', fs.statSync(heroFile).size, 'bytes');
    
    // Update references in markdown
    const oldImageRef = `./${firstImageName}`;
    let updatedContent = content;
    
    // Replace all occurrences
    const refRegex = new RegExp(`!\\[.*?\\]\\((\\./)?${firstImageName.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\)`, 'g');
    updatedContent = updatedContent.replace(refRegex, (match) => {
      return match.replace(firstImageName, 'hero.jpg');
    });
    
    // Write updated content
    fs.writeFileSync(indexFile, updatedContent);
    console.log(`\nUpdated references from ${firstImageName} to hero.jpg`);
    
    // Show before/after
    console.log('\n=== BEFORE (first few lines with images) ===');
    const beforeLines = content.split('\n').filter(line => line.includes('![')).slice(0, 3);
    beforeLines.forEach(line => console.log(line));
    
    console.log('\n=== AFTER (first few lines with images) ===');
    const afterLines = updatedContent.split('\n').filter(line => line.includes('![')).slice(0, 3);
    afterLines.forEach(line => console.log(line));
    
    // Restore backups
    console.log('\n=== RESTORING BACKUPS ===');
    fs.copyFileSync(indexBackup, indexFile);
    fs.copyFileSync(heroBackup, heroFile);
    fs.unlinkSync(indexBackup);
    fs.unlinkSync(heroBackup);
    
    console.log('Original files restored, backups removed.');
    
  } else {
    console.log('No image references found in article');
  }
}

testSingleArticle().catch(console.error);