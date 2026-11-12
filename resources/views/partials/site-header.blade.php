@php($navUser = auth()->user())
<header class="site-head">
    <div class="wrap site-head__inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Portside — home">
            <x-brand-mark :size="26" />
            <span class="brand__word">Portside</span>
        </a>

        <nav class="site-nav" aria-label="Main">
            <a href="{{ route('explore') }}" @if (request()->routeIs('explore')) aria-current="page" @endif>Explore</a>
            <a href="{{ route('templates.index') }}" @if (request()->routeIs('templates.*')) aria-current="page" @endif>Templates</a>
            <a href="{{ route('docs.code') }}" @if (request()->routeIs('docs.*')) aria-current="page" @endif>Code guide</a>
            <a href="{{ route('home') }}#faq">FAQ</a>
        </nav>

        <div class="head-actions">
            @auth
                <a class="btn btn--sm btn--outline head-studio" href="{{ route('dashboard') }}">Studio <x-icon name="arrow-right" :size="16" /></a>
                <details class="user-menu">
                    <summary class="user-menu__trigger" aria-label="Your account">
                        <x-avatar :user="$navUser" :size="30" />
                        <span class="user-menu__name">{{ $navUser->name }}</span>
                        <x-icon name="chevron-down" :size="14" class="user-menu__chev" />
                    </summary>
                    <div class="user-menu__panel">
                        <p class="user-menu__handle">&#64;{{ $navUser->username }}</p>
                        <a href="{{ route('dashboard') }}"><x-icon name="layout" :size="16" /> Studio</a>
                        <a href="{{ route('makers.show', $navUser->username) }}"><x-icon name="globe" :size="16" /> Public profile</a>
                        <a href="{{ route('settings.edit') }}"><x-icon name="settings" :size="16" /> Settings</a>
                        @if ($navUser->is_admin)
                            <a href="{{ route('admin.index') }}"><x-icon name="shield" :size="16" /> Moderation</a>
                        @endif
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-menu__logout"><x-icon name="log-out" :size="16" /> Log out</button>
                        </form>
                    </div>
                </details>
            @else
                <a class="head-login" href="{{ route('login') }}">Log in</a>
                <a class="btn btn--sm btn--outline" href="{{ route('register') }}">Start free</a>
            @endauth
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu" data-nav-toggle>
                <x-icon name="menu" :size="22" />
            </button>
        </div>
    </div>
</header>

<div class="mobile-nav" id="mobile-nav" hidden>
    <div class="wrap">
        <nav class="mobile-nav__links" aria-label="Mobile">
            <a href="{{ route('explore') }}">Explore</a>
            <a href="{{ route('templates.index') }}">Templates</a>
            <a href="{{ route('docs.code') }}">Code guide</a>
            <a href="{{ route('home') }}#faq">FAQ</a>
            @auth
                <a href="{{ route('dashboard') }}">Studio</a>
                <a href="{{ route('makers.show', $navUser->username) }}">Public profile</a>
                <a href="{{ route('settings.edit') }}">Settings</a>
            @endauth
        </nav>
        <div class="mobile-nav__actions">
            @auth
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn--outline btn--block" type="submit">Log out</button>
                </form>
            @else
                <a class="btn btn--outline btn--block" href="{{ route('login') }}">Log in</a>
                <a class="btn btn--primary btn--block" href="{{ route('register') }}">Start a portfolio</a>
            @endauth
        </div>
    </div>
</div>
