<?php

namespace App\Seo;

use App\Seo\Contracts\SeoMetaInterface;
use Illuminate\Support\Facades\Vite;

class SeoDataProvider
{
    private const string DEFAULT_OG_IMAGE = 'resources/images/og-home.jpg';

    public function forCurrentRequest(): SeoMetaInterface
    {
        if (is_singular()) {
            return $this->forPost(get_queried_object_id());
        }

        if (is_front_page() || is_home()) {
            return $this->forHome();
        }

        return new SeoMeta();
    }

    public function forPost(int $postId): SeoMetaInterface
    {
        $ogImageId = get_post_meta($postId, '_luciotorres_og_image', true);

        if (empty($ogImageId)) {
            $ogImageId = get_post_thumbnail_id($postId);
            $ogImageUrl = $ogImageId ? wp_get_attachment_url($ogImageId) : null;
        } else {
            $ogImageUrl = wp_get_attachment_url((int) $ogImageId);
        }

        return new SeoMeta([
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
        ], true);
    }

    public function forHome(): SeoMetaInterface
    {
        $desc = get_bloginfo('description');
        if (empty($desc) || $desc === 'Just another WordPress site') {
            $desc = 'Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico y mirada progresista.';
        }

        return new SeoMeta([
            'post_title' => get_bloginfo('name'),
            'post_url' => home_url('/'),
            'meta_desc' => $desc,
            'og_title' => get_bloginfo('name') . ' — ' . $desc,
            'og_desc' => $desc,
            'og_image_url' => Vite::asset(self::DEFAULT_OG_IMAGE),
            'is_home' => true,
            'canonical' => home_url('/'),
        ], true);
    }
}
