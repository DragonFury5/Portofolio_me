@extends('layouts.public')

@section('title', 'Portside — Your work, at home.')
@section('description', 'A portfolio host for people who would rather build their own page. Start from a considered template or write the HTML, CSS and JS yourself.')
@section('canonical', route('home'))
@section('body_class', 'home')

@section('content')
<section class="hero">
    <div class="wrap hero__grid">
        <div class="hero__copy">
            <p class="eyebrow">Portfolio hosting · Open beta</p>
            <h1>Your work,<br>at <span class="hero__home">home.</span></h1>
            <p class="hero__sub">
                A real page on the web, not a profile in someone else’s grid. Start from a
                <strong>considered template</strong>, or write the <strong>HTML, CSS and JS</strong> yourself.
                Publish to a link you can hand to anyone.
            </p>
            <div class="hero__ctas">
                <a class="btn btn--primary btn--lg" href="{{ auth()->check() ? route('dashboard') : route('register') }}">
                    {{ auth()->check() ? 'Open your studio' : 'Start a portfolio' }} <x-icon name="arrow-right" :size="17" />
                </a>
                <a class="link-arrow" href="{{ route('explore') }}">See what people built <x-icon name="arrow-up-right" :size="15" /></a>
            </div>
            <p class="hero__fine">
                <span><x-icon name="check" :size="13" /> Free while in beta</span>
                <span><x-icon name="check" :size="13" /> Your markup stays yours</span>
                <span><x-icon name="check" :size="13" /> No card, no ads</span>
            </p>
        </div>

        <div class="hero-art" aria-label="A portfolio page shown next to its source code">
            <div class="hero-code" aria-hidden="true">
                <div class="hero-code__bar"><i></i><i></i><i></i><em>portfolio.css</em></div>
                <pre><code><span class="c-comment">/* made by hand, not by a wizard */</span>
<span class="c-tag">.page</span> {
  <span class="c-prop">max-width</span>: <span class="c-num">72ch</span>;
  <span class="c-prop">display</span>: grid;
  <span class="c-prop">grid-template</span>: <span class="c-str">"name"</span> <span class="c-num">1fr</span>;
}

<span class="c-key">const</span> motion = {
  <span class="c-prop">ease</span>: <span class="c-str">"slow, deliberate"</span>,
  <span class="c-prop">scroll</span>: <span class="c-num">480</span>,
  <span class="c-prop">bounce</span>: <span class="c-num">false</span>
};</code></pre>
            </div>

            <div class="hero-window">
                <div class="browser-bar">
                    <span class="browser-dots"><i></i><i></i><i></i></span>
                    <span class="browser-url">portside.test/p/mara-ellison</span>
                    <span class="browser-live">Live</span>
                </div>
                <div class="mini-site">
                    <div class="mini-site__head">
                        <strong>Mara Ellison</strong>
                        <span>Work / About</span>
                    </div>
                    <p class="mini-site__kicker">Graphic design · Rotterdam</p>
                    <h3>Printed matter for people who still <em>read.</em></h3>
                    <div class="mini-site__grid">
                        <figure>
                            <img src="{{ asset('assets/img/seed/harbor-mark.svg') }}" alt="" loading="eager">
                            <figcaption><span>01 Harbor identity</span><span>2025</span></figcaption>
                        </figure>
                        <figure>
                            <img src="{{ asset('assets/img/seed/silt-specimen.svg') }}" alt="" loading="eager">
                            <figcaption><span>02 Silt</span><span>2024</span></figcaption>
                        </figure>
                    </div>
                    <p class="mini-site__foot"><span>Open for commissions</span><span>hello@mara.studio</span></p>
                </div>
            </div>

            <div class="hero-stamp" aria-hidden="true">Open<br>beta<br>{{ date('Y') }}</div>
            <div class="hero-note" aria-hidden="true">
                <svg viewBox="0 0 60 44" fill="none"><path d="M4 8c14 1 20 9 21 23" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M18 26l7 6 6-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>Same page.<br>Both ways.</span>
            </div>
        </div>
    </div>
</section>

<div class="hero-strip">
    <div class="wrap hero-strip__inner">
        <span class="hero-strip__label">Made here</span>
        <ul class="hero-strip__list">
            <li>Quiet editorial portfolios</li>
            <li>Photo essays</li>
            <li>Developer pages</li>
            <li>Type specimens</li>
            <li>Experiments with too much motion</li>
        </ul>
    </div>
</div>

<section class="section" id="how">
    <div class="wrap">
        <div class="section-head">
            <div>
                <p class="eyebrow">01 / Two ways in</p>
                <h2>Pick a lane.<br>Change your mind later.</h2>
            </div>
            <p class="section-head__note">
                You don’t have to choose between “no code” and “my code” forever.
                Start where you are, keep the same address, and take the lid off when you want.
            </p>
        </div>

        <div class="route-grid">
            <article class="route-card">
                <div class="route-card__top">
                    <span class="route-card__no">01</span>
                    <span class="tag">No code needed</span>
                </div>
                <h3>Start from a template.</h3>
                <p>
                    Four starting points, drawn by a designer rather than a committee.
                    Replace the words, drop in your work, publish. Every template keeps the
                    same content, so switching does not mean starting over.
                </p>
                <ul class="tick-list">
                    <li><x-icon name="check" :size="14" /> Real project pages, not a single scroll</li>
                    <li><x-icon name="check" :size="14" /> Uploads, links, availability, contact</li>
                    <li><x-icon name="check" :size="14" /> Looks deliberate on a phone too</li>
                </ul>
                <a class="link-arrow" href="{{ route('templates.index') }}">Browse the four <x-icon name="arrow-right" :size="15" /></a>

                <div class="route-visual route-visual--templates" aria-hidden="true">
                    <span class="route-thumb route-thumb--a" data-name="Ledger"></span>
                    <span class="route-thumb route-thumb--b" data-name="Signal"></span>
                    <span class="route-thumb route-thumb--c" data-name="Atlas"></span>
                    <span class="route-thumb route-thumb--d" data-name="Tide"></span>
                </div>
            </article>

            <article class="route-card route-card--offset">
                <div class="route-card__top">
                    <span class="route-card__no">02</span>
                    <span class="tag tag--accent">100% freedom</span>
                </div>
                <h3>Or bring your own code.</h3>
                <p>
                    HTML, CSS, JavaScript, a CDN of your choosing. Scroll-driven type,
                    a canvas experiment, a cursor trail from 2003. It is your page.
                </p>
                <ul class="tick-list">
                    <li><x-icon name="check" :size="14" /> Live preview beside the editor</li>
                    <li><x-icon name="check" :size="14" /> Import libraries like GSAP or Three.js</li>
                    <li><x-icon name="check" :size="14" /> Download the whole page any time</li>
                </ul>
                <a class="link-arrow" href="{{ route('docs.code') }}">Read the code guide <x-icon name="arrow-right" :size="15" /></a>

                <div class="route-visual" aria-hidden="true">
                    <div class="route-visual__chrome"><i></i><i></i><i></i><span>index.html</span></div>
                    <pre class="route-code"><span class="c-tag">&lt;section</span> <span class="c-prop">class</span>=<span class="c-str">"hero"</span><span class="c-tag">&gt;</span>
  <span class="c-tag">&lt;h1&gt;</span>Hello, <span class="c-tag">&lt;em&gt;</span>harbour<span class="c-tag">&lt;/em&gt;&lt;/h1&gt;</span>
<span class="c-tag">&lt;/section&gt;</span>

<span class="c-comment">// your animation, your rules</span>
<span class="c-key">gsap</span>.<span class="c-prop">to</span>(<span class="c-str">".hero h1"</span>, { <span class="c-prop">y</span>: <span class="c-num">0</span> });</pre>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section section--dark" id="editor">
    <div class="wrap editor-showcase">
        <div class="editor-showcase__copy">
            <p class="eyebrow eyebrow--light">02 / The editor</p>
            <h2>Your code.<br>Live, in the room.</h2>
            <p>
                A split editor with a real browser preview. Save early, save often — or let
                autosave do it while you chase a keyframe. Nothing is compiled, minified, or
                hidden behind a proprietary layer.
            </p>
            <ul class="feature-list">
                <li>
                    <x-icon name="monitor" :size="18" />
                    <span><strong>A live preview that refreshes as you type.</strong> Desktop and phone widths, one click apart.</span>
                </li>
                <li>
                    <x-icon name="code" :size="18" />
                    <span><strong>Any CDN, any library.</strong> GSAP, Three.js, Lenis, a font you love. We don’t get in the way.</span>
                </li>
                <li>
                    <x-icon name="download" :size="18" />
                    <span><strong>Export, not lock-in.</strong> Download your page as one HTML file and host it anywhere.</span>
                </li>
            </ul>
            <a class="link-arrow link-arrow--light" href="{{ route('docs.code') }}">How code mode works <x-icon name="arrow-right" :size="15" /></a>
        </div>

        <div class="editor-mock" aria-label="Illustration of the Portside code editor">
            <div class="editor-mock__bar">
                <span>index.html</span>
                <span>styles.css</span>
                <span>script.js</span>
                <span class="editor-mock__saved">Saved</span>
            </div>
            <div class="editor-mock__body">
                <pre><code><span class="c-comment">&lt;!-- your page, your rules --&gt;</span>
<span class="c-tag">&lt;header</span> <span class="c-prop">class</span>=<span class="c-str">"intro"</span><span class="c-tag">&gt;</span>
  <span class="c-tag">&lt;p</span> <span class="c-prop">class</span>=<span class="c-str">"kicker"</span><span class="c-tag">&gt;</span>Illustrator, Lisbon<span class="c-tag">&lt;/p&gt;</span>
  <span class="c-tag">&lt;h1&gt;</span>Pictures for <span class="c-tag">&lt;em&gt;</span>slow<span class="c-tag">&lt;/em&gt;</span><span class="c-tag">&lt;br&gt;</span>mornings.<span class="c-tag">&lt;/h1&gt;</span>
<span class="c-tag">&lt;/header&gt;</span>

<span class="c-tag">&lt;script&gt;</span>
  <span class="c-key">const</span> io = <span class="c-key">new</span> <span class="c-prop">IntersectionObserver</span>(
    entries =&gt; entries.<span class="c-fn">forEach</span>(...)
  );
<span class="c-tag">&lt;/script&gt;</span></code></pre>
                <div class="editor-mock__preview">
                    <div class="editor-mock__page">
                        <p>Illustration · Lisbon</p>
                        <h3>Pictures for<br><em>slow mornings.</em></h3>
                        <div class="editor-mock__lines"><span></span><span></span><span></span></div>
                        <div class="editor-mock__plate"></div>
                    </div>
                    <span class="editor-mock__caption">preview · 390 × 844 · auto</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="templates">
    <div class="wrap">
        <div class="section-head">
            <div>
                <p class="eyebrow">03 / Four starting points</p>
                <h2>Templates that don’t look like templates.</h2>
            </div>
            <p class="section-head__note">
                Each one is a different page, not a different shade of blue. Same content
                underneath, so you can move between them without retyping your life.
            </p>
        </div>

        <div class="template-grid">
            @foreach ($templates as $key => $template)
                <a class="tpl-card" href="{{ route('templates.index') }}#{{ $key }}">
                    <div class="tpl-card__preview">
                        <x-template-mini :template="$key" />
                    </div>
                    <div class="tpl-card__meta">
                        <div>
                            <h3>{{ $template['name'] }}</h3>
                            <p>{{ $template['kicker'] }} · {{ $template['best'] }}</p>
                        </div>
                        <span class="tag">Template</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" id="work">
    <div class="wrap">
        <div class="section-head">
            <div>
                <p class="eyebrow">04 / From the harbour</p>
                <h2>Live pages,<br>not screenshots.</h2>
            </div>
            <p class="section-head__note">
                Every frame below is the actual page, running in place. Some are templates.
                Some are hand-written code. All of them belong to their makers.
            </p>
        </div>

        @if ($featured->isNotEmpty())
            @php
                $lead = $featured->first();
                $rest = $featured->slice(1);
            @endphp
            <div class="work-grid">
                <a class="work-card work-card--lead" href="{{ $lead->publicUrl() }}">
                    <div class="work-card__frame">
                        <iframe src="{{ $lead->publicUrl() }}?embed=1"
                                title="Preview of {{ $lead->title }}"
                                loading="lazy" tabindex="-1" aria-hidden="true"
                                @if ($lead->isCode()) sandbox="allow-scripts allow-forms allow-popups allow-modals" @endif></iframe>
                        <span class="work-card__open">Open page <x-icon name="arrow-up-right" :size="13" /></span>
                    </div>
                    <div class="work-card__meta">
                        <h3>{{ $lead->user->name }}</h3>
                        <p>{{ $lead->isCode() ? 'Hand-coded' : $lead->templateMeta()['name'] }} · {{ excerpt_text($lead->description ?: 'Portfolio', 42) }}</p>
                    </div>
                </a>

                <div class="work-card__stack">
                    @foreach ($rest as $portfolio)
                        <a class="work-card work-card--small" href="{{ $portfolio->publicUrl() }}">
                            <div class="work-card__frame">
                                <iframe src="{{ $portfolio->publicUrl() }}?embed=1"
                                        title="Preview of {{ $portfolio->title }}"
                                        loading="lazy" tabindex="-1" aria-hidden="true"
                                        @if ($portfolio->isCode()) sandbox="allow-scripts allow-forms allow-popups allow-modals" @endif></iframe>
                                <span class="work-card__open">Open page <x-icon name="arrow-up-right" :size="13" /></span>
                            </div>
                            <div class="work-card__meta">
                                <h3>{{ $portfolio->user->name }}</h3>
                                <p>{{ $portfolio->isCode() ? 'Hand-coded' : $portfolio->templateMeta()['name'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <h2>The harbour is quiet.</h2>
                <p>No pages have been published yet. Be the first to put something in the water.</p>
                <a class="btn btn--primary" href="{{ route('register') }}">Start a portfolio</a>
            </div>
        @endif

        <div class="work-section__foot">
            <p>View counts are ours. No third-party trackers, no cookie banner.</p>
            <a class="link-arrow" href="{{ route('explore') }}">Explore all pages <x-icon name="arrow-right" :size="15" /></a>
        </div>
    </div>
</section>

<section class="section section--dark" id="principles">
    <div class="wrap">
        <div class="manifesto__grid">
            <div>
                <p class="eyebrow eyebrow--light">05 / What we believe</p>
                <h2>No drag handles. No app store. No page like <em>everyone else’s.</em></h2>
                <p class="manifesto__aside">
                    We built the opposite of a template factory. Then we added templates,
                    because not everyone wants to write CSS at 1am.
                </p>
            </div>
            <div class="principles">
                <article class="principle">
                    <span>01</span>
                    <div>
                        <h3>Your markup stays yours.</h3>
                        <p>Code pages export as a single HTML file. Templates are a starting point, not a lease. Nothing here is encrypted or trapped behind a plan.</p>
                    </div>
                </article>
                <article class="principle">
                    <span>02</span>
                    <div>
                        <h3>Sandboxed by default.</h3>
                        <p>Custom code runs with an opaque origin and no access to cookies or other people’s pages. Your experiments stay experiments, safely.</p>
                    </div>
                </article>
                <article class="principle">
                    <span>03</span>
                    <div>
                        <h3>Fewer features, done properly.</h3>
                        <p>No booking widget, no dropshipping app, no AI copy generator. A page, an editor, uploads and a link. That is the product.</p>
                    </div>
                </article>
            </div>
        </div>

        <div class="beta-band">
            <div>
                <p class="eyebrow eyebrow--light">Open beta</p>
                <h3>Free while we’re small. No card, no trial clock.</h3>
            </div>
            <ul class="beta-band__limits">
                <li><x-icon name="check" :size="13" /> Up to 4 pages per person</li>
                <li><x-icon name="check" :size="13" /> 5 MB per image, 300 KB of HTML</li>
                <li><x-icon name="check" :size="13" /> Custom domains when we leave beta</li>
            </ul>
            <a class="btn btn--light" href="{{ auth()->check() ? route('dashboard') : route('register') }}">Start a portfolio <x-icon name="arrow-right" :size="16" /></a>
        </div>
    </div>
</section>

<section class="section" id="faq">
    <div class="wrap faq__grid">
        <div class="faq__intro">
            <p class="eyebrow">06 / Questions</p>
            <h2>Ask us<br>anything.</h2>
            <p>
                The honest answers, including the parts where we say “not yet.”
                If your question is missing, the code guide goes deeper into the technical side.
            </p>
            <a class="link-arrow" href="{{ route('docs.code') }}" style="margin-top: 18px;">Read the code guide <x-icon name="arrow-right" :size="15" /></a>
        </div>

        <div class="faq__list">
            <details class="faq-item" open>
                <summary>Can I really write all of my own code? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>Yes. HTML, CSS and JavaScript in three panes, with a live preview. You can import any library from a CDN — motion, canvas, WebGL, a font you like — and use whatever markup you want. There is no visual editor in the way.</p>
                    <p>The one boundary is server-side code. Pages are static, so there is no PHP, Python or database behind your site. For forms, use a third-party endpoint or a mailto link.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>What if I can’t code? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>Use a template. You fill in a headline, a short bio, projects and links; the template handles layout, type and mobile. Four templates share the same content, so you can switch the look whenever you like without retyping anything.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>Can I use my own domain? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>Not during the beta. Your page lives at <code>/p/your-address</code>, and your profile at <code>/u/your-name</code>. Custom domains are the first thing on the list after we leave beta. We would rather do them properly than ship a broken redirect.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>Can I take my page somewhere else? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>Custom-code pages download as one self-contained HTML file from the editor. Host it on Netlify, GitHub Pages, Vercel, or a folder on your desktop. Template pages are rendered by Portside, so they do not export as a single file yet — that is on the roadmap.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>What isn’t allowed? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>No malware, phishing, harassment, stolen work, or content that is illegal where you live. Custom code runs in a sandbox, but “it can’t read my cookies” is not a moderation policy. Pages can be reported and taken down.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>How do view counts work? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>One view per visitor session per day, counted on the server. No Google Analytics, no pixels, no cookie banner. The number is a rough signal of interest, not a growth metric.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>Why is it free? <span class="faq-item__plus"><x-icon name="plus" :size="14" /></span></summary>
                <div class="faq-item__body">
                    <p>Because the beta is small and the hosting bill is smaller. Limits keep it that way: four pages, 5 MB images, modest code size. If we add a paid plan later, there will be a free tier, plenty of notice, and no hostage situations.</p>
                </div>
            </details>
        </div>
    </div>
</section>

<section class="closing">
    <div class="wrap closing__grid">
        <div>
            <p class="eyebrow">{{ $totalPages > 0 ? number_format($totalPages).' pages and counting' : 'The tide is in' }}</p>
            <h2>Make the page you’d want to visit.</h2>
        </div>
        <div class="closing__actions">
            <a class="btn btn--primary btn--lg" href="{{ auth()->check() ? route('studio.portfolios.create') : route('register') }}">Start a portfolio <x-icon name="arrow-right" :size="17" /></a>
            <p class="closing__note">Free in beta. Your address is yours from day one. You can leave with your code.</p>
        </div>
    </div>
</section>
@endsection
