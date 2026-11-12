@extends('layouts.auth')

@section('title', 'Log in · Portside')
@section('description', 'Log in to your Portside studio.')

@section('form')
    <div class="auth-form__head">
        <p class="eyebrow">The studio door</p>
        <h1>Welcome back.</h1>
        <p class="auth-form__sub">Your pages, drafts and code are exactly where you left them.</p>
    </div>

    <form method="post" action="{{ route('login.store') }}">
        @csrf
        <div class="field">
            <label for="login">Email or username</label>
            <input
                class="input"
                id="login"
                name="login"
                type="text"
                value="{{ old('login') }}"
                autocomplete="username"
                required
                autofocus
                @error('login') aria-invalid="true" aria-describedby="login-error" @enderror
            >
            @error('login')
                <p class="field__error" id="login-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input
                class="input"
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
            >
            @error('password')
                <p class="field__error" id="password-error">{{ $message }}</p>
            @enderror
        </div>

        <label class="checkbox">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>Keep me logged in on this device</span>
        </label>

        <button class="btn btn--primary btn--block" type="submit">Log in <x-icon name="arrow-right" :size="16" /></button>
    </form>

    <p class="auth-form__switch">New here? <a href="{{ route('register') }}">Start a portfolio</a>.</p>

    <div class="auth-demo">
        <strong>Looking around first?</strong> The demo studio is open.<br>
        <code>demo@portside.test</code> · <code>portside-demo</code>
    </div>
@endsection
