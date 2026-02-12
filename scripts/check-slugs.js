// scripts/check-slugs.js
// Utility to detect duplicate slugs (or generated ids) in the Astro content collection.
// Run with: node scripts/check-slugs.js

import { readFileSync, readdirSync, statSync } from "fs";
import { join, basename, dirname, relative, extname } from "path";

const CONTENT_ROOT = "src/content/articulos";

function getAllMarkdownFiles(dir) {
  const entries = readdirSync(dir, { withFileTypes: true });
  const files = [];
  for (const entry of entries) {
    const fullPath = join(dir, entry.name);
    if (entry.isDirectory()) {
      files.push(...getAllMarkdownFiles(fullPath));
    } else if (entry.isFile() && /\.mdx?$/i.test(entry.name)) {
      // We only care about index files inside a folder (the convention used in this project)
      if (basename(fullPath, extname(fullPath)) === "index") {
        files.push(fullPath);
      }
    }
  }
  return files;
}

function extractSlug(filePath) {
  const raw = readFileSync(filePath, "utf8");
  const slugMatch = raw.match(/^slug:\s*"([^"]+)"/m);
  if (slugMatch) return slugMatch[1];
  // Fallback id – path relative to src/content without the leading "src/content/" and without the "/index.md" part
  const rel = relative("src/content", filePath);
  const withoutIndex = rel.replace(/\/index\.mdx?$/i, "");
  // Normalise to forward slashes for consistency
  return withoutIndex.replace(/\\/g, "/");
}

function main() {
  const files = getAllMarkdownFiles(CONTENT_ROOT);
  const slugMap = new Map(); // slug/id -> [paths]
  const folderSlugMap = new Map(); // folder name -> slug (for collision detection)

  for (const file of files) {
    const slug = extractSlug(file);
    const existing = slugMap.get(slug) ?? [];
    existing.push(file);
    slugMap.set(slug, existing);

    const folder = basename(dirname(file));
    if (!folderSlugMap.has(folder)) folderSlugMap.set(folder, []);
    folderSlugMap.get(folder).push({ slug, file });
  }

  // 1️⃣ Duplicate slugs (more than one file shares the same slug/id)
  const duplicateSlugs = [...slugMap.entries()].filter(([, paths]) => paths.length > 1);
  if (duplicateSlugs.length) {
    console.log("⚠️ Duplicate slugs/ids found:");
    for (const [slug, paths] of duplicateSlugs) {
      console.log(`  • ${slug}`);
      for (const p of paths) console.log(`    - ${p}`);
    }
  } else {
    console.log("✅ No duplicate slugs/ids.");
  }

  // 2️⃣ Folder name vs slug collisions (folder name equals a slug used elsewhere)
  const collisions = [];
  for (const [folder, entries] of folderSlugMap.entries()) {
    for (const { slug, file } of entries) {
      if (folder !== slug && slugMap.has(folder)) {
        // Some other article uses this folder name as its slug/id
        collisions.push({ folder, slug, file, otherPaths: slugMap.get(folder) });
      }
    }
  }
  if (collisions.length) {
    console.log("⚠️ Folder‑name vs slug collisions detected:");
    for (const c of collisions) {
      console.log(`  • Folder '${c.folder}' (in ${c.file}) collides with slug used by:`);
      for (const p of c.otherPaths) console.log(`    - ${p}`);
    }
  } else {
    console.log("✅ No folder‑name vs slug collisions.");
  }
}

main();
