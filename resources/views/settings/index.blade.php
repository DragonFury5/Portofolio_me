@extends('layouts.app')

@section('title', 'Account settings · Portside Studio')
@section('body_class', 'app-body')

@section('content')
<header class="studio-head">
    <div>
        <p class="eyebrow">Account</p>
        <h1>Your identity,<br>off the page.</h1>
        <p>This is the person behind every page you publish. Keep it short and recognisable.</p>
        <div class="studio-head__meta">
            <span>{{ $publishedCount }} live {{ $publishedCount === 1 ? 'page' : 'pages' }}</span>
            <span>{{ $pageCount }} total</span>
            <span>{{ human_number($totalViews) }} views</span>
            <a href="{{ route('makers.show', $user->username) }}">View public profile</a>
        </div>
    </div>
</header>

@if ($errors->any())
    <div class="flash flash--error" role="alert" style="margin-bottom: 20px;">
        <span class="flash__icon"><x-icon name="x" :size="16" /></span>
        <div>
            <p><strong>Some changes need another look.</strong></p>
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
        <form class="form-card" method="post" action="{{ route('settings.profile') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-card__head">
                <h2>The profile</h2>
                <span>Shown on /u/{{ $user->username }}</span>
            </div>

            <div class="field">
                <span class="field__label">Portrait</span>
                <div class="project-image">
                    <div class="project-image__thumb" style="width: 64px; height: 64px; border-radius: 50%;">
                        <x-avatar :user="$user" :size="64" />
                    </div>
                    <div class="project-image__actions">
                        <label class="upload-btn">
                            <x-icon name="upload" :size="13" /> Change portrait
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
                        </label>
                        @if ($user->avatar)
                            <label class="checkbox" style="font-size: 11.5px;">
                                <input type="checkbox" name="remove_avatar" value="1">
                                <span>Remove</span>
                            </label>
                        @endif
                        <span class="upload-note">JPG, PNG or WebP · 5 MB</span>
                    </div>
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="name">Name</label>
                    <input class="input" id="name" name="name" type="text" maxlength="60" required value="{{ old('name', $user->name) }}">
                </div>
                <div class="field">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <span class="input-group__prefix">/u/</span>
                        <input class="input" id="username" name="username" type="text" maxlength="24" required value="{{ old('username', $user->username) }}">
                    </div>
                </div>
            </div>
            <p class="field__help">Changing your username changes your public profile address. Existing page links are unaffected.</p>

            <div class="field">
                <label for="bio">Short bio</label>
                <textarea class="textarea" id="bio" name="bio" maxlength="700" style="min-height: 120px;">{{ old('bio', $user->bio) }}</textarea>
                <p class="field__help">Who you are, what you make, and where. Up to 700 characters.</p>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="location">Location</label>
                    <input class="input" id="location" name="location" type="text" maxlength="100" value="{{ old('location', $user->location) }}" placeholder="City, country">
                </div>
                <div class="field">
                    <label for="website">Website</label>
                    <input class="input" id="website" name="website" type="text" maxlength="190" value="{{ old('website', $user->website) }}" placeholder="https://">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn--primary" type="submit">Save profile</button>
                <a class="btn btn--ghost" href="{{ route('makers.show', $user->username) }}" target="_blank" rel="noopener">View public profile <x-icon name="external" :size="14" /></a>
            </div>
        </form>

        <form class="form-card" method="post" action="{{ route('settings.password') }}">
            @csrf
            @method('PUT')

            <div class="form-card__head">
                <h2>The password</h2>
                <span>Ten characters minimum</span>
            </div>

            <div class="field">
                <label for="current_password">Current password</label>
                <input class="input" id="current_password" name="current_password" type="password" autocomplete="current-password" required>
            </div>
            <div class="field-row">
                <div class="field">
                    <label for="new_password">New password</label>
                    <input class="input" id="new_password" name="password" type="password" minlength="10" autocomplete="new-password" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">New password, again</label>
                    <input class="input" id="password_confirmation" name="password_confirmation" type="password" minlength="10" autocomplete="new-password" required>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn--outline" type="submit">Change password</button>
                <span class="form-note">Other sessions will be signed out.</span>
            </div>
        </form>
    </div>

    <aside class="settings-aside">
        <div class="side-card">
            <p class="side-card__label">Public preview</p>
            <div class="profile-summary" style="margin-top: 8px;">
                <x-avatar :user="$user" :size="48" />
                <div>
                    <h3>{{ $user->name }}</h3>
                    <p>&#64;{{ $user->username }}</p>
                </div>
            </div>
            @if ($user->bio)
                <p style="margin-top: 14px;">{{ excerpt_text($user->bio, 160) }}</p>
            @endif
            <div class="side-card__links">
                <a href="{{ route('makers.show', $user->username) }}"><x-icon name="globe" :size="15" /> Open public profile</a>
                <a href="{{ route('dashboard') }}"><x-icon name="layout" :size="15" /> Back to the studio</a>
            </div>
        </div>

        <div class="side-card">
            <p class="side-card__label">The beta deal</p>
            <h3>Four pages. No invoice.</h3>
            <p>Your account includes four pages, image uploads and the code editor. No card, no trial countdown. The limits are documented in the code guide.</p>
            <div class="side-card__links">
                <a href="{{ route('docs.code') }}"><x-icon name="file-code" :size="15" /> Read the code guide</a>
                <a href="{{ route('privacy') }}"><x-icon name="shield" :size="15" /> How privacy works</a>
            </div>
        </div>

        <div class="side-card side-card--dark">
            <p class="side-card__label">Leaving</p>
            <h3>Your work stays portable.</h3>
            <p>Code pages download as one file. Your profile is just a public link. If you want an account removed, ask in the open.</p>
            <div class="side-card__links">
                <a href="{{ route('terms') }}"><x-icon name="arrow-right" :size="15" /> House rules</a>
            </div>
        </div>
    </aside>
</div>
@endsection
