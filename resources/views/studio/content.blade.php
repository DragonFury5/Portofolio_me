@extends('layouts.app')

@section('title', 'Edit '.$portfolio->title.' · Portside Studio')
@section('body_class', 'app-body')

@section('content')
@php
    $links = old('links', $content['links']);
    $links = is_array($links) ? $links : [];
    $projects = old('projects', $content['projects']);
    $projects = is_array($projects) ? $projects : [];
@endphp
<header class="editor-head">
    <div class="editor-head__left">
        <a class="editor-head__back" href="{{ route('dashboard') }}" aria-label="Back to your pages"><x-icon name="arrow-left" :size="16" /></a>
        <div>
            <h1>{{ $portfolio->title }}</h1>
            <div class="editor-head__sub">
                <span class="status {{ $portfolio->is_published ? 'status--live' : 'status--draft' }}">{{ $portfolio->is_published ? 'Live' : 'Draft' }}</span>
                <span>{{ $template['name'] }} template</span>
                <span>Edited {{ time_ago($portfolio->updated_at) }}</span>
            </div>
        </div>
    </div>

    <nav class="editor-tabs" aria-label="Page editor">
        <a href="{{ route('studio.portfolios.content.edit', $portfolio) }}" aria-current="page">Content</a>
        <a href="{{ route('studio.portfolios.settings.edit', $portfolio) }}">Settings</a>
    </nav>

    <div class="editor-head__actions">
        <span class="autosave" data-autosave>All changes saved</span>
        <a class="btn btn--outline btn--sm" href="{{ route('studio.portfolios.preview', $portfolio) }}" target="_blank" rel="noopener">Preview <x-icon name="external" :size="14" /></a>
        <button class="btn btn--primary btn--sm" type="submit" form="content-form">Save changes</button>
    </div>
</header>

@if ($errors->any())
    <div class="flash flash--error" role="alert" style="margin-bottom: 20px;">
        <span class="flash__icon"><x-icon name="x" :size="16" /></span>
        <div>
            <p><strong>Some fields need attention.</strong></p>
            <ul class="form-note">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="content-layout">
    <form
        class="content-form"
        id="content-form"
        method="post"
        action="{{ route('studio.portfolios.content.update', $portfolio) }}"
        data-autosave-form
        data-preview-frame="#content-preview"
        data-upload-url="{{ route('studio.uploads.store') }}"
    >
        @csrf
        @method('PUT')

        <section class="form-card">
            <div class="form-card__head">
                <h2>The opening</h2>
                <span>Above the fold</span>
            </div>

            <div class="field">
                <label for="eyebrow">Eyebrow</label>
                <input class="input" id="eyebrow" name="eyebrow" type="text" maxlength="120" value="{{ old('eyebrow', $content['eyebrow']) }}" placeholder="Graphic designer · Rotterdam">
                <p class="field__help">Your role and city, or a short line that sets the scene.</p>
            </div>

            <div class="field">
                <label for="headline">Headline</label>
                <input class="input" id="headline" name="headline" type="text" maxlength="220" required value="{{ old('headline', $content['headline']) }}">
                <p class="field__help">Wrap words in *asterisks* to make them italic.</p>
            </div>

            <div class="field">
                <label for="intro">Short introduction</label>
                <textarea class="textarea" id="intro" name="intro" maxlength="500">{{ old('intro', $content['intro']) }}</textarea>
            </div>

            <div class="field">
                <label for="about">About the practice</label>
                <textarea class="textarea" id="about" name="about" maxlength="1800" style="min-height: 140px;">{{ old('about', $content['about']) }}</textarea>
            </div>

            <div class="field-row field-row--3">
                <div class="field">
                    <label for="location">Location</label>
                    <input class="input" id="location" name="location" type="text" maxlength="100" value="{{ old('location', $content['location']) }}">
                </div>
                <div class="field">
                    <label for="email">Contact email</label>
                    <input class="input" id="email" name="email" type="email" maxlength="190" value="{{ old('email', $content['email']) }}">
                </div>
                <div class="field">
                    <label for="availability">Availability</label>
                    <input class="input" id="availability" name="availability" type="text" maxlength="120" value="{{ old('availability', $content['availability']) }}">
                </div>
            </div>
        </section>

        <section class="form-card">
            <div class="form-card__head">
                <h2>Elsewhere</h2>
                <span>Optional links</span>
            </div>

            <div class="repeat-list" data-link-list>
                @foreach ($links as $i => $link)
                    <div class="repeat-item" data-link-item>
                        <div class="repeat-item__head">
                            <span class="repeat-item__handle" data-item-number>{{ sprintf('%02d', $i + 1) }}</span>
                            <span class="repeat-item__title" data-repeat-title>{{ filled($link['label'] ?? null) ? $link['label'] : 'Untitled link' }}</span>
                            <div class="repeat-item__tools">
                                <button class="mini-btn" type="button" data-move="up" aria-label="Move link up"><x-icon name="arrow-up" :size="14" /></button>
                                <button class="mini-btn" type="button" data-move="down" aria-label="Move link down"><x-icon name="arrow-down" :size="14" /></button>
                                <button class="mini-btn mini-btn--danger" type="button" data-remove-item aria-label="Remove link"><x-icon name="trash" :size="14" /></button>
                            </div>
                        </div>
                        <div class="repeat-item__body">
                            <div class="field-row">
                                <div class="field">
                                    <label>Label</label>
                                    <input class="input" type="text" name="links[{{ $i }}][label]" maxlength="40" value="{{ $link['label'] ?? '' }}" data-repeat-title-input>
                                </div>
                                <div class="field">
                                    <label>URL</label>
                                    <input class="input" type="text" name="links[{{ $i }}][url]" maxlength="500" value="{{ $link['url'] ?? '' }}" placeholder="https://">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-actions" style="margin-top: 14px;">
                <button class="btn btn--outline btn--sm" type="button" data-add-link><x-icon name="plus" :size="14" /> Add a link</button>
                <span class="form-note">Instagram, GitHub, a shop — anything with a URL.</span>
            </div>
        </section>

        <section class="form-card">
            <div class="form-card__head">
                <h2>Selected work</h2>
                <span><span data-project-count>{{ count($projects) }}</span> of 12</span>
            </div>

            <div class="repeat-list" data-project-list>
                @foreach ($projects as $i => $project)
                    <div class="repeat-item" data-project-item>
                        <div class="repeat-item__head">
                            <span class="repeat-item__handle" data-item-number>{{ sprintf('%02d', $i + 1) }}</span>
                            <span class="repeat-item__title" data-repeat-title>{{ filled($project['title'] ?? null) ? $project['title'] : 'Untitled project' }}</span>
                            <div class="repeat-item__tools">
                                <button class="mini-btn" type="button" data-move="up" aria-label="Move project up"><x-icon name="arrow-up" :size="14" /></button>
                                <button class="mini-btn" type="button" data-move="down" aria-label="Move project down"><x-icon name="arrow-down" :size="14" /></button>
                                <button class="mini-btn mini-btn--danger" type="button" data-remove-item aria-label="Remove project"><x-icon name="trash" :size="14" /></button>
                            </div>
                        </div>
                        <div class="repeat-item__body">
                            <div class="field-row">
                                <div class="field">
                                    <label>Project title</label>
                                    <input class="input" type="text" name="projects[{{ $i }}][title]" maxlength="100" value="{{ $project['title'] ?? '' }}" data-repeat-title-input>
                                </div>
                                <div class="field">
                                    <label>Year</label>
                                    <input class="input" type="text" name="projects[{{ $i }}][year]" maxlength="20" value="{{ $project['year'] ?? '' }}">
                                </div>
                            </div>
                            <div class="field">
                                <label>Role</label>
                                <input class="input" type="text" name="projects[{{ $i }}][role]" maxlength="80" value="{{ $project['role'] ?? '' }}" placeholder="Identity, print">
                            </div>
                            <div class="field">
                                <label>Short description</label>
                                <textarea class="textarea" name="projects[{{ $i }}][summary]" maxlength="500">{{ $project['summary'] ?? '' }}</textarea>
                            </div>
                            <div class="project-image" data-image-field>
                                <div class="project-image__thumb" data-image-thumb>
                                    @if (filled($project['image'] ?? null))
                                        <img src="{{ media_url($project['image']) }}" alt="">
                                    @else
                                        <span><x-icon name="image" :size="18" /></span>
                                    @endif
                                </div>
                                <input type="hidden" name="projects[{{ $i }}][image]" value="{{ $project['image'] ?? '' }}" data-image-input>
                                <div class="project-image__actions">
                                    <label class="upload-btn">
                                        <x-icon name="upload" :size="13" /> Upload image
                                        <input type="file" accept="image/jpeg,image/png,image/webp" data-image-upload>
                                    </label>
                                    <button class="upload-btn" type="button" data-image-clear>Remove</button>
                                    <span class="upload-note" data-upload-note>JPG, PNG or WebP · 5 MB</span>
                                </div>
                            </div>
                            <div class="field">
                                <label>Project URL <span class="form-note">(optional)</span></label>
                                <input class="input" type="text" name="projects[{{ $i }}][url]" maxlength="500" value="{{ $project['url'] ?? '' }}" placeholder="https://">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-actions" style="margin-top: 14px;">
                <button class="btn btn--outline btn--sm" type="button" data-add-project><x-icon name="plus" :size="14" /> Add a project</button>
                <span class="form-note">Twelve projects is the ceiling. Keep the best ones.</span>
            </div>
        </section>

        <div class="form-actions">
            <button class="btn btn--outline" type="submit">Save changes</button>
            <a class="btn btn--ghost" href="{{ route('dashboard') }}">Cancel</a>
            <span class="form-note">Saves automatically while you type. Publishing is a separate, deliberate click.</span>
        </div>
    </form>

    <aside class="content-preview">
        <div class="preview-chrome">
            <span class="preview-chrome__dots"><i></i><i></i><i></i></span>
            <span class="preview-chrome__url">{{ $portfolio->slug }}</span>
            <div class="device-toggle" aria-label="Preview size">
                <button type="button" class="is-active" data-preview-device="desktop" aria-label="Desktop preview" aria-pressed="true"><x-icon name="monitor" :size="13" /></button>
                <button type="button" data-preview-device="mobile" aria-label="Phone preview" aria-pressed="false"><x-icon name="smartphone" :size="13" /></button>
            </div>
            <span class="preview-chrome__label">Preview</span>
        </div>
        <div class="preview-frame" data-preview-stage>
            <iframe
                id="content-preview"
                src="{{ route('studio.portfolios.preview', $portfolio) }}"
                title="Live preview of {{ $portfolio->title }}"
                loading="eager"
            ></iframe>
        </div>
        <div class="preview-help">
            <span>Preview shows saved changes. <a href="{{ route('studio.portfolios.settings.edit', $portfolio) }}" style="color: var(--brand-secondary);">Change template</a></span>
            <a class="link-arrow" href="{{ route('studio.portfolios.preview', $portfolio) }}" target="_blank" rel="noopener">Open full page <x-icon name="arrow-up-right" :size="13" /></a>
        </div>
    </aside>
</div>

<template id="link-template">
    <div class="repeat-item" data-link-item>
        <div class="repeat-item__head">
            <span class="repeat-item__handle" data-item-number>__NUMBER__</span>
            <span class="repeat-item__title" data-repeat-title>Untitled link</span>
            <div class="repeat-item__tools">
                <button class="mini-btn" type="button" data-move="up" aria-label="Move link up"><x-icon name="arrow-up" :size="14" /></button>
                <button class="mini-btn" type="button" data-move="down" aria-label="Move link down"><x-icon name="arrow-down" :size="14" /></button>
                <button class="mini-btn mini-btn--danger" type="button" data-remove-item aria-label="Remove link"><x-icon name="trash" :size="14" /></button>
            </div>
        </div>
        <div class="repeat-item__body">
            <div class="field-row">
                <div class="field">
                    <label>Label</label>
                    <input class="input" type="text" name="links[__INDEX__][label]" maxlength="40" data-repeat-title-input>
                </div>
                <div class="field">
                    <label>URL</label>
                    <input class="input" type="text" name="links[__INDEX__][url]" maxlength="500" placeholder="https://">
                </div>
            </div>
        </div>
    </div>
</template>

<template id="project-template">
    <div class="repeat-item" data-project-item>
        <div class="repeat-item__head">
            <span class="repeat-item__handle" data-item-number>__NUMBER__</span>
            <span class="repeat-item__title" data-repeat-title>Untitled project</span>
            <div class="repeat-item__tools">
                <button class="mini-btn" type="button" data-move="up" aria-label="Move project up"><x-icon name="arrow-up" :size="14" /></button>
                <button class="mini-btn" type="button" data-move="down" aria-label="Move project down"><x-icon name="arrow-down" :size="14" /></button>
                <button class="mini-btn mini-btn--danger" type="button" data-remove-item aria-label="Remove project"><x-icon name="trash" :size="14" /></button>
            </div>
        </div>
        <div class="repeat-item__body">
            <div class="field-row">
                <div class="field">
                    <label>Project title</label>
                    <input class="input" type="text" name="projects[__INDEX__][title]" maxlength="100" data-repeat-title-input>
                </div>
                <div class="field">
                    <label>Year</label>
                    <input class="input" type="text" name="projects[__INDEX__][year]" maxlength="20">
                </div>
            </div>
            <div class="field">
                <label>Role</label>
                <input class="input" type="text" name="projects[__INDEX__][role]" maxlength="80" placeholder="Identity, print">
            </div>
            <div class="field">
                <label>Short description</label>
                <textarea class="textarea" name="projects[__INDEX__][summary]" maxlength="500"></textarea>
            </div>
            <div class="project-image" data-image-field>
                <div class="project-image__thumb" data-image-thumb>
                    <span><x-icon name="image" :size="18" /></span>
                </div>
                <input type="hidden" name="projects[__INDEX__][image]" value="" data-image-input>
                <div class="project-image__actions">
                    <label class="upload-btn">
                        <x-icon name="upload" :size="13" /> Upload image
                        <input type="file" accept="image/jpeg,image/png,image/webp" data-image-upload>
                    </label>
                    <button class="upload-btn" type="button" data-image-clear>Remove</button>
                    <span class="upload-note" data-upload-note>JPG, PNG or WebP · 5 MB</span>
                </div>
            </div>
            <div class="field">
                <label>Project URL <span class="form-note">(optional)</span></label>
                <input class="input" type="text" name="projects[__INDEX__][url]" maxlength="500" placeholder="https://">
            </div>
        </div>
    </div>
</template>
@endsection
