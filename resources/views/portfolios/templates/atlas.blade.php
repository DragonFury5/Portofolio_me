@extends('portfolios.layout')

@section('portfolio')
@php
    $heroProject = null;
    $rest = $content['projects'];
    if ($rest) {
        $heroProject = array_shift($rest);
    }
@endphp
<div class="atlas">
    <header class="atlas-top">
        <a class="atlas-brand" href="#top">{{ $site['display_name'] }}</a>
        <p class="atlas-top__role">{{ $content['eyebrow'] }}</p>
        <nav class="atlas-nav" aria-label="Sections">
            <a href="#work">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main id="top">
        <section class="atlas-hero">
            <div class="atlas-hero__copy">
                <h1>{!! portfolio_markup($content['headline']) !!}</h1>
                <p class="atlas-intro">{!! portfolio_markup($content['intro']) !!}</p>
                @if ($content['availability'] !== '')
                    <p class="atlas-avail"><span></span>{{ $content['availability'] }}</p>
                @endif
            </div>
            @if ($heroProject)
                @php($heroImg = media_url($heroProject['image']))
                <figure class="atlas-hero__figure">
                    @if ($heroImg)
                        <img src="{{ $heroImg }}" alt="{{ $heroProject['title'] }}" loading="eager" decoding="async">
                    @else
                        <div class="atlas-plate-empty" aria-hidden="true"><span>01</span></div>
                    @endif
                    <figcaption>
                        <span>{{ $heroProject['title'] }}</span>
                        <span>{{ $heroProject['year'] }}</span>
                    </figcaption>
                </figure>
            @endif
        </section>

        <section class="atlas-work" id="work" aria-labelledby="atlas-work-title">
            <div class="atlas-sec-head">
                <h2 id="atlas-work-title">Selected work</h2>
                <span>Plates &amp; commissions</span>
            </div>

            <div class="atlas-grid">
                @foreach ($rest as $i => $project)
                    @php($img = media_url($project['image']))
                    @php($href = safe_href($project['url']))
                    <article class="atlas-plate">
                        <div class="atlas-plate__frame">
                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $project['title'] }}" loading="lazy" decoding="async">
                            @else
                                <div class="atlas-plate-empty" aria-hidden="true"><span>{{ sprintf('%02d', $i + 2) }}</span></div>
                            @endif
                        </div>
                        <div class="atlas-plate__meta">
                            <div>
                                <span class="atlas-plate__no">{{ sprintf('%02d', $i + 2) }}</span>
                                <h3>
                                    @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $project['title'] }}</a>@else{{ $project['title'] }}@endif
                                </h3>
                            </div>
                            <p>{{ $project['role'] }}{{ $project['role'] !== '' && $project['year'] !== '' ? ' · ' : '' }}{{ $project['year'] }}</p>
                        </div>
                        @if ($project['summary'] !== '')
                            <p class="atlas-plate__summary">{{ $project['summary'] }}</p>
                        @endif
                    </article>
                @endforeach

                @if (! $rest && ! $heroProject)
                    <p class="atlas-empty">Work is being catalogued. Check back soon.</p>
                @endif
            </div>
        </section>

        <section class="atlas-about" id="about">
            <div class="atlas-about__label">
                <p class="tpl-eyebrow tpl-eyebrow--light">About</p>
                <p class="atlas-about__loc">{{ $content['location'] }}</p>
            </div>
            <div class="atlas-about__body">
                <p class="atlas-about__lead">{!! portfolio_markup($content['about'] ?: $content['intro']) !!}</p>
                @if ($content['links'])
                    <div class="atlas-links">
                        @foreach ($content['links'] as $link)
                            @php($href = safe_href($link['url']))
                            @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $link['label'] }} ↗</a>@endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="atlas-foot" id="contact">
        <div class="atlas-foot__top">
            <p class="tpl-eyebrow tpl-eyebrow--light">Commissions &amp; prints</p>
            @if ($content['email'] !== '')
                <a class="atlas-mail" href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a>
            @else
                <p class="atlas-mail">{{ $site['display_name'] }}</p>
            @endif
        </div>
        <div class="atlas-foot__bottom">
            <span>{{ $content['location'] }}</span>
            <span>© {{ date('Y') }} {{ $site['display_name'] }}</span>
            <span>Made on Portside</span>
        </div>
    </footer>
</div>
@endsection
