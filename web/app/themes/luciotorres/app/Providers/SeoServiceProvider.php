<?php

namespace App\Providers;

use App\Seo\Contracts\JsonLdInterface;
use App\Seo\Contracts\SeoMetaInterface;
use App\Seo\JsonLd;
use App\Seo\MetaBox;
use App\Seo\Migration;
use App\Seo\SeoBreadcrumbBuilder;
use App\Seo\SeoDataProvider;
use App\Seo\SeoGraphBuilder;
use App\Seo\SeoMeta;
use App\View\Composers\Seo as SeoComposer;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(JsonLdInterface::class, JsonLd::class);
        $this->app->singleton(JsonLd::class, function () {
            return new JsonLd();
        });
        $this->app->bind(SeoMetaInterface::class, SeoMeta::class);
        $this->app->singleton(SeoDataProvider::class);
        $this->app->singleton(SeoGraphBuilder::class);
        $this->app->singleton(SeoBreadcrumbBuilder::class);
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
