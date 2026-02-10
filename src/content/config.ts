import { defineCollection, z } from 'astro:content';

const articulosCollection = defineCollection({
  type: 'content',
  schema: () => z.object({
    title: z.string(),
    date: z.string(),
    author: z.string().default('Lucio Torres'),
    categories: z.array(z.string()).default([]),
    tags: z.array(z.string()).default([]),
    featuredImage: z.string(),
  }),
});

export const collections = {
  articulos: articulosCollection,
};
