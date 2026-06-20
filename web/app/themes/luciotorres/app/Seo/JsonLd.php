<?php

namespace App\Seo;

use App\Seo\Contracts\JsonLdInterface;

/**
 * Builder for JSON-LD structured data schemas.
 *
 * Generates Organization, WebSite, Article, and BreadcrumbList
 * schemas as associative arrays ready for JSON serialization.
 */
class JsonLd implements JsonLdInterface
{
    /**
     * Build an Organization schema.
     *
     * @param  array<string, mixed>  $config
     * @param  bool  $includeContext  Whether to include @context (false for @graph items)
     * @return array<string, mixed>
     */
    public function organization(array $config, bool $includeContext = true): array
    {
        $schema = [
            '@type' => 'Organization',
            'name' => $config['name'] ?? '',
            'url' => $config['url'] ?? '',
        ];

        if ($includeContext) {
            $schema['@context'] = 'https://schema.org';
        }

        if (! empty($config['logo'])) {
            $schema['logo'] = $config['logo'];
        }

        if (! empty($config['sameAs'])) {
            $schema['sameAs'] = $config['sameAs'];
        }

        return $schema;
    }

    /**
     * Build a WebSite schema with optional SearchAction.
     *
     * @param  array<string, mixed>  $config
     * @param  bool  $includeContext  Whether to include @context (false for @graph items)
     * @return array<string, mixed>
     */
    public function website(array $config, bool $includeContext = true): array
    {
        $schema = [
            '@type' => 'WebSite',
            'name' => $config['name'] ?? '',
            'url' => $config['url'] ?? '',
        ];

        if ($includeContext) {
            $schema['@context'] = 'https://schema.org';
        }

        if (! empty($config['search_url'])) {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $config['search_url'],
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }

        return $schema;
    }

    /**
     * Build an Article schema.
     *
     * @param  array<string, mixed>  $data
     * @param  bool  $includeContext  Whether to include @context (false for @graph items)
     * @return array<string, mixed>
     */
    public function article(array $data, bool $includeContext = true): array
    {
        $schema = [
            '@type' => 'Article',
            'headline' => $data['headline'] ?? '',
        ];

        if ($includeContext) {
            $schema['@context'] = 'https://schema.org';
        }

        return array_merge($schema, array_filter([
            'description' => $data['description'] ?? null,
            'datePublished' => $data['datePublished'] ?? null,
            'dateModified' => $data['dateModified'] ?? null,
            'image' => $data['image'] ?? null,
            'author' => ! empty($data['author']) ? [
                '@type' => 'Person',
                'name' => $data['author'],
            ] : null,
            'mainEntityOfPage' => ! empty($data['url']) ? [
                '@type' => 'WebPage',
                '@id' => $data['url'],
            ] : null,
        ]));
    }

    /**
     * Build a BreadcrumbList schema.
     *
     * @param  array<int, array<string, string>>  $items  Array of ['name' => ..., 'url' => ...]
     * @param  bool  $includeContext  Whether to include @context (false for @graph items)
     * @return array<string, mixed>
     */
    public function breadcrumbList(array $items, bool $includeContext = true): array
    {
        $listElements = [];
        $position = 1;

        foreach ($items as $item) {
            $listElements[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $item['name'] ?? '',
                'item' => $item['url'] ?? '',
            ];
            $position++;
        }

        $schema = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listElements,
        ];

        if ($includeContext) {
            $schema['@context'] = 'https://schema.org';
        }

        return $schema;
    }

    /**
     * Render a schema array as a JSON-LD script tag.
     *
     * @param  array<string, mixed>  $schema
     */
    public static function toScript(array $schema): string
    {
        $json = json_encode(
            $schema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
            | JSON_THROW_ON_ERROR,
        );

        return sprintf(
            '<script type="application/ld+json">%s</script>',
            $json,
        );
    }
}
