import rss from '@astrojs/rss';
import { getCollection } from 'astro:content';
import { SITE_TITLE, SITE_DESCRIPTION } from '../consts';

export async function GET(context) {
	const site = context.site || 'https://luciotorres.online';
	const articulos = await getCollection('articulos');
	const sortedArticulos = articulos.sort(
		(a, b) => b.data.pubDate.valueOf() - a.data.pubDate.valueOf()
	);

	return rss({
		title: SITE_TITLE,
		description: SITE_DESCRIPTION,
		site: site,
		items: sortedArticulos.map((articulo) => ({
			title: articulo.data.title,
			pubDate: articulo.data.pubDate,
			description: articulo.data.description || 'Análisis político y periodístico',
			link: `/articulos/${articulo.data.slug || articulo.id}/`,
		})),
		customData: `<language>es-co</language>`,
	});
}
