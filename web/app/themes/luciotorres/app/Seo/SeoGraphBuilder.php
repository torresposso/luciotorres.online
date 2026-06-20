<?php

namespace App\Seo;

use App\Seo\Contracts\JsonLdInterface;
use App\Seo\Contracts\SeoMetaInterface;

class SeoGraphBuilder
{
    private JsonLdInterface $jsonLd;

    public function __construct(JsonLdInterface $jsonLd)
    {
        $this->jsonLd = $jsonLd;
    }

    public function build(SeoMetaInterface $seoMeta): array
    {
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

        $graph[] = $this->jsonLd->organization($orgConfig, false);

        $siteConfig = apply_filters('luciotorres/seo/social', [
            'name' => $siteName,
            'url' => $siteUrl,
            'search_url' => $siteUrl . '/?s={search_term_string}',
        ]);

        $graph[] = $this->jsonLd->website($siteConfig, false);

        if (is_singular('post') && ! $seoMeta->isHome()) {
            $graph[] = $this->jsonLd->article([
                'headline' => $seoMeta->getPostTitle() ?? '',
                'description' => $seoMeta->getMetaDescription() ?? $seoMeta->getOgDescription() ?? '',
                'datePublished' => $seoMeta->getDatePublished() ?? '',
                'dateModified' => $seoMeta->getDateModified() ?? '',
                'author' => $seoMeta->getAuthorName() ?? '',
                'image' => $seoMeta->getOgImageUrl() ?? '',
                'url' => $seoMeta->getPostUrl() ?? '',
            ], false);
        }

        return $graph;
    }
}
