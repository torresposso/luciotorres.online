import { getCollection } from 'astro:content';
import rss from '@astrojs/rss';
import { SITE_DESCRIPTION, SITE_TITLE } from '../consts';

function extractSlugFromPath(id) {
	const parts = id.split('/');
	return parts[parts.length - 1];
}

export async function GET(context) {
	const posts = await getCollection('articulos');
	return rss({
		title: SITE_TITLE,
		description: SITE_DESCRIPTION,
		site: context.site,
		items: posts.map((post) => ({
			...post.data,
			link: `/articulos/${extractSlugFromPath(post.id)}/`,
		})),
	});
}
