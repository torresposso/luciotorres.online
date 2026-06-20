{{-- SEO Meta Tags --}}
@php
    if (isset($seoMeta)) {
        $__metaRenderer = app(\App\Seo\MetaRenderer::class, ['seo' => $seoMeta]);
        echo $__metaRenderer->render();
    } else {
        $__defaultDesc = 'Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico y mirada progresista.';
        echo '<meta name="description" content="' . htmlspecialchars($__defaultDesc, ENT_QUOTES, 'UTF-8') . '">';
    }
@endphp

{{-- JSON-LD Structured Data --}}
@php
    $__jsonLd = app(\App\Seo\JsonLd::class);
    $__combined = ['@context' => 'https://schema.org', '@graph' => $jsonLdGraph];
@endphp
{!! $__jsonLd::toScript($__combined) !!}

