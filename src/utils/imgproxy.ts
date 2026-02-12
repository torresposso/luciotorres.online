import { createHmac } from "node:crypto";

const IMGPROXY_URL = process.env.IMGPROXY_URL || "https://cdn.luciotorres.online";
const IMGPROXY_KEY = process.env.IMGPROXY_KEY || "";
const IMGPROXY_SALT = process.env.IMGPROXY_SALT || "";
const SITE_URL = process.env.SITE_URL || "https://luciotorres.online";

const hexDecode = (hex: string) => Buffer.from(hex, "hex");

const sign = (salt: string, target: string, secret: string) => {
    const hmac = createHmac("sha256", hexDecode(secret) as any);
    hmac.update(hexDecode(salt) as any);
    hmac.update(target);
    return hmac.digest("base64url");
};

export const getImgproxyUrl = (src: any, width?: number, height?: number, format: string = "webp") => {
    if (!src) return "";

    let imageSrc = "";
    if (typeof src === "string") {
        imageSrc = src;
    } else if (typeof src === "object" && src !== null) {
        imageSrc = (src as any).src || "";
    }

    if (!imageSrc) return "";

    if (imageSrc.startsWith("/")) {
        imageSrc = `${SITE_URL}${imageSrc}`;
    }

    const cleanFullSrc = imageSrc.split("?")[0].split("#")[0];

    if (!IMGPROXY_URL || !IMGPROXY_KEY || !IMGPROXY_SALT) {
        console.log(`[Imgproxy Fallback] ${cleanFullSrc}`);
        return cleanFullSrc;
    }

    const encodedUrl = Buffer.from(cleanFullSrc).toString("base64url");
    const processingOptions = `rs:fill:${width || 0}:${height || 0}:1`;
    const pathForSign = `/${processingOptions}/${encodedUrl}.${format}`;

    const signature = sign(IMGPROXY_SALT, pathForSign, IMGPROXY_KEY);
    const finalUrl = `${IMGPROXY_URL}/${signature}${pathForSign}`;

    console.log(`[Imgproxy Active] ${cleanFullSrc} -> ${finalUrl}`);

    return finalUrl;
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
