#!/usr/bin/env node
/**
 * Continue uploading images to Railway Bucket
 */

import { S3Client, PutObjectCommand, ListObjectsV2Command } from "@aws-sdk/client-s3";
import { readFileSync, readdirSync, statSync, existsSync } from "fs";
import { join, relative, dirname } from "path";
import { fileURLToPath } from "url";

const BUCKET_CONFIG = {
  endpoint: "https://t3.storageapi.dev",
  bucket: "bundled-saddlebag-tnqki7w",
  region: "auto",
  credentials: {
    accessKeyId: "tid_SpAHYaSJpIpYfbfWlCI_MCEHUogNADNNUJbiyHrOfMvNvLosvg",
    secretAccessKey: "tsec_nH+0AKPni26HRqM35YopNVDTRSUpJyr4jjHwqeWPHFCBOyNm5k-kqXk9BHq50JS__cwWXj",
  },
};

const CONTENT_DIR = join(process.cwd(), "src/content/articulos");

const s3Client = new S3Client({
  endpoint: BUCKET_CONFIG.endpoint,
  region: BUCKET_CONFIG.region,
  credentials: BUCKET_CONFIG.credentials,
  forcePathStyle: false,
});

async function getUploadedFiles() {
  const uploaded = new Set();
  let continuationToken = undefined;
  
  do {
    const command = new ListObjectsV2Command({
      Bucket: BUCKET_CONFIG.bucket,
      ContinuationToken: continuationToken,
      MaxKeys: 1000,
    });
    
    const response = await s3Client.send(command);
    response.Contents?.forEach(obj => uploaded.add(obj.Key));
    continuationToken = response.NextContinuationToken;
  } while (continuationToken);
  
  return uploaded;
}

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

function getContentType(filePath) {
  if (filePath.endsWith(".jpg") || filePath.endsWith(".jpeg")) return "image/jpeg";
  if (filePath.endsWith(".png")) return "image/png";
  if (filePath.endsWith(".webp")) return "image/webp";
  if (filePath.endsWith(".gif")) return "image/gif";
  return "application/octet-stream";
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
    return key;
  } catch (error) {
    console.error(`❌ Failed: ${key}`, error.message);
    return null;
  }
}

async function main() {
  console.log("🚀 Continuing image upload...\n");
  
  console.log("📂 Getting list of already uploaded files...");
  const uploaded = await getUploadedFiles();
  console.log(`✅ Found ${uploaded.size} files already uploaded\n`);
  
  const allImages = [];
  for await (const file of walkDir(CONTENT_DIR)) {
    const key = relative(CONTENT_DIR, file).replace(/\\/g, "/");
    if (!uploaded.has(key)) {
      allImages.push({ file, key });
    }
  }
  
  console.log(`📸 Found ${allImages.length} images to upload\n`);
  
  if (allImages.length === 0) {
    console.log("✨ All images already uploaded!");
    return;
  }
  
  let success = 0;
  let failed = 0;
  const batchSize = 50;
  
  for (let i = 0; i < allImages.length; i += batchSize) {
    const batch = allImages.slice(i, i + batchSize);
    process.stdout.write(`\r[${Math.min(i + batchSize, allImages.length)}/${allImages.length}] Uploading batch... `);
    
    const results = await Promise.all(
      batch.map(({ file, key }) => uploadImage(file).then(result => ({ key, result })))
    );
    
    results.forEach(({ result }) => {
      if (result) success++;
      else failed++;
    });
  }
  
  console.log(`\n\n✨ Done! Uploaded: ${success}, Failed: ${failed}`);
  console.log(`📊 Total in bucket: ${uploaded.size + success}`);
}

main().catch(console.error);
