import { defineCollection, z } from "astro:content";
import { glob } from "astro/loaders";

const articulos = defineCollection({
  loader: glob({ 
    base: "./src/content/articulos", 
    pattern: "**/*.{md,mdx}",
    // Generate ID from the directory structure to avoid collisions with "index"
    generateId: ({ entry }) => entry.replace(/\/index\.(md|mdx)$/, "").replace(/\.(md|mdx)$/, ""),
  }),
  schema: ({ image }) =>
    z.object({
      title: z.string(),
      description: z.string().optional(),
      pubDate: z.coerce.date(),
      updatedDate: z.coerce.date().optional(),
      heroImage: z.string().url().optional(),
      heroImageAlt: z.string().optional(),
      author: z.string().default('Lucio Torres'),
      categories: z.array(z.string()).optional(),
      tags: z.array(z.string()).optional(),
      slug: z.string().optional(),
    }),
});

export const collections = { articulos };
