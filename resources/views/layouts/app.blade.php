@php
    $appUser = auth()->user();
    $bodyClass = trim($__env->yieldContent('body_class')) ?: 'app-body';
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Studio · Portside')</title>
    <meta name="description" content="Your Portside studio.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1B365D">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    @stack('head')
</head>
<body class="{{ $bodyClass }}">
<a class="skip-link" href="#main">Skip to content</a>
<header class="app-head">
    <div class="app-head__inner">
        <div class="app-head__left">
            <a class="brand brand--app" href="{{ route('dashboard') }}" aria-label="Portside studio">
                <x-brand-mark :size="22" />
                <span class="brand__word">Portside</span>
                <span class="app-head__tag">Studio</span>
            </a>
            <nav class="app-nav" aria-label="Studio">
                <a href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Your pages</a>
                <a href="{{ route('explore') }}">Explore</a>
                <a href="{{ route('templates.index') }}">Templates</a>
            </nav>
        </div>
        <div class="app-head__right">
            <a class="btn btn--sm btn--outline app-head__new" href="{{ route('studio.portfolios.create') }}"><x-icon name="plus" :size="15" /> New page</a>
            <details class="user-menu user-menu--app">
                <summary class="user-menu__trigger" aria-label="Your account">
                    <x-avatar :user="$appUser" :size="30" />
                    <span class="user-menu__name">{{ $appUser->name }}</span>
                    <x-icon name="chevron-down" :size="14" class="user-menu__chev" />
                </summary>
                <div class="user-menu__panel">
                    <p class="user-menu__handle">@{{ $appUser->username }}</p>
                    <a href="{{ route('makers.show', $appUser->username) }}"><x-icon name="globe" :size="16" /> Public profile</a>
                    <a href="{{ route('settings.edit') }}"><x-icon name="settings" :size="16" /> Account settings</a>
                    <a href="{{ route('docs.code') }}"><x-icon name="code" :size="16" /> Code guide</a>
                    @if ($appUser->is_admin)
                        <a href="{{ route('admin.index') }}"><x-icon name="shield" :size="16" /> Moderation</a>
                    @endif
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="user-menu__logout"><x-icon name="log-out" :size="16" /> Log out</button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</header>

<main id="main" class="app-main">
    <div class="wrap app-shell">
        @if (session('status'))
            <div class="flash-stack flash-stack--app">
                <div class="flash flash--success" role="status">
                    <span class="flash__icon"><x-icon name="check" :size="16" /></span>
                    <p>{{ session('status') }}</p>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="flash-stack flash-stack--app">
                <div class="flash flash--error" role="alert">
                    <span class="flash__icon"><x-icon name="x" :size="16" /></span>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @yield('content')
    </div>
</main>

<footer class="app-foot">
    <div class="wrap app-foot__inner">
        <p>Portside Studio · open beta</p>
        <p><a href="{{ route('home') }}">Back to portside</a> · <a href="{{ route('docs.code') }}">Code guide</a> · <a href="{{ route('settings.edit') }}">Account</a></p>
    </div>
</footer>
<script src="{{ asset('assets/js/site.js') }}" defer></script>
@stack('scripts')
</body>
</html>
