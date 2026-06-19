<?php

use App\Providers\AnalyticsServiceProvider;
use App\Providers\SeoServiceProvider;
use App\Providers\SitemapServiceProvider;
use Roots\Acorn\Application;
use Roots\Acorn\Sage\SageServiceProvider;

if (! file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'luciotorres'));
}

require $composer;

$app = Application::configure()
    ->withRouting(wordpress: true)
    ->withProviders([
        SageServiceProvider::class,
        AnalyticsServiceProvider::class,
        SeoServiceProvider::class,
        SitemapServiceProvider::class,
    ])
    ->boot();

// Load theme configurations
if (file_exists($setup = __DIR__ . '/app/setup.php')) {
    require_once $setup;
}

if (file_exists($filters = __DIR__ . '/app/filters.php')) {
    require_once $filters;
}

return $app;
