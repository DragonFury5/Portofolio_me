@extends('layouts.app')

@section('title', 'Code · '.$portfolio->title.' · Portside Studio')
@section('body_class', 'app-body app-body--flush')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/css/code-fonts.css') }}" data-code-fonts>
@endpush

@section('content')
@php
    $kb = fn (string $value): string => number_format(strlen($value) / 1000, 1).' KB';
@endphp
<form
    class="editor-page"
    id="code-form"
    method="post"
    action="{{ route('studio.portfolios.code.update', $portfolio) }}"
    data-code-form
    data-preview-frame="#code-preview"
    data-upload-url="{{ route('studio.uploads.store') }}"
>
    @csrf
    @method('PUT')

    <div class="editor-bar">
        <div class="editor-bar__left">
            <a class="editor-bar__back" href="{{ route('dashboard') }}" aria-label="Back to your pages"><x-icon name="arrow-left" :size="15" /></a>
            <div class="editor-bar__title">
                <strong>{{ $portfolio->title }}</strong>
                <span>/p/{{ $portfolio->slug }} · {{ $portfolio->is_published ? 'live' : 'draft' }}</span>
            </div>
        </div>

        <div class="editor-bar__tabs" role="tablist" aria-label="Code files">
            <button class="code-tab is-active" type="button" role="tab" id="tab-html" aria-selected="true" aria-controls="pane-html" data-code-tab="html">
                <span class="code-tab__dot" data-dirty-dot></span>index.html
            </button>
            <button class="code-tab" type="button" role="tab" id="tab-css" aria-selected="false" aria-controls="pane-css" data-code-tab="css">
                <span class="code-tab__dot" data-dirty-dot></span>styles.css
            </button>
            <button class="code-tab" type="button" role="tab" id="tab-js" aria-selected="false" aria-controls="pane-js" data-code-tab="js">
                <span class="code-tab__dot" data-dirty-dot></span>script.js
            </button>
        </div>

        <div class="editor-bar__spacer"></div>

        <div class="editor-bar__right">
            <span class="editor-status" data-code-status>All changes saved</span>

            <label class="editor-icon-btn" title="Upload an image and insert its URL" aria-label="Upload an image">
                <x-icon name="upload" :size="15" />
                <input type="file" accept="image/jpeg,image/png,image/webp" data-code-upload hidden>
            </label>

            <a class="editor-icon-btn" href="{{ route('studio.portfolios.export', $portfolio) }}" title="Export as one HTML file" aria-label="Export page">
                <x-icon name="download" :size="15" />
            </a>
            <a class="editor-icon-btn" href="{{ route('docs.code') }}" target="_blank" rel="noopener" title="Open the code guide" aria-label="Open the code guide">
                <x-icon name="file-code" :size="15" />
            </a>

            <button class="editor-btn editor-btn--save" type="submit">Save</button>

            @if ($portfolio->is_published)
                <a class="editor-btn editor-btn--publish" href="{{ $portfolio->publicUrl() }}" target="_blank" rel="noopener">View live <x-icon name="external" :size="13" /></a>
            @else
                <button class="editor-btn editor-btn--publish" type="submit" form="publish-form">Publish</button>
            @endif

            <a class="editor-icon-btn" href="{{ route('studio.portfolios.settings.edit', $portfolio) }}" title="Page settings" aria-label="Page settings">
                <x-icon name="settings" :size="15" />
            </a>
        </div>
    </div>

    <div class="editor-workspace">
        <div class="code-pane" data-code-pane="html" role="tabpanel" id="pane-html" aria-labelledby="tab-html">
            <div class="code-pane__head">
                <span><x-icon name="code" :size="12" /> index.html</span>
                <span class="code-pane__hint">HTML · Tab inserts two spaces</span>
            </div>
            <div class="code-editor-shell">
                <div class="code-editor-scroll">
                    <div class="line-numbers" data-line-numbers aria-hidden="true">1</div>
                    <div class="code-edit-wrap">
                        <textarea class="code-input" id="code-html" name="html" form="code-form" spellcheck="false" autocomplete="off" autocapitalize="off" wrap="off" aria-label="HTML source" data-code-editor="html">{{ $portfolio->html }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="code-pane" data-code-pane="css" role="tabpanel" id="pane-css" aria-labelledby="tab-css" hidden>
            <div class="code-pane__head">
                <span><x-icon name="code" :size="12" /> styles.css</span>
                <span class="code-pane__hint">CSS · no build step</span>
            </div>
            <div class="code-editor-shell">
                <div class="code-editor-scroll">
                    <div class="line-numbers" data-line-numbers aria-hidden="true">1</div>
                    <div class="code-edit-wrap">
                        <textarea class="code-input" id="code-css" name="css" form="code-form" spellcheck="false" autocomplete="off" autocapitalize="off" wrap="off" aria-label="CSS source" data-code-editor="css">{{ $portfolio->css }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="code-pane" data-code-pane="js" role="tabpanel" id="pane-js" aria-labelledby="tab-js" hidden>
            <div class="code-pane__head">
                <span><x-icon name="code" :size="12" /> script.js</span>
                <span class="code-pane__hint">JavaScript · sandboxed</span>
            </div>
            <div class="code-editor-shell">
                <div class="code-editor-scroll">
                    <div class="line-numbers" data-line-numbers aria-hidden="true">1</div>
                    <div class="code-edit-wrap">
                        <textarea class="code-input" id="code-js" name="js" form="code-form" spellcheck="false" autocomplete="off" autocapitalize="off" wrap="off" aria-label="JavaScript source" data-code-editor="js">{{ $portfolio->js }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="editor-preview">
            <div class="preview-toolbar">
                <span>preview</span>
                <span class="preview-toolbar__url">{{ $portfolio->slug }} · sandboxed iframe</span>
                <div class="device-toggle" aria-label="Preview width">
                    <button type="button" class="is-active" data-device="desktop" aria-label="Desktop preview" aria-pressed="true"><x-icon name="monitor" :size="13" /></button>
                    <button type="button" data-device="mobile" aria-label="Phone preview" aria-pressed="false"><x-icon name="smartphone" :size="13" /></button>
                </div>
            </div>
            <div class="preview-stage" data-preview-stage>
                <iframe
                    id="code-preview"
                    title="Live code preview"
                    sandbox="allow-scripts allow-forms allow-popups allow-modals allow-downloads"
                ></iframe>
            </div>
        </div>
    </div>

    <div class="editor-statusbar">
        <span>{{ $portfolio->is_published ? 'Live' : 'Draft' }} · autosaves while you type</span>
        <span>HTML <strong data-code-count="html">{{ $kb((string) $portfolio->html) }}</strong> · CSS <strong data-code-count="css">{{ $kb((string) $portfolio->css) }}</strong> · JS <strong data-code-count="js">{{ $kb((string) $portfolio->js) }}</strong></span>
        <span>Limits: {{ round($limits['html'] / 1000) }} / {{ round($limits['css'] / 1000) }} / {{ round($limits['js'] / 1000) }} KB</span>
        <span class="editor-statusbar__note"><x-icon name="lock" :size="12" /> Sandbox on · Ctrl+S to save</span>
    </div>
</form>

<form id="publish-form" method="post" action="{{ route('studio.portfolios.publish', $portfolio) }}" hidden>
    @csrf
    <input type="hidden" name="published" value="{{ $portfolio->is_published ? '0' : '1' }}">
</form>
@endsection
