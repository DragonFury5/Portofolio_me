@extends('portfolios.layout')

@section('portfolio')
<div class="tide">
    <header class="tide-top">
        <a class="tide-brand" href="#top">{{ $site['display_name'] }}</a>
        <p class="tide-top__note">{{ $content['availability'] }}</p>
        <nav class="tide-nav" aria-label="Sections">
            <a href="#work">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main id="top">
        <section class="tide-hero">
            <div class="tide-hero__copy">
                <p class="tpl-eyebrow">{{ $content['eyebrow'] }}</p>
                <h1>{!! portfolio_markup($content['headline']) !!}</h1>
            </div>
            <div class="tide-hero__side">
                <div class="tide-portrait">
                    @if ($site['avatar'] !== '')
                        <img src="{{ media_url($site['avatar']) }}" alt="{{ $site['display_name'] }}" loading="lazy">
                    @else
                        <span>{{ initials($site['display_name']) }}</span>
                    @endif
                </div>
                <p class="tide-intro">{!! portfolio_markup($content['intro']) !!}</p>
                @if ($content['location'] !== '')
                    <p class="tide-loc">{{ $content['location'] }}</p>
                @endif
            </div>
        </section>

        <section class="tide-rail" id="work" aria-labelledby="tide-work-title">
            <div class="tide-rail__head">
                <h2 id="tide-work-title">Things I’ve made</h2>
                <p><span class="tide-arrow">→</span> Scroll the shelf</p>
            </div>
            <div class="tide-scroller" tabindex="0" role="region" aria-label="Selected projects, scroll horizontally">
                @foreach ($content['projects'] as $i => $project)
                    @php
                        $href = safe_href($project['url']);
                        $img = media_url($project['image']);
                    @endphp
                    <article class="tide-card">
                        <div class="tide-card__image">
                            @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener" aria-label="{{ $project['title'] }}">@endif
                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $project['title'] }}" loading="lazy" decoding="async">
                            @else
                                <div class="tide-card__empty" aria-hidden="true"><span>{{ sprintf('%02d', $i + 1) }}</span></div>
                            @endif
                            @if ($href)</a>@endif
                        </div>
                        <div class="tide-card__meta">
                            <span>{{ sprintf('%02d', $i + 1) }} / {{ $project['year'] }}</span>
                            <span>{{ $project['role'] }}</span>
                        </div>
                        <h3>
                            @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $project['title'] }}</a>@else{{ $project['title'] }}@endif
                        </h3>
                        @if ($project['summary'] !== '')<p>{{ $project['summary'] }}</p>@endif
                    </article>
                @endforeach
                @if (! $content['projects'])
                    <p class="tide-empty">The shelf is being re-stocked. Check back soon.</p>
                @endif
            </div>
        </section>

        <section class="tide-about" id="about">
            <div class="tide-about__head">
                <p class="tpl-eyebrow">A little context</p>
                <h2>{{ $site['display_name'] }} <em>makes things.</em></h2>
            </div>
            <div class="tide-about__body">
                <p>{!! portfolio_markup($content['about'] ?: $content['intro']) !!}</p>
                <div class="tide-facts">
                    @if ($content['location'] !== '')
                        <p><span>Based in</span>{{ $content['location'] }}</p>
                    @endif
                    @if ($content['availability'] !== '')
                        <p><span>Right now</span>{{ $content['availability'] }}</p>
                    @endif
                    @if ($content['links'])
                        <p class="tide-facts__links">
                            <span>Elsewhere</span>
                            @foreach ($content['links'] as $link)
                                @php($href = safe_href($link['url']))
                                @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $link['label'] }}</a>@endif
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <footer class="tide-foot" id="contact">
        <div class="tide-foot__inner">
            <div>
                <p class="tpl-eyebrow tpl-eyebrow--light">Say hello</p>
                @if ($content['email'] !== '')
                    <a class="tide-mail" href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a>
                @else
                    <p class="tide-mail">{{ $site['display_name'] }}</p>
                @endif
            </div>
            <p class="tide-foot__note">{{ $content['availability'] ?: 'Open for commissions.' }}</p>
        </div>
        <div class="tide-foot__bottom">
            <span>© {{ date('Y') }} {{ $site['display_name'] }}</span>
            <span>Made on Portside</span>
        </div>
    </footer>
</div>
@endsection
