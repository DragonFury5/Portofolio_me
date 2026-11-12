@php
    $isPreview = $preview ?? false;
    $isEmbed = $embed ?? false;
    $showDraftBar = $isPreview && $portfolio->exists && auth()->id() === $portfolio->user_id;
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if ($isPreview || $isEmbed)
        <meta name="robots" content="noindex, nofollow">
    @else
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:type" content="profile">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:site_name" content="Portside">
        @if ($ogImage)
            <meta property="og:image" content="{{ $ogImage }}">
            <meta name="twitter:card" content="summary_large_image">
        @endif
    @endif
    <meta name="theme-color" content="#1B365D">
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/overrides.css') }}">
</head>
<body class="{{ $bodyClass }}{{ $isEmbed ? ' is-embed' : '' }}{{ $isPreview ? ' is-preview' : '' }}" data-page-template="{{ $templateKey }}" data-page-accent="{{ $accent }}">
@if ($showDraftBar)
    <div class="ps-draft-bar">
        <strong>Draft</strong>
        <span>Only you can see this page. Publishing makes it public.</span>
        <a href="{{ $portfolio->editUrl() }}">Edit page</a>
        <form method="post" action="{{ route('studio.portfolios.publish', $portfolio) }}">
            @csrf
            <input type="hidden" name="published" value="1">
            <button type="submit">Publish now</button>
        </form>
    </div>
@endif
@yield('portfolio')
@if (! $isEmbed && ! $isPreview)
    @include('portfolios.partials.badge')
@endif
</body>
</html>
