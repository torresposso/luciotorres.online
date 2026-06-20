<?php

namespace App\Seo;

use App\Seo\Contracts\MetaRendererInterface;
use App\Seo\Contracts\SeoMetaInterface;

class MetaRenderer implements MetaRendererInterface
{
    private SeoMetaInterface $seo;

    public function __construct(SeoMetaInterface $seo)
    {
        $this->seo = $seo;
    }

    public function render(): string
    {
        $tags = [];

        if ($this->seo->getOgTitle() !== null) {
            $tags[] = $this->metaTag('property="og:title"', $this->seo->getOgTitle());
        }

        if ($this->seo->getOgDescription() !== null) {
            $tags[] = $this->metaTag('property="og:description"', $this->seo->getOgDescription());
        }

        if ($this->seo->getOgImageUrl() !== null) {
            $tags[] = $this->metaTag('property="og:image"', $this->seo->getOgImageUrl());
        }

        if ($this->seo->getPostUrl() !== null) {
            $tags[] = $this->metaTag('property="og:url"', $this->seo->getPostUrl());
        }

        $tags[] = $this->metaTag('property="og:type"', $this->seo->isHome() ? 'website' : 'article');
        $tags[] = $this->metaTag('property="og:site_name"', get_bloginfo('name'));
        $tags[] = $this->metaTag('property="og:locale"', get_locale());

        $tags[] = $this->metaTag('name="twitter:card"', 'summary_large_image');

        if ($this->seo->getOgTitle() !== null) {
            $tags[] = $this->metaTag('name="twitter:title"', $this->seo->getOgTitle());
        }

        if ($this->seo->getOgDescription() !== null) {
            $tags[] = $this->metaTag('name="twitter:description"', $this->seo->getOgDescription());
        }

        if ($this->seo->getOgImageUrl() !== null) {
            $tags[] = $this->metaTag('name="twitter:image"', $this->seo->getOgImageUrl());
        }

        if ($this->seo->getMetaDescription() !== null) {
            $tags[] = $this->metaTag('name="description"', $this->seo->getMetaDescription());
        }

        if ($this->seo->getCanonical() !== null) {
            $tags[] = sprintf('<link rel="canonical" href="%s">', $this->escape($this->seo->getCanonical()));
        }

        if ($this->seo->getNoindex()) {
            $tags[] = $this->metaTag('name="robots"', 'noindex');
        }

        return implode("\n", $tags);
    }

    private function metaTag(string $attribute, string $content): string
    {
        return sprintf('<meta %s content="%s">', $attribute, $this->escape($content));
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
    }
}
