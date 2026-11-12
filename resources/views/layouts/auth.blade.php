@php
    $authTitle = trim(strip_tags($__env->yieldContent('title'))) ?: 'Portside';
    $authDescription = trim(strip_tags($__env->yieldContent('description'))) ?: 'Make a portfolio that is actually yours.';
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $authTitle }}</title>
    <meta name="description" content="{{ $authDescription }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1B365D">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/overrides.css') }}">
</head>
<body class="auth-body">
<a class="skip-link" href="#auth-form">Skip to form</a>
<header class="auth-head">
    <div class="wrap auth-head__inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Portside — home">
            <x-brand-mark :size="25" />
            <span class="brand__word">Portside</span>
        </a>
        <a class="auth-head__back" href="{{ route('home') }}"><x-icon name="arrow-left" :size="15" /> Back to the harbour</a>
    </div>
</header>

<main class="auth-main">
    <div class="auth-grid">
        <aside class="auth-aside">
            <div class="auth-aside__inner">
                <p class="eyebrow eyebrow--light">Your work, at home</p>
                <p class="auth-aside__quote">A page, not a profile in someone else’s grid.</p>
                <ul class="auth-aside__list">
                    <li><x-icon name="check" :size="16" /><span><strong>No-code templates</strong> for when the work matters more than the markup.</span></li>
                    <li><x-icon name="check" :size="16" /><span><strong>Your own HTML, CSS and JS</strong> when it doesn’t. Animation, canvas, a whole strange idea.</span></li>
                    <li><x-icon name="check" :size="16" /><span><strong>Portable by default.</strong> Export a code page as one file. Keep the address for as long as you like.</span></li>
                </ul>
                <p class="auth-aside__foot">Four pages · Free in beta · No card</p>
            </div>
            <svg class="auth-aside__art" viewBox="0 0 520 270" fill="none" aria-hidden="true">
                <path d="M0 196c54-6 82-46 136-40s74 54 128 44 72-56 126-48 86 40 130 32" stroke="#60C1E5" stroke-width="1.5" opacity=".7"/>
                <path d="M0 220c54-6 82-46 136-40s74 54 128 44 72-56 126-48 86 40 130 32" stroke="#A0E0EF" stroke-width="1.5" opacity=".4"/>
                <path d="M0 244c54-6 82-46 136-40s74 54 128 44 72-56 126-48 86 40 130 32" stroke="#60C1E5" stroke-width="1.5" opacity=".2"/>
                <circle cx="398" cy="82" r="54" stroke="#A0E0EF" stroke-width="1" opacity=".55"/>
                <circle cx="398" cy="82" r="32" stroke="#A0E0EF" stroke-width="1" opacity=".35"/>
                <path d="M398 20v124M336 82h124" stroke="#A0E0EF" stroke-width="1" opacity=".25"/>
                <path d="M112 70v38M93 89h38" stroke="#60C1E5" stroke-width="1.4"/>
            </svg>
        </aside>

        <section class="auth-panel" id="auth-form">
            <div class="auth-form">
                @yield('form')
            </div>
        </section>
    </div>
</main>

<footer class="auth-foot">
    <div class="wrap auth-foot__inner">
        <p>© {{ date('Y') }} Portside · built with Laravel</p>
        <p><a href="{{ route('terms') }}">Terms</a> · <a href="{{ route('privacy') }}">Privacy</a></p>
    </div>
</footer>
<script src="{{ asset('assets/js/site.js') }}" defer></script>
</body>
</html>
