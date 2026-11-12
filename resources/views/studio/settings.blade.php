@extends('layouts.app')

@section('title', 'Page settings · '.$portfolio->title.' · Portside Studio')
@section('body_class', 'app-body')

@section('content')
<header class="editor-head">
    <div class="editor-head__left">
        <a class="editor-head__back" href="{{ route('dashboard') }}" aria-label="Back to your pages"><x-icon name="arrow-left" :size="16" /></a>
        <div>
            <h1>{{ $portfolio->title }}</h1>
            <div class="editor-head__sub">
                <span class="status {{ $portfolio->is_published ? 'status--live' : 'status--draft' }}">{{ $portfolio->is_published ? 'Live' : 'Draft' }}</span>
                <span>Page settings</span>
                <span>{{ $pageCount }} of {{ $maxPages }} pages used</span>
            </div>
        </div>
    </div>

    <nav class="editor-tabs" aria-label="Page editor">
        @if ($portfolio->isTemplate())
            <a href="{{ route('studio.portfolios.content.edit', $portfolio) }}">Content</a>
        @else
            <a href="{{ route('studio.portfolios.code.edit', $portfolio) }}">Code</a>
        @endif
        <a href="{{ route('studio.portfolios.settings.edit', $portfolio) }}" aria-current="page">Settings</a>
    </nav>

    <div class="editor-head__actions">
        <a class="btn btn--outline btn--sm" href="{{ route('studio.portfolios.preview', $portfolio) }}" target="_blank" rel="noopener">Preview <x-icon name="external" :size="14" /></a>
        <button class="btn btn--primary btn--sm" type="submit" form="settings-form">Save settings</button>
    </div>
</header>

@if ($errors->any())
    <div class="flash flash--error" role="alert" style="margin-bottom: 20px;">
        <span class="flash__icon"><x-icon name="x" :size="16" /></span>
        <div>
            <p><strong>Couldn’t save those settings.</strong></p>
            <ul class="form-note">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="settings-grid">
    <div class="settings-form">
        <form class="form-card" id="settings-form" method="post" action="{{ route('studio.portfolios.settings.update', $portfolio) }}">
            @csrf
            @method('PUT')

            <div class="form-card__head">
                <h2>The address</h2>
                <span>Public byline and metadata</span>
            </div>

            <div class="field">
                <label for="title">Page title</label>
                <input class="input" id="title" name="title" type="text" maxlength="80" required value="{{ old('title', $portfolio->title) }}">
                <p class="field__help">Used in the browser tab and on search results.</p>
            </div>

            <div class="field">
                <label for="slug">Page address</label>
                <div class="input-group">
                    <span class="input-group__prefix">/p/</span>
                    <input class="input" id="slug" name="slug" type="text" maxlength="40" required value="{{ old('slug', $portfolio->slug) }}" pattern="[a-z0-9][a-z0-9-]{1,38}[a-z0-9]">
                </div>
                <p class="field__help">Lowercase letters, numbers and dashes. Changing this breaks old links — on purpose, if you need it.</p>
            </div>

            <div class="slug-preview">
                <x-icon name="globe" :size="14" />
                <span id="live-url">{{ $portfolio->publicUrl() }}</span>
                <button type="button" class="mini-btn" data-copy-target="#live-url" aria-label="Copy page URL" style="margin-left: auto;"><x-icon name="copy" :size="14" /></button>
            </div>

            <div class="field" style="margin-top: 16px;">
                <label for="description">Meta description</label>
                <textarea class="textarea" id="description" name="description" maxlength="200" style="min-height: 84px;">{{ old('description', $portfolio->description) }}</textarea>
                <p class="field__help">One clear sentence for search engines and link previews. Up to 200 characters.</p>
            </div>

            @if ($portfolio->isTemplate())
                <div class="form-card__head" style="margin-top: 24px;">
                    <h2>The template</h2>
                    <span>Content follows you</span>
                </div>

                <div class="template-picker">
                    @foreach ($templates as $key => $item)
                        <label class="template-pick">
                            <input type="radio" name="template" value="{{ $key }}" @checked(old('template', $portfolio->template) === $key)>
                            <div class="template-pick__body">
                                <div class="template-pick__mini"><x-template-mini :template="$key" /></div>
                                <div class="template-pick__label">{{ $item['name'] }} <span>{{ $item['kicker'] }}</span></div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="field" style="margin-top: 18px;">
                    <span class="field__label">Accent colour</span>
                    <div class="accent-picker">
                        @foreach ($accents as $key => $label)
                            <label class="accent-pick" title="{{ $label }}">
                                <input type="radio" name="accent" value="{{ $key }}" @checked(old('accent', $portfolio->accent) === $key) aria-label="{{ $label }}">
                                <span class="accent-pick__swatch accent-pick__swatch--{{ $key }}"></span>
                            </label>
                        @endforeach
                    </div>
                    <p class="field__help">The accent is one small signal across the page, not a repaint.</p>
                </div>
            @else
                <input type="hidden" name="accent" value="{{ $portfolio->accent }}">
                <div class="notice" style="margin-top: 20px;">
                    <x-icon name="code" :size="18" />
                    <div><strong>This is a code page.</strong> Its design lives in your HTML, CSS and JS. <a href="{{ route('studio.portfolios.code.edit', $portfolio) }}" style="color: var(--brand-secondary); text-decoration: underline;">Open the editor</a> to change the look.</div>
                </div>
            @endif

            <div class="form-actions">
                <button class="btn btn--outline" type="submit">Save settings</button>
                <a class="btn btn--ghost" href="{{ route('dashboard') }}">Back to your pages</a>
            </div>
        </form>

        <div class="danger-zone">
            <h2>Delete this page</h2>
            <p>This removes the page, its content, uploads references and view history. It cannot be undone. An exported code file is unaffected.</p>
            <button class="btn btn--danger btn--sm" type="button" data-dialog-open="delete-dialog">Delete page</button>
        </div>
    </div>

    <aside class="settings-aside">
        <div class="side-card">
            <p class="side-card__label">Status</p>
            <h3>{{ $portfolio->is_published ? 'Live in the harbour.' : 'Still in the studio.' }}</h3>
            <p>
                {{ $portfolio->is_published
                    ? 'Anyone with the link can see this page, and it appears in Explore when it has work on it.'
                    : 'Only you can preview this page. Publish when the words and work are ready.' }}
            </p>
            <div class="side-card__links">
                @if ($portfolio->is_published)
                    <a href="{{ $portfolio->publicUrl() }}" target="_blank" rel="noopener"><x-icon name="external" :size="15" /> Open live page</a>
                @else
                    <a href="{{ route('studio.portfolios.preview', $portfolio) }}" target="_blank" rel="noopener"><x-icon name="eye" :size="15" /> Preview draft</a>
                @endif
                <a href="{{ $portfolio->editUrl() }}"><x-icon name="pencil" :size="15" /> Edit {{ $portfolio->isCode() ? 'code' : 'content' }}</a>
            </div>
            <form method="post" action="{{ route('studio.portfolios.publish', $portfolio) }}" style="margin-top: 14px;">
                @csrf
                <input type="hidden" name="published" value="{{ $portfolio->is_published ? '0' : '1' }}">
                <button class="btn btn--sm {{ $portfolio->is_published ? 'btn--outline' : 'btn--outline' }} btn--block" type="submit">
                    {{ $portfolio->is_published ? 'Unpublish page' : 'Publish page' }}
                </button>
            </form>
        </div>

        <div class="side-card">
            <p class="side-card__label">At a glance</p>
            <h3>{{ $portfolio->isCode() ? 'Hand-coded' : $template['name'] }}</h3>
            <p>
                {{ $portfolio->isCode()
                    ? 'HTML, CSS and JS in a sandboxed page. Export it whenever you like.'
                    : $template['best'] }}
            </p>
            <div class="side-card__links">
                <span style="font-size: 11px; color: var(--ink-faint);">Created {{ format_date($portfolio->created_at) }}</span>
                <span style="font-size: 11px; color: var(--ink-faint);">Updated {{ time_ago($portfolio->updated_at) }}</span>
                <span style="font-size: 11px; color: var(--ink-faint);">{{ human_number($portfolio->views) }} total views</span>
            </div>
        </div>

        <div class="side-card side-card--dark">
            <p class="side-card__label">Need a hand?</p>
            <h3>Small pages, fewer bugs.</h3>
            <p>The code guide covers the sandbox, limits and export. The FAQ covers the rest.</p>
            <div class="side-card__links">
                <a href="{{ route('docs.code') }}"><x-icon name="file-code" :size="15" /> Code guide</a>
                <a href="{{ route('home') }}#faq"><x-icon name="arrow-right" :size="15" /> Read the FAQ</a>
            </div>
        </div>
    </aside>
</div>

<dialog class="starter-dialog" id="delete-dialog" aria-labelledby="delete-title">
    <form method="post" action="{{ route('studio.portfolios.destroy', $portfolio) }}">
        @csrf
        @method('DELETE')
        <div class="starter-dialog__head">
            <div>
                <h2 id="delete-title">Delete this page?</h2>
                <p>This cannot be undone. The URL will stop resolving immediately.</p>
            </div>
            <button class="mini-btn" type="button" data-dialog-close aria-label="Close dialog"><x-icon name="x" :size="16" /></button>
        </div>
        <div class="starter-dialog__body">
            <div class="field">
                <label for="confirm">Type <code>{{ $portfolio->slug }}</code> to confirm</label>
                <input class="input" id="confirm" name="confirm" type="text" autocomplete="off" required>
            </div>
        </div>
        <div class="starter-dialog__foot">
            <button class="btn btn--ghost" type="button" data-dialog-close>Cancel</button>
            <button class="btn btn--danger" type="submit">Delete page</button>
        </div>
    </form>
</dialog>
@endsection
