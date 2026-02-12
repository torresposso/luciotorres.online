import mdx from "@astrojs/mdx";
import sitemap from "@astrojs/sitemap";
import { defineConfig } from "astro/config";

export default defineConfig({
  site: "https://luciotorres.online",
  integrations: [mdx(), sitemap()],
  image: {
    service: undefined,
  },
  cacheDir: './cache',
});
