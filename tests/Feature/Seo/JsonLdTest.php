<?php

use App\Seo\JsonLd;

it('builds Organization schema with all fields', function () {
    $jsonld = new JsonLd();
    $org = $jsonld->organization([
        'name' => 'Lucio Torres',
        'logo' => 'https://example.com/logo.png',
        'url' => 'https://luciotorres.online',
        'sameAs' => [
            'https://twitter.com/luciotorres',
            'https://facebook.com/luciotorres',
        ],
    ]);

    expect($org)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Lucio Torres',
        'logo' => 'https://example.com/logo.png',
        'url' => 'https://luciotorres.online',
        'sameAs' => [
            'https://twitter.com/luciotorres',
            'https://facebook.com/luciotorres',
        ],
    ]);
});

it('omits sameAs from Organization when empty', function () {
    $jsonld = new JsonLd();
    $org = $jsonld->organization([
        'name' => 'Lucio Torres',
        'url' => 'https://luciotorres.online',
    ]);

    expect($org)->not->toHaveKey('sameAs');
});

it('builds WebSite schema with SearchAction', function () {
    $jsonld = new JsonLd();
    $website = $jsonld->website([
        'name' => 'Lucio Torres',
        'url' => 'https://luciotorres.online',
        'search_url' => 'https://luciotorres.online/?s={search_term_string}',
    ]);

    expect($website)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Lucio Torres',
        'url' => 'https://luciotorres.online',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => 'https://luciotorres.online/?s={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ]);
});

it('builds WebSite schema without SearchAction when no search_url', function () {
    $jsonld = new JsonLd();
    $website = $jsonld->website([
        'name' => 'Lucio Torres',
        'url' => 'https://luciotorres.online',
    ]);

    expect($website)->toHaveKey('name');
    expect($website)->toHaveKey('url');
    expect($website)->not->toHaveKey('potentialAction');
});

it('builds Article schema with all required fields', function () {
    $jsonld = new JsonLd();
    $article = $jsonld->article([
        'headline' => 'Article Title',
        'description' => 'Article description',
        'datePublished' => '2026-01-15T10:00:00+00:00',
        'dateModified' => '2026-01-16T10:00:00+00:00',
        'author' => 'John Doe',
        'image' => 'https://example.com/image.jpg',
        'url' => 'https://luciotorres.online/article',
    ]);

    expect($article)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Article Title',
        'description' => 'Article description',
        'datePublished' => '2026-01-15T10:00:00+00:00',
        'dateModified' => '2026-01-16T10:00:00+00:00',
        'author' => [
            '@type' => 'Person',
            'name' => 'John Doe',
        ],
        'image' => 'https://example.com/image.jpg',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => 'https://luciotorres.online/article',
        ],
    ]);
});

it('builds Article schema without optional fields when missing', function () {
    $jsonld = new JsonLd();
    $article = $jsonld->article([
        'headline' => 'Minimal Article',
    ]);

    expect($article)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Minimal Article',
    ]);
    expect($article)->not->toHaveKey('description');
    expect($article)->not->toHaveKey('image');
    expect($article)->not->toHaveKey('mainEntityOfPage');
    expect($article)->not->toHaveKey('author');
    expect($article)->not->toHaveKey('datePublished');
    expect($article)->not->toHaveKey('dateModified');
});

it('builds BreadcrumbList for single item', function () {
    $jsonld = new JsonLd();
    $breadcrumbs = $jsonld->breadcrumbList([
        ['name' => 'Home', 'url' => 'https://luciotorres.online'],
    ]);

    expect($breadcrumbs)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => 'https://luciotorres.online',
            ],
        ],
    ]);
});

it('builds BreadcrumbList with multiple items', function () {
    $jsonld = new JsonLd();
    $breadcrumbs = $jsonld->breadcrumbList([
        ['name' => 'Home', 'url' => 'https://luciotorres.online'],
        ['name' => 'News', 'url' => 'https://luciotorres.online/category/news'],
        ['name' => 'Article Title', 'url' => 'https://luciotorres.online/article'],
    ]);

    expect($breadcrumbs)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://luciotorres.online'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'News', 'item' => 'https://luciotorres.online/category/news'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => 'Article Title', 'item' => 'https://luciotorres.online/article'],
        ],
    ]);
});

it('builds BreadcrumbList with empty items array', function () {
    $jsonld = new JsonLd();
    $breadcrumbs = $jsonld->breadcrumbList([]);

    expect($breadcrumbs)->toMatchArray([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [],
    ]);
});

it('renders to script tag via toScript', function () {
    $jsonld = new JsonLd();
    $schema = $jsonld->organization([
        'name' => 'Test',
        'url' => 'https://example.com',
    ]);

    $script = JsonLd::toScript($schema);

    expect($script)->toContain('<script type="application/ld+json">');
    expect($script)->toContain('</script>');
    expect($script)->toContain('"@type":"Organization"');
    expect($script)->toContain('"name":"Test"');
});

it('prevents XSS script tag breakout injection', function () {
    $jsonld = new JsonLd();
    $schema = $jsonld->organization([
        'name' => '</script><script>alert("xss")</script>',
    ]);

    $script = JsonLd::toScript($schema);

    // Ensure it does not contain the raw unescaped closing script tag or injection
    expect($script)->not->toContain('</script><script>');
    expect($script)->toContain('\u003C/script\u003E\u003Cscript\u003E');
});
