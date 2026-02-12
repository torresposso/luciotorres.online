const fs = require('fs');
const path = require('path');

async function simpleVerification() {
  const articlesDir = path.join(__dirname, '..', 'src', 'content', 'articulos');
  
  // Test a few articles manually
  const testArticles = [
    'src/content/articulos/2018/07/alumbrado-publico-de-cartagena-una-bomba-secreta',
    'src/content/articulos/2018/09/adios-yolanda-nos-dejaste-una-cloaca-edurbe-ii',
    'src/content/articulos/2020/08/los-heroes-contra-la-legion-del-mal-que-aseguraron-a-uribe',
    'src/content/articulos/2020/04/quien-es-el-ladron-sobrecostos-de-dau-gel-antiseptico-300-mascarilla-a-19-5-mil-200'
  ];
  
  console.log('=== SIMPLE VERIFICATION ===\n');
  
  for (const testArticle of testArticles) {
    const articleDir = path.join(__dirname, '..', testArticle);
    const indexFile = path.join(articleDir, 'index.md');
    
    console.log(`Checking: ${testArticle}`);
    
    if (!fs.existsSync(indexFile)) {
      console.log('  ❌ Missing index.md');
      continue;
    }
    
    const content = fs.readFileSync(indexFile, 'utf8');
    
    // Check heroImage in frontmatter
    const hasHeroImage = content.includes('heroImage:');
    console.log(`  ✅ heroImage in frontmatter: ${hasHeroImage}`);
    
    // Check hero.jpg file exists
    const heroFile = path.join(articleDir, 'hero.jpg');
    const hasHeroFile = fs.existsSync(heroFile);
    console.log(`  ✅ hero.jpg file exists: ${hasHeroFile}`);
    
    // Look for first image reference (simple search)
    const firstImageMatch = content.match(/!\[.*?\]\(\.\/([^)]+\.(jpg|png|webp))\)/);
    if (firstImageMatch) {
      const firstImage = firstImageMatch[1];
      console.log(`  First image in article: ${firstImage}`);
      console.log(`  hero.jpg is first image: ${firstImage === 'hero.jpg' ? '✅' : '❌'}`);
    } else {
      console.log(`  No regular image references found`);
      
      // Check for link-wrapped images
      const linkWrappedMatch = content.match(/\[!\[.*?\]\(\.\/([^)]+\.(jpg|png|webp))\)/);
      if (linkWrappedMatch) {
        const wrappedImage = linkWrappedMatch[1];
        console.log(`  Link-wrapped image: ${wrappedImage}`);
        console.log(`  hero.jpg is first image: ${wrappedImage === 'hero.jpg' ? '✅' : '❌'}`);
      }
    }
    
    console.log('');
  }
  
  // Quick count of articles where hero.jpg appears in body
  console.log('=== QUICK COUNT ===');
  
  let countWithHeroInBody = 0;
  let totalChecked = 0;
  
  // Check 50 random articles
  const allArticles = [];
  
  function walkDir(dir) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });
    for (const entry of entries) {
      const fullPath = path.join(dir, entry.name);
      if (entry.isDirectory()) {
        const hasIndex = fs.existsSync(path.join(fullPath, 'index.md'));
        if (hasIndex) {
          allArticles.push(fullPath);
        } else {
          walkDir(fullPath);
        }
      }
    }
  }
  
  walkDir(articlesDir);
  
  // Sample 50 articles
  const sampleSize = Math.min(50, allArticles.length);
  const sample = allArticles.sort(() => 0.5 - Math.random()).slice(0, sampleSize);
  
  for (const articleDir of sample) {
    totalChecked++;
    const indexFile = path.join(articleDir, 'index.md');
    
    if (fs.existsSync(indexFile)) {
      const content = fs.readFileSync(indexFile, 'utf8');
      
      // Check if hero.jpg appears in image references
      if (content.match(/!\[.*?\]\(\.\/hero\.jpg\)/)) {
        countWithHeroInBody++;
      }
    }
  }
  
  console.log(`Sampled ${sampleSize} articles`);
  console.log(`Articles where hero.jpg appears in body: ${countWithHeroInBody} (${Math.round(countWithHeroInBody/sampleSize*100)}%)`);
}

simpleVerification().catch(console.error);