#!/usr/bin/env node
/**
 * Upload images to Railway Storage Bucket (S3-compatible)
 * Usage: node scripts/upload-images-to-bucket.js
 */

import { S3Client, PutObjectCommand, ListObjectsV2Command } from "@aws-sdk/client-s3";
import { readFileSync, readdirSync, statSync } from "fs";
import { join, relative } from "path";
import { fileURLToPath } from "url";

const __dirname = fileURLToPath(new URL(".", import.meta.url));

// Configuration - Update these with your actual credentials
const BUCKET_CONFIG = {
  endpoint: process.env.BUCKET_ENDPOINT || "https://storage.railway.app",
  bucket: process.env.BUCKET_NAME || "your-bucket-name",
  region: process.env.BUCKET_REGION || "auto",
  credentials: {
    accessKeyId: process.env.BUCKET_ACCESS_KEY_ID || "",
    secretAccessKey: process.env.BUCKET_SECRET_ACCESS_KEY || "",
  },
};

const CONTENT_DIR = join(process.cwd(), "src/content/articulos");

const s3Client = new S3Client({
  endpoint: BUCKET_CONFIG.endpoint,
  region: BUCKET_CONFIG.region,
  credentials: BUCKET_CONFIG.credentials,
  forcePathStyle: false, // Railway uses virtual-hosted style
});

async function* walkDir(dir) {
  const files = readdirSync(dir);
  for (const file of files) {
    const path = join(dir, file);
    const stat = statSync(path);
    if (stat.isDirectory()) {
      yield* walkDir(path);
    } else if (/\.(jpg|jpeg|png|webp|gif)$/i.test(file)) {
      yield path;
    }
  }
}

async function uploadImage(filePath) {
  const key = relative(CONTENT_DIR, filePath).replace(/\\/g, "/");
  const fileContent = readFileSync(filePath);
  
  const command = new PutObjectCommand({
    Bucket: BUCKET_CONFIG.bucket,
    Key: key,
    Body: fileContent,
    ContentType: getContentType(filePath),
  });

  try {
    await s3Client.send(command);
    console.log(`✅ Uploaded: ${key}`);
    return key;
  } catch (error) {
    console.error(`❌ Failed: ${key}`, error.message);
    return null;
  }
}

function getContentType(filePath) {
  if (filePath.endsWith(".jpg") || filePath.endsWith(".jpeg")) return "image/jpeg";
  if (filePath.endsWith(".png")) return "image/png";
  if (filePath.endsWith(".webp")) return "image/webp";
  if (filePath.endsWith(".gif")) return "image/gif";
  return "application/octet-stream";
}

async function main() {
  console.log("🚀 Starting image upload to Railway Bucket...\n");
  console.log(`📁 Content directory: ${CONTENT_DIR}`);
  console.log(`🪣 Bucket: ${BUCKET_CONFIG.bucket}\n`);

  if (!BUCKET_CONFIG.credentials.accessKeyId) {
    console.error("❌ Error: BUCKET_ACCESS_KEY_ID not set!");
    console.log("\nSet environment variables:");
    console.log("  export BUCKET_ACCESS_KEY_ID=your_key");
    console.log("  export BUCKET_SECRET_ACCESS_KEY=your_secret");
    console.log("  export BUCKET_NAME=your_bucket_name");
    process.exit(1);
  }

  const images = [];
  for await (const file of walkDir(CONTENT_DIR)) {
    images.push(file);
  }

  console.log(`📸 Found ${images.length} images\n`);

  let uploaded = 0;
  let failed = 0;

  for (let i = 0; i < images.length; i++) {
    const file = images[i];
    process.stdout.write(`[${i + 1}/${images.length}] `);
    const result = await uploadImage(file);
    if (result) uploaded++;
    else failed++;
  }

  console.log(`\n✨ Done! Uploaded: ${uploaded}, Failed: ${failed}`);
  console.log(`\n🌐 Public URL base:`);
  console.log(`   ${BUCKET_CONFIG.endpoint}/${BUCKET_CONFIG.bucket}/`);
}

main().catch(console.error);
