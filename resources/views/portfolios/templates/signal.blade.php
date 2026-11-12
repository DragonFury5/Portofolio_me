@extends('portfolios.layout')

@section('portfolio')
@php($siteInitials = initials($site['display_name']))
<div class="signal">
    <aside class="signal-rail">
        <div class="signal-rail__top">
            <a class="signal-mark" href="#top" aria-label="{{ $site['display_name'] }} — top">{{ $siteInitials }}</a>
            <p class="signal-name">{{ $site['display_name'] }}</p>
            <p class="signal-role">{{ $content['eyebrow'] }}</p>
            @if ($content['availability'] !== '')
                <p class="signal-status"><span></span>{{ $content['availability'] }}</p>
            @endif
        </div>

        <nav class="signal-nav" aria-label="Sections">
            <a href="#work"><span>01</span>Work</a>
            <a href="#about"><span>02</span>About</a>
            <a href="#contact"><span>03</span>Contact</a>
        </nav>

        <div class="signal-rail__bottom">
            @if ($content['location'] !== '')
                <p class="signal-meta"><span>Based</span>{{ $content['location'] }}</p>
            @endif
            @if ($content['links'])
                <div class="signal-links">
                    @foreach ($content['links'] as $link)
                        @php($href = safe_href($link['url']))
                        @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $link['label'] }}<span>↗</span></a>@endif
                    @endforeach
                </div>
            @endif
            @if ($content['email'] !== '')
                <a class="signal-mail-sm" href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a>
            @endif
            <p class="signal-colophon">Built by hand · {{ date('Y') }}</p>
        </div>
    </aside>

    <main class="signal-main" id="top">
        <section class="signal-intro">
            <p class="tpl-eyebrow">{{ $content['location'] ?: 'Independent practice' }} · Portfolio</p>
            <h1>{!! portfolio_markup($content['headline']) !!}</h1>
            <p class="signal-intro__text">{!! portfolio_markup($content['intro']) !!}</p>
        </section>

        <section class="signal-work" id="work" aria-labelledby="signal-work-title">
            <div class="signal-sec-head">
                <h2 id="signal-work-title">Selected work</h2>
                <span>{{ count($content['projects']) }} projects</span>
            </div>

            @if ($content['projects'])
                <ol class="signal-list">
                    @foreach ($content['projects'] as $i => $project)
                        @php($href = safe_href($project['url']))
                        <li class="signal-item">
                            <span class="signal-item__no">{{ sprintf('%02d', $i + 1) }}</span>
                            <div class="signal-item__body">
                                <h3>
                                    @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $project['title'] }}<span class="signal-arrow">↗</span></a>@else{{ $project['title'] }}@endif
                                </h3>
                                @if ($project['summary'] !== '')<p>{{ $project['summary'] }}</p>@endif
                            </div>
                            <div class="signal-item__meta">
                                <span>{{ $project['role'] }}</span>
                                <span>{{ $project['year'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="signal-empty">Projects will appear here once they are published.</p>
            @endif
        </section>

        <section class="signal-about" id="about">
            <div class="signal-about__label">
                <h2>About</h2>
                <span>02</span>
            </div>
            <div class="signal-about__body">
                <p class="signal-about__lead">{!! portfolio_markup($content['about'] ?: $content['intro']) !!}</p>
                <div class="signal-facts">
                    @if ($content['location'] !== '')
                        <div><span>Location</span><strong>{{ $content['location'] }}</strong></div>
                    @endif
                    @if ($content['availability'] !== '')
                        <div><span>Availability</span><strong>{{ $content['availability'] }}</strong></div>
                    @endif
                    @if ($content['links'])
                        <div>
                            <span>Elsewhere</span>
                            <strong>
                                @foreach ($content['links'] as $link)
                                    @php($href = safe_href($link['url']))
                                    @if ($href)<a href="{{ $href }}" target="_blank" rel="noopener">{{ $link['label'] }}</a>@endif
                                @endforeach
                            </strong>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="signal-contact" id="contact">
            <p class="tpl-eyebrow">03 / Contact</p>
            <h2>Have something<br>worth building?</h2>
            @if ($content['email'] !== '')
                <a class="signal-mail" href="mailto:{{ $content['email'] }}">{{ $content['email'] }}<span>↗</span></a>
            @endif
            <p class="signal-contact__note">{{ $content['availability'] ?: 'Open to new work.' }}</p>
        </section>

        <footer class="signal-foot">
            <span>© {{ date('Y') }} {{ $site['display_name'] }}</span>
            <span>Hosted on Portside</span>
        </footer>
    </main>
</div>
@endsection
