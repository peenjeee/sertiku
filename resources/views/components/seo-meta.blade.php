@props([
    'title' => 'SertiKu - Platform Sertifikat Digital',
    'description' => 'Platform terdepan untuk menerbitkan, mengelola, dan memverifikasi sertifikat digital dengan teknologi QR Code dan blockchain.',
    'keywords' => 'sertifikat digital, verifikasi sertifikat, QR code, blockchain, e-sertifikat, sertifikat online, sertiku',
    'image' => null,
    'url' => null,
    'type' => 'website'
])

@php
    $siteName = 'SertiKu';
    $siteUrl = config('app.url', 'https://sertiku.web.id');
    $currentUrl = $url ?? url()->current();
    $ogImage = $image ?? asset('favicon.svg');
    $logoUrl = asset('favicon.svg');
@endphp

{{-- Primary Meta Tags --}}
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="SertiKu">
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">
<meta name="revisit-after" content="7 days">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $currentUrl }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $currentUrl }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

{{-- Structured Data / JSON-LD --}}
<script type="application/ld+json">
@php
    echo json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebApplication',
        'name' => $siteName,
        'description' => $description,
        'url' => $siteUrl,
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem' => 'Web',
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'IDR'
        ],
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.8',
            'ratingCount' => '150'
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $siteName,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logoUrl
            ]
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
@endphp
</script>
