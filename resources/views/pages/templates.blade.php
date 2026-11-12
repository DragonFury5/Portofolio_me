@extends('layouts.public')

@section('title', 'Portfolio templates · Portside')
@section('description', 'Four distinct portfolio templates for designers, photographers, engineers and illustrators. Live previews, shared content, no lock-in.')
@section('canonical', route('templates.index'))
@section('body_class', 'templates-page')

@section('content')
<div class="wrap">
    <header class="page-hero">
        <p class="eyebrow">Four ways to begin</p>
        <h1>Templates that don’t look like templates.</h1>
        <p class="page-hero__lede">
            Every layout below is a real page with sample content, not a screenshot.
            They share the same fields, so switching templates keeps your work intact.
        </p>
        <div class="page-hero__meta">
            <span><x-icon name="layout" :size="14" /> Four layouts</span>
            <span><x-icon name="monitor" :size="14" /> Responsive by default</span>
            <span><x-icon name="code" :size="14" /> Or take the code route instead</span>
        </div>
    </header>

    <div class="tpl-showcase">
        @foreach ($templates as $key => $template)
            <article class="tpl-feature" id="{{ $key }}">
                <div class="tpl-feature__preview">
                    <iframe src="{{ route('templates.preview', $key) }}?embed=1"
                            title="{{ $template['name'] }} template preview"
                            loading="lazy" tabindex="-1" aria-hidden="true"
                            sandbox="allow-scripts allow-forms allow-popups"></iframe>
                </div>
                <div class="tpl-feature__copy">
                    <span class="tpl-feature__no">{{ sprintf('%02d', $loop->iteration) }} / {{ $template['kicker'] }}</span>
                    <h2>{{ $template['name'] }}</h2>
                    <p>{{ $template['long'] }}</p>
                    <div class="tpl-feature__meta">
                        <span class="tag">Best for {{ $template['best'] }}</span>
                        <span class="tag tag--accent">Accent: {{ $template['accent'] }}</span>
                    </div>
                    <div class="tpl-feature__actions">
                        <a class="btn btn--primary" href="{{ auth()->check() ? route('studio.portfolios.create', ['template' => $key]) : route('register', ['template' => $key]) }}">Use {{ $template['name'] }} <x-icon name="arrow-right" :size="16" /></a>
                        <a class="link-arrow" href="{{ route('templates.preview', $key) }}" target="_blank" rel="noopener">Full preview <x-icon name="arrow-up-right" :size="14" /></a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>

<section class="section section--dark">
    <div class="wrap editor-showcase">
        <div class="editor-showcase__copy">
            <p class="eyebrow eyebrow--light">Not a template person?</p>
            <h2>Start with a blank file instead.</h2>
            <p>
                Code mode is not a secret menu. It is the same editor, with an empty page and
                nothing between you and the browser. Start blank, or borrow one of three starter pages.
            </p>
            <ul class="feature-list">
                <li><x-icon name="code" :size="18" /><span><strong>Three starters.</strong> Blank canvas, editorial one-pager, kinetic type.</span></li>
                <li><x-icon name="download" :size="18" /><span><strong>Export any time.</strong> One HTML file, yours to host anywhere.</span></li>
                <li><x-icon name="shield" :size="18" /><span><strong>Sandboxed.</strong> Your scripts run without access to cookies or this platform.</span></li>
            </ul>
            <a class="link-arrow link-arrow--light" href="{{ route('docs.code') }}">Read the code guide <x-icon name="arrow-right" :size="15" /></a>
        </div>
        <div class="editor-mock" aria-hidden="true">
            <div class="editor-mock__bar"><span>index.html</span><span>styles.css</span><span>script.js</span><span class="editor-mock__saved">Ready</span></div>
            <div class="editor-mock__body">
                <pre><code><span class="c-comment">&lt;!-- three starters, no lock-in --&gt;</span>
<span class="c-tag">&lt;main</span> <span class="c-prop">class</span>=<span class="c-str">"page"</span><span class="c-tag">&gt;</span>
  <span class="c-tag">&lt;h1&gt;</span>Your work, <span class="c-tag">&lt;em&gt;</span>at home<span class="c-tag">&lt;/em&gt;.&lt;/h1&gt;</span>
<span class="c-tag">&lt;/main&gt;</span>

<span class="c-tag">&lt;style&gt;</span>
  <span class="c-tag">.page</span> { <span class="c-prop">max-width</span>: <span class="c-num">72ch</span>; }
<span class="c-tag">&lt;/style&gt;</span></code></pre>
                <div class="editor-mock__preview">
                    <div class="editor-mock__page">
                        <p>Blank canvas</p>
                        <h3>Your work,<br><em>at home.</em></h3>
                        <div class="editor-mock__lines"><span></span><span></span><span></span></div>
                        <div class="editor-mock__plate"></div>
                    </div>
                    <span class="editor-mock__caption">preview · live</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="closing">
    <div class="wrap closing__grid">
        <div>
            <p class="eyebrow">Start where you are</p>
            <h2>Template today, source code tomorrow.</h2>
        </div>
        <div class="closing__actions">
            <a class="btn btn--primary btn--lg" href="{{ auth()->check() ? route('studio.portfolios.create') : route('register') }}">Start a portfolio <x-icon name="arrow-right" :size="17" /></a>
            <p class="closing__note">Four pages per person, free while we’re in beta. No card.</p>
        </div>
    </div>
</section>
@endsection
