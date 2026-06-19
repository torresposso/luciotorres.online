<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Homepage
{
    private const CACHE_TTL = 3600;

    public function loadSections(): array
    {
        $sections = $this->getSectionDefinitions();

        $loadedSections = Cache::remember('luciotorres_homepage_sections_ids', self::CACHE_TTL, function () use ($sections) {
            return $this->resolveSectionPosts($sections);
        });

        $allSectionPostIds = [];
        foreach ($loadedSections as $section) {
            $allSectionPostIds = array_merge($allSectionPostIds, $section['post_ids']);
        }

        if (!empty($allSectionPostIds)) {
            $allSectionPostIds = array_unique($allSectionPostIds);
            _prime_post_caches($allSectionPostIds, true, true);
        }

        foreach ($loadedSections as &$section) {
            $section['posts'] = array_filter(array_map('get_post', $section['post_ids']));
        }

        return $loadedSections;
    }

    public function getSectionDefinitions(): array
    {
        return [
            ['slug' => 'analisis',      'name' => 'Análisis',      'desc' => 'Lectura profunda de la coyuntura política, económica y social.',      'icon' => '📊'],
            ['slug' => 'investigacion', 'name' => 'Investigación',  'desc' => 'Reportajes de investigación con fuentes documentales y testimonios.', 'icon' => '🔍'],
            ['slug' => 'opinion',       'name' => 'Opinión',       'desc' => 'Columnas firmadas con postura explícita y análisis crítico.',         'icon' => '✍️'],
            ['slug' => 'deportes',      'name' => 'Deportes',      'desc' => 'Sociología, política y cultura del fenómeno deportivo caribeño.',    'icon' => '🏃'],
            ['slug' => 'ahora',         'name' => 'Ahora',         'desc' => 'Noticias de última hora y flashes de actualización rápida.',         'icon' => '⚡'],
        ];
    }

    private function resolveSectionPosts(array $sections): array
    {
        $seen = [];
        $result = [];

        $slugs = array_column($sections, 'slug');

        $allPosts = get_posts([
            'category_name' => implode(',', $slugs),
            'posts_per_page' => 100,
            'fields' => 'ids',
            'no_found_rows' => true,
        ]);

        $postTerms = wp_get_object_terms($allPosts, 'category', ['fields' => 'all_with_object_id']);

        $postsBySlug = [];
        if (!is_wp_error($postTerms)) {
            foreach ($postTerms as $term) {
                if (in_array($term->slug, $slugs, true)) {
                    $postsBySlug[$term->slug][] = $term->object_id;
                }
            }
        }

        foreach ($sections as $section) {
            $postIds = [];
            $slug = $section['slug'];

            foreach ($allPosts as $id) {
                if (!empty($postsBySlug[$slug]) && in_array($id, $postsBySlug[$slug], true)) {
                    if (!in_array($id, $seen, true)) {
                        $postIds[] = $id;
                        $seen[] = $id;
                        if (count($postIds) >= 3) {
                            break;
                        }
                    }
                }
            }

            if (count($postIds) < 3) {
                $fallbackPosts = get_posts([
                    'category_name' => $slug,
                    'posts_per_page' => 3 - count($postIds),
                    'post__not_in' => $seen,
                    'fields' => 'ids',
                    'no_found_rows' => true,
                ]);

                foreach ($fallbackPosts as $fId) {
                    $postIds[] = $fId;
                    $seen[] = $fId;
                }
            }

            $result[] = array_merge($section, ['post_ids' => $postIds]);
        }

        return $result;
    }
}
