<?php

namespace App\Seo;

use WP_Query;

class SitemapController
{
    public function handle(): void
    {
        if (get_query_var('vox_sitemap') !== '1') {
            return;
        }

        $idQuery = new WP_Query([
            'post_type' => ['post', 'page'],
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);

        $entries = [];

        if (!empty($idQuery->posts)) {
            $batches = array_chunk($idQuery->posts, 100);

            foreach ($batches as $batch) {
                $batchQuery = new WP_Query([
                    'post_type' => ['post', 'page'],
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'post__in' => $batch,
                    'orderby' => 'post__in',
                    'no_found_rows' => true,
                ]);

                while ($batchQuery->have_posts()) {
                    $batchQuery->the_post();
                    $postId = get_the_ID();

                    if (get_post_meta($postId, '_luciotorres_noindex', true) === '1') {
                        continue;
                    }

                    $entries[] = [
                        'loc' => get_permalink($postId),
                        'lastmod' => get_the_modified_date('Y-m-d', $postId),
                        'priority' => get_post_type($postId) === 'page' ? '0.8' : '0.7',
                        'changefreq' => get_post_type($postId) === 'page' ? 'monthly' : 'weekly',
                        'type' => get_post_type($postId),
                    ];
                }
            }
        }

        wp_reset_postdata();

        $sitemap = new Sitemap($entries);

        status_header(200);
        header('Content-Type: ' . $sitemap->getContentType() . '; charset=UTF-8');
        header('Cache-Control: ' . $sitemap->getCacheControl());

        $lastMod = $sitemap->getLastModified();
        if ($lastMod !== null) {
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', strtotime($lastMod)) . ' GMT');
        }

        wp_die($sitemap->toXml());
    }
}
