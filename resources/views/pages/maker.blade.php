@extends('layouts.public')

@php($isSelf = auth()->check() && auth()->id() === $maker->id)

@section('title', $maker->name.' (@'.$maker->username.') · Portside')
@section('description', excerpt_text($maker->bio ?: $maker->name.' makes portfolios on Portside.', 160))
@section('canonical', $maker->publicUrl())
@section('og_type', 'profile')

@section('content')
<div class="maker-hero">
    <div class="wrap maker-hero__grid">
        <div>
            <div class="maker-hero__avatar">
                <x-avatar :user="$maker" :size="64" />
                <div>
                    <h1>{{ $maker->name }}</h1>
                    <p class="maker-hero__handle">&#64;{{ $maker->username }}</p>
                </div>
            </div>
            @if ($maker->bio)
                <p class="maker-hero__bio">{{ $maker->bio }}</p>
            @endif
            <div class="maker-hero__meta">
                @if ($maker->location)
                    <span><x-icon name="globe" :size="13" /> {{ $maker->location }}</span>
                @endif
                <span><x-icon name="clock" :size="13" /> Joined {{ format_date($maker->created_at, 'F Y') }}</span>
                @if ($maker->website)
                    @php($siteHref = safe_href($maker->website))
                    @if ($siteHref)
                        <a href="{{ $siteHref }}" target="_blank" rel="noopener"><x-icon name="external" :size="13" /> {{ preg_replace('#^https?://#', '', $maker->website) }}</a>
                    @endif
                @endif
            </div>
        </div>
        <div>
            @if ($isSelf)
                <div class="side-card">
                    <p class="side-card__label">This is your public page</p>
                    <h3>Looking good.</h3>
                    <p>Anyone with this link can see your published pages. Drafts stay private.</p>
                    <div class="side-card__links">
                        <a href="{{ route('settings.edit') }}"><x-icon name="settings" :size="15" /> Edit your profile</a>
                        <a href="{{ route('dashboard') }}"><x-icon name="layout" :size="15" /> Back to the studio</a>
                    </div>
                </div>
            @else
                <div class="side-card">
                    <p class="side-card__label">On Portside</p>
                    <h3>{{ $portfolios->count() }} published {{ $portfolios->count() === 1 ? 'page' : 'pages' }}</h3>
                    <p>Every page here is a working site. Open one, then go looking for the next.</p>
                    <div class="side-card__links">
                        <a href="{{ route('explore') }}"><x-icon name="arrow-right" :size="15" /> More makers</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<section class="wrap">
    @if ($portfolios->isNotEmpty())
        <div class="maker-sites">
            @foreach ($portfolios as $portfolio)
                <a class="maker-site" href="{{ $portfolio->publicUrl() }}">
                    <div class="maker-site__frame">
                        <iframe src="{{ $portfolio->publicUrl() }}?embed=1"
                                title="Preview of {{ $portfolio->title }}"
                                loading="lazy" tabindex="-1" aria-hidden="true"
                                @if ($portfolio->isCode()) sandbox="allow-scripts allow-forms allow-popups allow-modals" @endif></iframe>
                    </div>
                    <div class="maker-site__meta">
                        <h2>{{ $portfolio->title }}</h2>
                        <p>{{ $portfolio->isCode() ? 'Hand-coded' : $portfolio->templateMeta()['name'] }} · {{ human_number($portfolio->views) }} views</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state" style="margin: 48px 0 80px;">
            <h2>Nothing published yet.</h2>
            <p>{{ $maker->name }} has not put a page in the water. There is plenty of other work to see.</p>
            <a class="btn btn--primary" href="{{ route('explore') }}">Explore the harbour</a>
        </div>
    @endif
</section>
@endsection
