import { defineConfig } from 'astro/config';
import alpinejs from '@astrojs/alpinejs';
import tailwindcss from '@tailwindcss/vite';
import mdx from "@astrojs/mdx";
import sitemap from "@astrojs/sitemap";

export default defineConfig({
    site: "https://luciotorres.online",
    image: {
        remotePatterns: [
            {
                protocol: "https",
                hostname: "cdn.luciotorres.online",
            },
            {
                protocol: "https",
                hostname: "bundled-saddlebag-tnqki7w.t3.storageapi.dev",
            }
        ]
    },
    integrations: [
        alpinejs({
            entrypoint: "/src/scripts/alpine.ts",
        }),
        mdx(),
        sitemap()
    ],
    vite: {
        plugins: [
            tailwindcss()
        ]
    }
});
