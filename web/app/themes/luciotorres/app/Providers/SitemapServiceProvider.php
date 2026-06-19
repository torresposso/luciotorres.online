<?php

namespace App\Providers;

use App\Seo\SitemapController;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action('init', function () {
            add_rewrite_rule(
                '^sitemap\.xml$',
                'index.php?vox_sitemap=1',
                'top',
            );

            add_rewrite_tag('%vox_sitemap%', '1');
        });

        add_action('template_redirect', function () {
            if (get_query_var('vox_sitemap') === '1') {
                (new SitemapController())->handle();
            }
        });
    }
}
