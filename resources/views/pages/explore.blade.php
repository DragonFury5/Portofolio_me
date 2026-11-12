@extends('layouts.public')

@section('title', 'Explore portfolios · Portside')
@section('description', 'Browse live portfolios made with Portside — editorial templates, hand-coded experiments, and everything between.')
@section('canonical', route('explore'))
@section('body_class', 'explore-page')

@section('content')
@php
    $total = $portfolios->total();
    $buildUrl = function (array $overrides = []) use ($filters) {
        $params = array_merge($filters, $overrides);
        $params = array_filter($params, fn ($value) => $value !== '' && $value !== null);
        return route('explore', $params);
    };
@endphp
<div class="wrap">
    <header class="explore-head">
        <div>
            <p class="eyebrow">The harbour</p>
            <h1>Work worth<br>a slow look.</h1>
        </div>
        <div class="explore-head__aside">
            <p class="explore-head__count">{{ number_format($total) }} published {{ $total === 1 ? 'page' : 'pages' }}, all running live.</p>
            <form class="search-bar" method="get" action="{{ route('explore') }}" role="search">
                @if ($filters['mode'] !== '')<input type="hidden" name="mode" value="{{ $filters['mode'] }}">@endif
                @if ($filters['template'] !== '')<input type="hidden" name="template" value="{{ $filters['template'] }}">@endif
                @if ($filters['sort'] !== 'recent')<input type="hidden" name="sort" value="{{ $filters['sort'] }}">@endif
                <x-icon name="search" :size="17" />
                <label class="visually-hidden" for="explore-q">Search pages and makers</label>
                <input id="explore-q" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search makers, pages, projects…">
                <button class="btn btn--sm btn--primary" type="submit">Search</button>
            </form>
        </div>
    </header>

    <div class="explore-toolbar">
        <div class="explore-toolbar__group">
            <a class="filter-chip" href="{{ $buildUrl(['mode' => '', 'template' => '', 'page' => 1]) }}" @if ($filters['mode'] === '' && $filters['template'] === '') aria-current="true" @endif>Everything</a>
            <a class="filter-chip" href="{{ $buildUrl(['mode' => 'template', 'template' => '', 'page' => 1]) }}" @if ($filters['mode'] === 'template') aria-current="true" @endif>Templates</a>
            <a class="filter-chip" href="{{ $buildUrl(['mode' => 'code', 'template' => '', 'page' => 1]) }}" @if ($filters['mode'] === 'code') aria-current="true" @endif>Hand-coded</a>
            @foreach ($templates as $key => $template)
                <a class="filter-chip" href="{{ $buildUrl(['mode' => 'template', 'template' => $key, 'page' => 1]) }}" @if ($filters['template'] === $key) aria-current="true" @endif>{{ $template['name'] }}</a>
            @endforeach
        </div>
        <form method="get" action="{{ route('explore') }}">
            <input type="hidden" name="q" value="{{ $filters['q'] }}">
            <input type="hidden" name="mode" value="{{ $filters['mode'] }}">
            <input type="hidden" name="template" value="{{ $filters['template'] }}">
            <label class="visually-hidden" for="explore-sort">Sort pages</label>
            <select class="explore-sort" id="explore-sort" name="sort" onchange="this.form.submit()">
                <option value="recent" @selected($filters['sort'] === 'recent')>Recently updated</option>
                <option value="popular" @selected($filters['sort'] === 'popular')>Most viewed</option>
            </select>
            <noscript><button class="btn btn--sm btn--outline" type="submit">Sort</button></noscript>
        </form>
    </div>

    @if ($filters['q'] !== '')
        <p class="muted" style="padding-top: 18px; font-size: 13px; margin: 0;">
            {{ number_format($total) }} {{ $total === 1 ? 'result' : 'results' }} for “{{ $filters['q'] }}”.
            <a href="{{ route('explore') }}" style="color: var(--brand-secondary); text-decoration: underline;">Clear search</a>
        </p>
    @endif

    @if ($portfolios->isNotEmpty())
        <div class="explore-grid">
            @foreach ($portfolios as $index => $portfolio)
                <a class="explore-card" href="{{ $portfolio->publicUrl() }}">
                    <div class="explore-card__frame">
                        <iframe src="{{ $portfolio->publicUrl() }}?embed=1"
                                title="Preview of {{ $portfolio->title }}"
                                loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                tabindex="-1" aria-hidden="true"
                                sandbox="allow-scripts allow-forms allow-popups allow-modals"></iframe>
                    </div>
                    <div class="explore-card__meta">
                        <div>
                            <h2>{{ $portfolio->title }}</h2>
                            <p>{{ $portfolio->user->name }} · {{ $portfolio->isCode() ? 'Hand-coded' : $portfolio->templateMeta()['name'] }} · {{ human_number($portfolio->views) }} views</p>
                        </div>
                        <span class="tag explore-card__tag">{{ $portfolio->isCode() ? 'Code' : 'Template' }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($portfolios->lastPage() > 1)
            <nav class="pagination" aria-label="Pages">
                @if (! $portfolios->onFirstPage())
                    <a class="page-btn" href="{{ $portfolios->previousPageUrl() }}" aria-label="Previous page"><x-icon name="arrow-left" :size="14" /></a>
                @endif
                @for ($i = 1; $i <= $portfolios->lastPage(); $i++)
                    <a class="page-btn" href="{{ $portfolios->url($i) }}" @if ($i === $portfolios->currentPage()) aria-current="page" @endif>{{ $i }}</a>
                @endfor
                @if ($portfolios->hasMorePages())
                    <a class="page-btn" href="{{ $portfolios->nextPageUrl() }}" aria-label="Next page"><x-icon name="arrow-right" :size="14" /></a>
                @endif
            </nav>
        @endif
    @else
        <div class="empty-state" style="margin: 40px 0 24px;">
            <h2>{{ $hasFilters ? 'Nothing matches that yet.' : 'The harbour is quiet.' }}</h2>
            <p>{{ $hasFilters ? 'Try a broader search, or clear the filters and see everything.' : 'No pages have been published yet. Be the first to put something in the water.' }}</p>
            <div class="error-links">
                @if ($hasFilters)<a class="btn btn--outline" href="{{ route('explore') }}">Clear filters</a>@endif
                <a class="btn btn--primary" href="{{ auth()->check() ? route('studio.portfolios.create') : route('register') }}">Start a portfolio</a>
            </div>
        </div>
    @endif
</div>
@endsection
