<?php

namespace App\View\Composers;

use App\Seo\SeoBreadcrumbBuilder;
use App\Seo\SeoDataProvider;
use App\Seo\SeoGraphBuilder;
use Roots\Acorn\View\Composer;

class Seo extends Composer
{
    protected static $views = [
        'layouts.app',
    ];

    public function with(): array
    {
        $seoMeta = app(SeoDataProvider::class)->forCurrentRequest();

        return [
            'seoMeta' => $seoMeta,
            'jsonLdGraph' => $this->buildJsonLdGraph($seoMeta),
        ];
    }

    private function buildJsonLdGraph($seoMeta): array
    {
        $graph = app(SeoGraphBuilder::class)->build($seoMeta);
        $graph[] = app(SeoBreadcrumbBuilder::class)->build($seoMeta);

        return $graph;
    }
}
