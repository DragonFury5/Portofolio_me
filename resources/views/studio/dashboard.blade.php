@extends('layouts.app')

@section('title', 'Your pages · Portside Studio')
@section('body_class', 'app-body')

@section('content')
@php
    $values = array_values($series);
    $max = max(1, ...$values);
    $width = 240;
    $height = 36;
    $count = count($values);
    $coords = [];
    foreach ($values as $i => $value) {
        $x = $count > 1 ? $i * ($width / ($count - 1)) : 0;
        $y = $height - 3 - (($value / $max) * ($height - 8));
        $coords[] = round($x, 1).','.round($y, 1);
    }
    $line = implode(' ', $coords);
    $area = '0,'.$height.' '.$line.' '.$width.','.$height;
    $profileReady = filled($user->bio) && filled($user->avatar);
@endphp
<header class="studio-head">
    <div>
        <p class="eyebrow">Portside Studio · &#64;{{ $user->username }}</p>
        <h1>Your work,<br>in progress.</h1>
        <p>Pages you own, views without the vanity metrics, and a door back to the editor.</p>
        <div class="studio-head__meta">
            <span>{{ $pages->count() }} of {{ $maxPages }} pages used</span>
            <a href="{{ route('makers.show', $user->username) }}">View public profile</a>
            <a href="{{ route('settings.edit') }}">Account settings</a>
        </div>
    </div>
    @if ($pages->isNotEmpty())
        <a class="btn btn--primary" href="{{ route('studio.portfolios.create') }}"><x-icon name="plus" :size="16" /> New page</a>
    @endif
</header>

<div class="stats-strip">
    <div class="stat">
        <span class="stat__value">{{ human_number($views14) }}</span>
        <span class="stat__label">Views · last 14 days</span>
        <svg class="spark" viewBox="0 0 240 36" preserveAspectRatio="none" aria-hidden="true">
            <polygon points="{{ $area }}" fill="rgba(96,193,229,0.18)" />
            <polyline points="{{ $line }}" fill="none" stroke="#005A9C" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="stat">
        <span class="stat__value">{{ $publishedCount }}</span>
        <span class="stat__label">Pages live</span>
    </div>
    <div class="stat">
        <span class="stat__value">{{ $draftCount }}</span>
        <span class="stat__label">Drafts in the studio</span>
    </div>
</div>

<div class="studio-grid">
    <div>
        @if ($pages->isNotEmpty())
            <div class="section-label">
                <h2>Your pages</h2>
                <span>Most recently edited first</span>
            </div>

            @foreach ($pages as $page)
                @php($thumb = $page->coverUrl())
                <article class="site-row">
                    <a class="site-row__thumb {{ $page->isCode() ? 'site-row__thumb--code' : '' }}"
                       href="{{ $page->isCode() ? route('studio.portfolios.code.edit', $page) : route('studio.portfolios.content.edit', $page) }}"
                       aria-label="Edit {{ $page->title }}">
                        @if ($page->isTemplate())
                            @if ($thumb)
                                <img src="{{ $thumb }}" alt="" loading="lazy">
                            @else
                                <span class="site-row__blank">{{ initials($user->name) }}</span>
                            @endif
                        @endif
                    </a>
                    <div>
                        <h3 class="site-row__title">
                            <a href="{{ $page->isCode() ? route('studio.portfolios.code.edit', $page) : route('studio.portfolios.content.edit', $page) }}">{{ $page->title }}</a>
                        </h3>
                        <a class="site-row__link" href="{{ $page->publicUrl() }}" target="_blank" rel="noopener">
                            /p/{{ $page->slug }} <x-icon name="arrow-up-right" :size="11" />
                        </a>
                        <div class="site-row__meta">
                            <span class="status {{ $page->is_published ? 'status--live' : 'status--draft' }}">{{ $page->is_published ? 'Live' : 'Draft' }}</span>
                            <span>{{ $page->isCode() ? 'Hand-coded' : $page->templateMeta()['name'] }}</span>
                            <span>Edited {{ time_ago($page->updated_at) }}</span>
                        </div>
                    </div>
                    <div class="site-row__views">
                        <strong>{{ human_number((int) ($page->views_14d ?? 0)) }}</strong>
                        <span>Views · 14d</span>
                    </div>
                    <div class="site-row__actions">
                        @if (!$page->is_published)
                            <a class="btn btn--icon" href="{{ route('studio.portfolios.preview', $page) }}" target="_blank" rel="noopener" title="Preview draft" aria-label="Preview draft of {{ $page->title }}">
                                <x-icon name="eye" :size="15" />
                            </a>
                        @else
                            <a class="btn btn--icon" href="{{ $page->publicUrl() }}" target="_blank" rel="noopener" title="Open live page" aria-label="Open live page {{ $page->title }}">
                                <x-icon name="external" :size="15" />
                            </a>
                        @endif
                        <a class="btn btn--icon" href="{{ $page->editUrl() }}" title="Edit page" aria-label="Edit {{ $page->title }}">
                            <x-icon name="pencil" :size="15" />
                        </a>
                        <a class="btn btn--icon" href="{{ route('studio.portfolios.settings.edit', $page) }}" title="Page settings" aria-label="Settings for {{ $page->title }}">
                            <x-icon name="settings" :size="15" />
                        </a>
                    </div>
                </article>
            @endforeach

            @if ($pages->count() < $maxPages)
                <div class="form-actions">
                    <a class="btn btn--outline" href="{{ route('studio.portfolios.create') }}"><x-icon name="plus" :size="15" /> Start another page</a>
                    <span class="form-note">{{ $maxPages - $pages->count() }} {{ $maxPages - $pages->count() === 1 ? 'slot' : 'slots' }} left in the beta.</span>
                </div>
            @endif
        @else
            <div class="empty-studio">
                <svg class="empty-studio__doodle" viewBox="0 0 90 62" fill="none" aria-hidden="true">
                    <path d="M3 40c15-3 23 7 36 6s24-12 47-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M10 49c14-2 20 4 31 4s21-7 35-4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity=".5"/>
                    <path d="M55 8l13 5-7 12-13-5z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                    <path d="M59 11l8 3" stroke="currentColor" stroke-width="1" opacity=".6"/>
                </svg>
                <span class="tag">A clean studio</span>
                <h2>Nothing here yet. That is the best part.</h2>
                <p>Start with a template if you want a considered page in ten minutes, or open a blank file and write the whole thing yourself. You can keep both.</p>
                <a class="btn btn--primary" href="{{ route('studio.portfolios.create') }}">Make your first page <x-icon name="arrow-right" :size="16" /></a>
            </div>
        @endif
    </div>

    <aside class="dashboard-aside">
        <div class="side-card">
            <div class="profile-summary">
                <x-avatar :user="$user" :size="42" />
                <div>
                    <h3>{{ $user->name }}</h3>
                    <p>&#64;{{ $user->username }}</p>
                </div>
            </div>
            <div class="side-card__links">
                <a href="{{ route('settings.edit') }}"><x-icon name="pencil" :size="15" /> Edit your profile</a>
                <a href="{{ route('makers.show', $user->username) }}"><x-icon name="globe" :size="15" /> Public profile</a>
            </div>
        </div>

        <div class="side-card">
            <p class="side-card__label">Before you publish</p>
            <h3>Three small things.</h3>
            <div class="side-checks">
                <span class="{{ $pages->isNotEmpty() ? 'is-done' : '' }}">
                    <x-icon :name="$pages->isNotEmpty() ? 'check' : 'arrow-right'" :size="14" />
                    Make a page
                </span>
                <span class="{{ $profileReady ? 'is-done' : '' }}">
                    <x-icon :name="$profileReady ? 'check' : 'arrow-right'" :size="14" />
                    Add a bio and a face
                </span>
                <span class="{{ $publishedCount > 0 ? 'is-done' : '' }}">
                    <x-icon :name="$publishedCount > 0 ? 'check' : 'arrow-right'" :size="14" />
                    Publish one live page
                </span>
            </div>
        </div>

        <div class="side-card side-card--dark">
            <p class="side-card__label">The code route</p>
            <h3>Things move here.</h3>
            <p>Three starters, a live preview, and a sandbox that lets your JavaScript run without borrowing our cookies.</p>
            <div class="side-card__links">
                <a href="{{ route('docs.code') }}"><x-icon name="code" :size="15" /> Read the code guide</a>
                <a href="{{ route('studio.portfolios.create') }}"><x-icon name="arrow-right" :size="15" /> Start a code page</a>
            </div>
        </div>

        @if ($user->is_admin)
            <div class="side-card">
                <p class="side-card__label">Staff</p>
                <h3>Moderation desk.</h3>
                <p>Reports, featured pages, and the unpublish switch.</p>
                <div class="side-card__links">
                    <a href="{{ route('admin.index') }}"><x-icon name="shield" :size="15" /> Open moderation</a>
                </div>
            </div>
        @endif
    </aside>
</div>
@endsection
