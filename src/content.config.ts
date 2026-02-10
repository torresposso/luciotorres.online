import { defineCollection, z } from "astro:content";
import { glob } from "astro/loaders";

const articulos = defineCollection({
  loader: glob({ base: "./src/content/articulos", pattern: "**/*.{md,mdx}" }),
  schema: ({ image }) =>
    z.object({
      title: z.string(),
      description: z.string().optional(),
      pubDate: z.coerce.date(),
      updatedDate: z.coerce.date().optional(),
      heroImage: image().optional(),
      categories: z.array(z.string()).optional(),
      slug: z.string().optional(),
    }),
});

export const collections = { articulos };
