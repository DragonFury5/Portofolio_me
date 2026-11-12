@php
    $pageTitle = trim(strip_tags($__env->yieldContent('title'))) ?: 'Portside — Your work, at home.';
    $pageDescription = trim(strip_tags($__env->yieldContent('description'))) ?: 'A portfolio host for people who would rather build their own page. Start from a considered template or write the HTML, CSS and JS yourself.';
    $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $robots = trim($__env->yieldContent('robots')) ?: 'index, follow';
    $ogType = trim($__env->yieldContent('og_type')) ?: 'website';
    $ogImage = trim($__env->yieldContent('og_image'));
    $bodyClass = trim($__env->yieldContent('body_class'));
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="Portside">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="theme-color" content="#1B365D">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/overrides.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/motion.css') }}">
    @stack('head')
</head>
<body class="site-body {{ $bodyClass }}">
<a class="skip-link" href="#main">Skip to content</a>
@include('partials.site-header')
<div class="scroll-progress" data-scroll-progress aria-hidden="true"><span></span></div>
<div class="spew-origin" data-spew-origin aria-hidden="true">
    <span class="spew-origin__ring"></span>
    <span class="spew-origin__ring"></span>
    <span class="spew-origin__core"></span>
</div>

<main id="main">
    @include('partials.flashes')
    @yield('content')
</main>

@include('partials.site-footer')
<script src="{{ asset('assets/js/site.js') }}" defer></script>
<script src="{{ asset('assets/js/motion.js') }}" defer></script>
@stack('scripts')
</body>
</html>
