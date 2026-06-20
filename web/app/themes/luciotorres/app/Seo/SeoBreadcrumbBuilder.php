<?php

namespace App\Seo;

use App\Seo\Contracts\JsonLdInterface;
use App\Seo\Contracts\SeoMetaInterface;

class SeoBreadcrumbBuilder
{
    private JsonLdInterface $jsonLd;

    public function __construct(JsonLdInterface $jsonLd)
    {
        $this->jsonLd = $jsonLd;
    }

    public function build(SeoMetaInterface $seoMeta): array
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

        return $this->jsonLd->breadcrumbList($items, false);
    }
}
