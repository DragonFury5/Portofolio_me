@extends('layouts.app')

@section('title', 'Start a page · Portside Studio')
@section('body_class', 'app-body')

@section('content')
<header class="picker-head">
    <p class="eyebrow">New page · {{ $pageCount }} of {{ $maxPages }} used</p>
    <h1>Where do you want<br>to begin?</h1>
    <p>
        You can switch templates later without retyping anything. Code pages stay code pages,
        but you can export the file and take it anywhere. No decision here is permanent.
    </p>
</header>

@if ($errors->any())
    <div class="flash flash--error" role="alert" style="margin-top: 20px;">
        <span class="flash__icon"><x-icon name="x" :size="16" /></span>
        <div>
            <p><strong>That starting point did not stick.</strong></p>
            <p class="form-note">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<section class="picker-section" aria-labelledby="template-picker-title">
    <div class="picker-section__head">
        <div>
            <h2 id="template-picker-title">Start with a template</h2>
            <p>Find the shape first. Your words and projects come next.</p>
        </div>
        <span class="tag">No code needed</span>
    </div>

    <form method="post" action="{{ route('studio.portfolios.store') }}">
        @csrf
        <input type="hidden" name="mode" value="template">
        <div class="choice-grid">
            @foreach ($templates as $key => $template)
                <label class="choice">
                    <input type="radio" name="template" value="{{ $key }}" @checked(old('template', $selectedTemplate) === $key)>
                    <div class="choice__body">
                        <div class="choice__preview">
                            <x-template-mini :template="$key" />
                        </div>
                        <div class="choice__text">
                            <div>
                                <h3>{{ $template['name'] }}</h3>
                                <p>{{ $template['description'] }}</p>
                            </div>
                            <span class="choice__check"><x-icon name="check" :size="13" /></span>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
        <div class="picker-foot">
            <p>A new page starts as a draft. Nothing is public until you say so.</p>
            <button class="btn btn--primary" type="submit">Use the selected template <x-icon name="arrow-right" :size="16" /></button>
        </div>
    </form>
</section>

<section class="picker-section" aria-labelledby="code-picker-title">
    <div class="picker-section__head">
        <div>
            <h2 id="code-picker-title">Or start in code</h2>
            <p>Three editable starter files. Bring your own animation, or none at all.</p>
        </div>
        <span class="tag tag--accent">100% freedom</span>
    </div>

    <form method="post" action="{{ route('studio.portfolios.store') }}">
        @csrf
        <input type="hidden" name="mode" value="code">
        <div class="choice-grid choice-grid--three">
            @foreach ($starters as $key => $starter)
                <label class="choice choice--code">
                    <input type="radio" name="starter" value="{{ $key }}" @checked(old('starter', $selectedStarter) === $key)>
                    <div class="choice__body">
                        <div class="choice__preview">
                            @if ($key === 'blank')
                                <span>&lt;main&gt;
  Your work.
&lt;/main&gt;

/* yours */</span>
                            @elseif ($key === 'editorial')
                                <span>display: grid;
font-size: clamp();

/* reveal on scroll */</span>
                            @else
                                <span>requestAnimationFrame()
pointermove → orb

/* it moves */</span>
                            @endif
                        </div>
                        <div class="choice__text">
                            <div>
                                <h3>{{ $starter['name'] }}</h3>
                                <p>{{ $starter['tagline'] }}</p>
                            </div>
                            <span class="choice__check"><x-icon name="check" :size="13" /></span>
                        </div>
                        <span class="tag" style="align-self: flex-start;">{{ $starter['level'] }}</span>
                    </div>
                </label>
            @endforeach
        </div>
        <div class="picker-foot">
            <p>Sandboxed, but real. Read <a href="{{ route('docs.code') }}" style="color: var(--brand-secondary); text-decoration: underline;">the code guide</a> before you start.</p>
            <button class="btn btn--primary" type="submit">Open a code editor <x-icon name="arrow-right" :size="16" /></button>
        </div>
    </form>
</section>

<div class="form-actions">
    <a class="btn btn--ghost" href="{{ route('dashboard') }}"><x-icon name="arrow-left" :size="15" /> Back to your pages</a>
    <span class="form-note">{{ $maxPages - $pageCount }} {{ $maxPages - $pageCount === 1 ? 'page' : 'pages' }} left. Delete one to free a slot.</span>
</div>
@endsection
