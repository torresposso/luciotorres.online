#!/usr/bin/env node
/**
 * Update image URLs in markdown files to use Railway Bucket
 * Usage: node scripts/update-image-urls.js
 */

import { readFileSync, writeFileSync, readdirSync } from "fs";
import { join } from "path";
import { fileURLToPath } from "url";

const BUCKET_URL = process.env.PUBLIC_IMAGE_URL || "https://bundled-saddlebag-tnqki7w.t3.storageapi.dev";
const CONTENT_DIR = join(process.cwd(), "src/content/articulos");

function* walkDir(dir) {
  const files = readdirSync(dir, { withFileTypes: true });
  for (const file of files) {
    const path = join(dir, file.name);
    if (file.isDirectory()) {
      yield* walkDir(path);
    } else if (file.name === "index.md") {
      yield path;
    }
  }
}

function updateMarkdownFile(filePath) {
  let content = readFileSync(filePath, "utf-8");
  let modified = false;

  // Get relative path from content dir
  const relativeDir = filePath.replace(CONTENT_DIR, "").replace("/index.md", "");

  // Replace heroImage paths
  // Pattern: heroImage: ./image.jpg or heroImage: image.jpg
  content = content.replace(
    /heroImage:\s*\.?\/?([^\s\n]+)/g,
    (match, imagePath) => {
      modified = true;
      const newUrl = `${BUCKET_URL}${relativeDir}/${imagePath}`;
      return `heroImage: ${newUrl}`;
    }
  );

  // Replace inline image references
  // Pattern: ![alt](./image.jpg) or ![alt](image.jpg)
  content = content.replace(
    /!\[([^\]]*)\]\(\.?\/?([^)]+)\)/g,
    (match, alt, imagePath) => {
      if (/^(http|https|data):/.test(imagePath)) return match; // Skip external URLs
      modified = true;
      const newUrl = `${BUCKET_URL}${relativeDir}/${imagePath}`;
      return `![${alt}](${newUrl})`;
    }
  );

  if (modified) {
    writeFileSync(filePath, content);
    console.log(`✅ Updated: ${filePath.replace(CONTENT_DIR, "")}`);
    return true;
  }
  return false;
}

async function main() {
  console.log("📝 Updating image URLs in markdown files...\n");
  console.log(`🌐 Bucket URL: ${BUCKET_URL}\n`);

  let updated = 0;
  let skipped = 0;

  for (const file of walkDir(CONTENT_DIR)) {
    if (updateMarkdownFile(file)) {
      updated++;
    } else {
      skipped++;
    }
  }

  console.log(`\n✨ Done! Updated: ${updated}, Skipped: ${skipped}`);
}

main().catch(console.error);
