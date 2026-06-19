{{-- SEO Meta Tags --}}
@php
    $__metaRenderer = app(\App\Seo\MetaRenderer::class, ['seoMeta' => $seoMeta]);
@endphp
{!! $__metaRenderer->render() !!}

{{-- JSON-LD Structured Data --}}
@php
    $__jsonLd = app(\App\Seo\JsonLd::class);
    $__combined = ['@context' => 'https://schema.org', '@graph' => $jsonLdGraph];
@endphp
{!! $__jsonLd::toScript($__combined) !!}

