<?php

namespace App\View\Composers;

use App\Seo\JsonLd;
use App\Seo\MetaRenderer;
use App\Seo\SeoMeta;
use Illuminate\Support\Facades\Vite;
use Roots\Acorn\View\Composer;

class Seo extends Composer
{
    /**
     * Default OG image path (relative to theme resources).
     */
    private const DEFAULT_OG_IMAGE = 'resources/images/og-home.jpg';

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'layouts.app',
    ];

    public function with(): array
    {
        $seoMeta = $this->resolveSeoMeta();

        return [
            'seoMeta' => $seoMeta,
            'jsonLdGraph' => $this->buildJsonLdGraph($seoMeta),
        ];
    }

    /**
     * Resolve SeoMeta for the current request.
     */
    private function resolveSeoMeta(): SeoMeta
    {
        $data = [];

        if (is_singular()) {
            $postId = get_queried_object_id();
            $data = $this->getSeoDataForPost($postId);
        } elseif (is_front_page() || is_home()) {
            $desc = get_bloginfo('description');
            if (empty($desc) || $desc === 'Just another WordPress site') {
                $desc = 'Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico y mirada progresista.';
            }
            $data = [
                'post_title' => get_bloginfo('name'),
                'post_url' => home_url('/'),
                'meta_desc' => $desc,
                'og_title' => get_bloginfo('name') . ' — ' . $desc,
                'og_desc' => $desc,
                'og_image_url' => Vite::asset(self::DEFAULT_OG_IMAGE),
                'is_home' => true,
                'canonical' => home_url('/'),
            ];
        }

        return new SeoMeta($data, true);
    }

    /**
     * Get SEO data for a specific post.
     *
     * @return array<string, mixed>
     */
    private function getSeoDataForPost(int $postId): array
    {
        $ogImageId = get_post_meta($postId, '_luciotorres_og_image', true);

        if (empty($ogImageId)) {
            $ogImageId = get_post_thumbnail_id($postId);
            $ogImageUrl = $ogImageId ? wp_get_attachment_url($ogImageId) : null;
        } else {
            $ogImageUrl = wp_get_attachment_url((int) $ogImageId);
        }

        return [
            'meta_desc' => get_post_meta($postId, '_luciotorres_meta_desc', true) ?: null,
            'og_title' => get_post_meta($postId, '_luciotorres_og_title', true) ?: null,
            'og_desc' => get_post_meta($postId, '_luciotorres_og_desc', true) ?: null,
            'og_image_id' => $ogImageId ?: null,
            'og_image_url' => $ogImageUrl ?: null,
            'noindex' => get_post_meta($postId, '_luciotorres_noindex', true) === '1',
            'canonical' => get_post_meta($postId, '_luciotorres_canonical', true) ?: null,
            'post_title' => get_the_title($postId),
            'post_url' => get_permalink($postId),
            'post_type' => get_post_type($postId),
            'date_published' => get_the_date('c', $postId),
            'date_modified' => get_the_modified_date('c', $postId),
            'author_name' => get_the_author_meta('display_name', get_post_field('post_author', $postId)),
        ];
    }

    private function buildJsonLdGraph(SeoMeta $seoMeta): array
    {
        $jsonld = app(JsonLd::class);
        $graph = [];

        $siteUrl = home_url();
        $siteName = get_bloginfo('name');

        $orgConfig = apply_filters('luciotorres/seo/organization', [
            'name' => $siteName,
            'logo' => get_theme_mod('custom_logo')
                ? wp_get_attachment_url(get_theme_mod('custom_logo'))
                : null,
            'url' => $siteUrl,
            'sameAs' => [],
        ]);

        $graph[] = $jsonld->organization($orgConfig, false);

        $siteConfig = apply_filters('luciotorres/seo/social', [
            'name' => $siteName,
            'url' => $siteUrl,
            'search_url' => $siteUrl . '/?s={search_term_string}',
        ]);

        $graph[] = $jsonld->website($siteConfig, false);

        if (is_singular('post') && ! $seoMeta->isHome()) {
            $articleData = [
                'headline' => $seoMeta->getPostTitle() ?? '',
                'description' => $seoMeta->getMetaDescription() ?? $seoMeta->getOgDescription() ?? '',
                'datePublished' => $seoMeta->getDatePublished() ?? '',
                'dateModified' => $seoMeta->getDateModified() ?? '',
                'author' => $seoMeta->getAuthorName() ?? '',
                'image' => $seoMeta->getOgImageUrl() ?? '',
                'url' => $seoMeta->getPostUrl() ?? '',
            ];

            $graph[] = $jsonld->article($articleData, false);
        }

        $breadcrumbs = $this->buildBreadcrumbs($seoMeta);
        $graph[] = $jsonld->breadcrumbList($breadcrumbs, false);

        return $graph;
    }

    /**
     * Build breadcrumb items.
     *
     * @return array<int, array<string, string>>
     */
    private function buildBreadcrumbs(SeoMeta $seoMeta): array
    {
        $items = [
            ['name' => 'Inicio', 'url' => home_url()],
        ];

        if (! $seoMeta->isHome() && is_singular()) {
            $categories = get_the_category(get_queried_object_id());

            if (! empty($categories)) {
                $category = $categories[0];
                $items[] = [
                    'name' => $category->name,
                    'url' => get_category_link($category->term_id),
                ];
            }

            $items[] = [
                'name' => $seoMeta->getPostTitle() ?? 'Artículo',
                'url' => (string) ($seoMeta->getPostUrl() ?? home_url()),
            ];
        }

        return $items;
    }
}
