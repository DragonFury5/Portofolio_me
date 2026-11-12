@extends('portfolios.layout')

@section('portfolio')
<div class="ledger">
    <header class="ledger-top">
        <a class="ledger-brand" href="#top">{{ $site['display_name'] }}<span>®</span></a>
        <nav class="ledger-nav" aria-label="Sections">
            <a href="#work">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main id="top">
        <section class="ledger-intro">
            <div class="ledger-intro__main">
                <p class="tpl-eyebrow">{{ $content['eyebrow'] }}</p>
                <h1>{!! portfolio_markup($content['headline']) !!}</h1>
            </div>
            <div class="ledger-intro__side">
                <p class="ledger-intro__text">{!! portfolio_markup($content['intro']) !!}</p>
                @if ($content['availability'] !== '')
                    <p class="ledger-avail"><span class="ledger-dot"></span>{{ $content['availability'] }}</p>
                @endif
            </div>
        </section>

        <section class="ledger-work" id="work" aria-labelledby="work-title">
            <div class="ledger-sec-head">
                <h2 id="work-title">Selected work</h2>
                <span>Index of projects</span>
            </div>

            @if ($content['projects'])
                <ol class="ledger-list">
                    @foreach ($content['projects'] as $i => $project)
                        @php
                            $href = safe_href($project['url']);
                            $thumb = media_url($project['image']);
                        @endphp
                        <li class="ledger-item">
                            <article class="ledger-row">
                                <span class="ledger-no">{{ sprintf('%02d', $i + 1) }}</span>
                                <div class="ledger-row__body">
                                    <h3 class="ledger-row__title">
                                        @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $project['title'] }}</a>@else{{ $project['title'] }}@endif
                                    </h3>
                                    @if ($project['summary'] !== '')
                                        <p class="ledger-row__summary">{{ $project['summary'] }}</p>
                                    @endif
                                </div>
                                <div class="ledger-row__meta">
                                    @if ($project['role'] !== '')<span>{{ $project['role'] }}</span>@endif
                                    @if ($project['year'] !== '')<span>{{ $project['year'] }}</span>@endif
                                </div>
                                @if ($thumb !== '')
                                    <div class="ledger-row__image"><img src="{{ $thumb }}" alt="{{ $project['title'] }}" loading="lazy" decoding="async"></div>
                                @else
                                    <div class="ledger-row__image ledger-row__image--empty" aria-hidden="true"><span>{{ sprintf('%02d', $i + 1) }}</span></div>
                                @endif
                            </article>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="ledger-empty">Work is being catalogued. Check back soon.</p>
            @endif
        </section>

        <section class="ledger-about" id="about" aria-labelledby="about-title">
            <h2 id="about-title" class="ledger-about__label">About</h2>
            <div class="ledger-about__body">
                <p class="ledger-about__lead">{!! portfolio_markup($content['about'] ?: $content['intro']) !!}</p>
                <dl class="ledger-facts">
                    @if ($content['location'] !== '')
                        <div><dt>Based</dt><dd>{{ $content['location'] }}</dd></div>
                    @endif
                    @if ($content['availability'] !== '')
                        <div><dt>Status</dt><dd>{{ $content['availability'] }}</dd></div>
                    @endif
                    @if ($content['links'])
                        <div>
                            <dt>Elsewhere</dt>
                            <dd class="ledger-facts__links">
                                @foreach ($content['links'] as $link)
                                    @php($linkHref = safe_href($link['url']))
                                    @if ($linkHref)
                                        <a href="{{ $linkHref }}" target="_blank" rel="noopener">{{ $link['label'] }} ↗</a>
                                    @endif
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </section>
    </main>

    <footer class="ledger-foot" id="contact">
        <div class="ledger-foot__inner">
            <p class="tpl-eyebrow tpl-eyebrow--light">Say hello</p>
            @if ($content['email'] !== '')
                <a class="ledger-mail" href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a>
            @else
                <p class="ledger-mail ledger-mail--quiet">{{ $site['display_name'] }}</p>
            @endif
            @if ($content['links'])
                <div class="ledger-foot__links">
                    @foreach ($content['links'] as $link)
                        @php($linkHref = safe_href($link['url']))
                        @if ($linkHref)<a href="{{ $linkHref }}" target="_blank" rel="noopener">{{ $link['label'] }}</a>@endif
                    @endforeach
                </div>
            @endif
        </div>
        <div class="ledger-foot__bottom">
            <span>© {{ date('Y') }} {{ $site['display_name'] }}</span>
            <span>Made on Portside</span>
        </div>
    </footer>
</div>
@endsection
