@extends('layouts.auth')

@section('title', 'Start a portfolio · Portside')
@section('description', 'Create a Portside account and make a portfolio with a template or your own code.')

@section('form')
    <div class="auth-form__head">
        <p class="eyebrow">Open beta · free</p>
        <h1>Start a portfolio.</h1>
        <p class="auth-form__sub">Make a page you can hand to anyone. Four pages per account, no card.</p>
    </div>

    <form method="post" action="{{ route('register.store', array_filter(['template' => $selectedTemplate])) }}">
        @csrf
        <div class="field">
            <label for="name">Name on the page</label>
            <input
                class="input"
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                autocomplete="name"
                required
                autofocus
                @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
            >
            @error('name')<p class="field__error" id="name-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label for="username">Your address</label>
            <div class="input-group">
                <span class="input-group__prefix">/u/</span>
                <input
                    class="input"
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    pattern="[a-z0-9][a-z0-9_]{1,22}[a-z0-9]"
                    maxlength="24"
                    required
                    @error('username') aria-invalid="true" aria-describedby="username-error" @enderror
                >
            </div>
            <p class="field__help">3–24 lowercase letters, numbers or underscores. This becomes your public profile.</p>
            @error('username')<p class="field__error" id="username-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input
                class="input"
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
            >
            @error('email')<p class="field__error" id="email-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input
                class="input"
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                minlength="10"
                required
                @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
            >
            <p class="field__help">At least 10 characters. A short sentence is easier to remember than a puzzle.</p>
            @error('password')<p class="field__error" id="password-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Password, again</label>
            <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
        </div>

        <label class="checkbox">
            <input type="checkbox" name="terms" value="1" required @checked(old('terms'))>
            <span>I agree to the <a href="{{ route('terms') }}" target="_blank" rel="noopener" style="color: var(--brand-secondary); text-decoration: underline;">house rules</a> and understand that my page is public once published.</span>
        </label>
        @error('terms')<p class="field__error">{{ $message }}</p>@enderror

        <button class="btn btn--primary btn--block" type="submit">Create my studio <x-icon name="arrow-right" :size="16" /></button>
    </form>

    <p class="auth-form__switch">Already have a page? <a href="{{ route('login') }}">Log in</a>.</p>
    @if ($selectedTemplate)
        <p class="form-note" style="margin-top: 12px;">We’ll start you on the {{ ucfirst($selectedTemplate) }} template.</p>
    @endif
@endsection
