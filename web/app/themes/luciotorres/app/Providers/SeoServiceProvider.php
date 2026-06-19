<?php

namespace App\Providers;

use App\Seo\JsonLd;
use App\Seo\MetaBox;
use App\Seo\Migration;
use App\View\Composers\Seo as SeoComposer;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(JsonLd::class, function () {
            return new JsonLd();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->make('view')->composer('layouts.app', SeoComposer::class);

        $metaBox = new MetaBox();

        add_action('add_meta_boxes', [$metaBox, 'register']);
        add_action('save_post', [$metaBox, 'save'], 10, 1);

        if (defined('WP_CLI') && \WP_CLI) {
            \WP_CLI::add_command('luciotorres migrate-seo', [Migration::class, 'handle']);
        }
    }
}
