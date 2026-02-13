import { createHmac } from "node:crypto";

// Use import.meta.env for Astro compatibility, fallback to process.env
const IMGPROXY_URL = (import.meta.env?.IMGPROXY_URL || process.env.IMGPROXY_URL || "https://cdn.luciotorres.online").replace(/"/g, '');
const IMGPROXY_KEY = (import.meta.env?.IMGPROXY_KEY || process.env.IMGPROXY_KEY || "tid_SpAHYaSJpIpYfbfWlCI_MCEHUogNADNNUJbiyHrOfMvNvLosvg").replace(/"/g, '');
const IMGPROXY_SALT = (import.meta.env?.IMGPROXY_SALT || process.env.IMGPROXY_SALT || "tsec_nH+0AKPni26HRqM35YopNVDTRSUpJyr4jjHwqeWPHFCBOyNm5k-kqXk9BHq50JS__cwWXj").replace(/"/g, '');
const SITE_URL = (import.meta.env?.SITE_URL || process.env.SITE_URL || "https://luciotorres.online").replace(/"/g, '');

const hexDecode = (hex: string) => Buffer.from(hex, "hex");

const sign = (salt: string, target: string, secret: string) => {
    // Keys in this project are plain strings (S3-style), not hex.
    const keyBuffer = Buffer.from(secret);
    const saltBuffer = Buffer.from(salt);

    const hmac = createHmac("sha256", keyBuffer);
    hmac.update(saltBuffer);
    hmac.update(target);
    return hmac.digest("base64url");
};

export const getImgproxyUrl = (src: any, width?: number, height?: number, format: string = "webp") => {
    if (!src) return "";

    let imageSrc = "";
    if (typeof src === "string") {
        imageSrc = src;
    } else if (typeof src === "object" && src !== null) {
        // Handle Astro image objects
        imageSrc = (src as any).src || "";
    }

    if (!imageSrc) return "";

    // Clean URL
    const cleanFullSrc = imageSrc.split("?")[0].split("#")[0];
    let urlToSign = cleanFullSrc;

    // If it's already our CDN, don't re-process it
    if (urlToSign.startsWith(IMGPROXY_URL)) {
        return urlToSign;
    }

    // Extract source from legacy t3 imgproxy URLs if they exist
    if (urlToSign.includes("t3.storageapi.dev") && urlToSign.includes("/rs:")) {
        try {
            const parts = urlToSign.split("/");
            const filename = parts[parts.length - 1];
            const base64Part = filename.split(".")[0];
            const decoded = Buffer.from(base64Part, "base64url").toString("utf-8");
            if (decoded.startsWith("s3://") || decoded.startsWith("http")) {
                urlToSign = decoded;
            }
        } catch (e) {
            // Silently fail and use original
        }
    }

    // Ensure it's a full URL for imgproxy
    if (urlToSign.startsWith("/")) {
        urlToSign = `${SITE_URL}${urlToSign}`;
    }

    // Sign the URL
    const encodedUrl = Buffer.from(urlToSign).toString("base64url");
    const processingOptions = width || height 
        ? `rs:fill:${width || 0}:${height || 0}:1`
        : `rs:fit:1200:0`;
        
    const pathForSign = `/${processingOptions}/${encodedUrl}.${format}`;
    const signature = sign(IMGPROXY_SALT, pathForSign, IMGPROXY_KEY);
    
    return `${IMGPROXY_URL}/${signature}${pathForSign}`;
};

export default {
    getURL(options: any) {
        return getImgproxyUrl(options.src, options.width, options.height, options.format);
    },
    getHTMLAttributes(options: any) {
        const { src, width, height, format, ...attributes } = options;
        return {
            src: getImgproxyUrl(src, width, height, format),
            ...attributes,
        };
    },
    getSrcSet(options: any) {
        return [];
    },
    validateOptions(options: any) {
        return true;
    }
};
