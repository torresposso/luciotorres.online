const { readFileSync, writeFileSync, readdirSync, existsSync } = require('fs');
const { join } = require('path');

const S3_BUCKET = "bundled-saddlebag-tnqki7w";

// Patterns to match
const CDN_PATTERN = /https:\/\/cdn\.luciotorres\.online\/[^\/]+\/rs:[^\/]+\/([a-zA-Z0-9_-]+)\.(?:webp|jpg|jpeg|png|gif)/g;
const T3_PATTERN = /https:\/\/t3\.storageapi\.dev\/[^\/]+\/rs:[^\/]+\/([a-zA-Z0-9_-]+)\.(?:webp|jpg|jpeg|png|gif)/g;
const OLD_DOMAIN_PATTERN = /https:\/\/bundled-saddlebag-tnqki7w\.t3\.storageapi\.dev\/([^\s\)"']+)/g;

function updateFile(filePath) {
  if (!filePath.endsWith('.md') && !filePath.endsWith('.mdx')) return;
  if (!existsSync(filePath)) return;
  
  let content = readFileSync(filePath, 'utf8');
  let originalContent = content;

  // 1. Revert signed CDN URLs to s3://
  content = content.replace(CDN_PATTERN, (match, base64Part) => {
    try {
      const decoded = Buffer.from(base64Part, 'base64url').toString('utf-8');
      if (decoded.startsWith('s3://') || decoded.startsWith('http')) return decoded;
    } catch (e) {}
    return match;
  });

  // 2. Revert signed T3 URLs to s3://
  content = content.replace(T3_PATTERN, (match, base64Part) => {
    try {
      const decoded = Buffer.from(base64Part, 'base64url').toString('utf-8');
      if (decoded.startsWith('s3://') || decoded.startsWith('http')) return decoded;
    } catch (e) {}
    return match;
  });

  // 3. Convert direct public S3 URLs to s3://
  content = content.replace(OLD_DOMAIN_PATTERN, (match, path) => {
    if (path.includes('/rs:')) return match; 
    return `s3://${S3_BUCKET}/${path}`;
  });
  
  if (content !== originalContent) {
    writeFileSync(filePath, content);
    console.log("Normalized: " + filePath);
  }
}

function walkDir(dir) {
  if (!existsSync(dir)) return;
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
console.log("Normalizing all images to s3:// format...");
walkDir(contentDir);
console.log("Done!");
