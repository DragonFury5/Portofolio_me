@extends('layouts.public')

@section('title', 'Report “'.$portfolio->title.'” · Portside')
@section('description', 'Report a Portside page for review.')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="wrap" style="max-width: 720px; padding-top: clamp(48px, 7vw, 88px); padding-bottom: 96px;">
    <header class="page-hero" style="padding-bottom: 24px;">
        <p class="eyebrow">Report a page</p>
        <h1>Flag this page.</h1>
        <p class="page-hero__lede">
            You are reporting <strong>{{ $portfolio->title }}</strong> by {{ $portfolio->user->name }}.
            Reports go to a human, not a void. Include links or screenshots in the details if they help.
        </p>
    </header>

    <div class="form-card">
        <form method="post" action="{{ route('reports.store', $portfolio->slug) }}">
            @csrf
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;">

            <div class="field">
                <label for="reason">What is wrong?</label>
                <select class="select" id="reason" name="reason" required>
                    <option value="">Choose a reason</option>
                    @foreach ($reasons as $value => $label)
                        <option value="{{ $value }}" @selected(old('reason') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('reason')<p class="field__error">{{ $message }}</p>@enderror
            </div>

            <div class="field" style="margin-top: 14px;">
                <label for="details">Details <span class="form-note">(optional)</span></label>
                <textarea class="textarea" id="details" name="details" maxlength="1200" placeholder="What should we look at?">{{ old('details') }}</textarea>
                @error('details')<p class="field__error">{{ $message }}</p>@enderror
            </div>

            <div class="field" style="margin-top: 14px;">
                <label for="reporter_email">Your email <span class="form-note">(optional, for a reply)</span></label>
                <input class="input" id="reporter_email" name="reporter_email" type="email" value="{{ old('reporter_email') }}" autocomplete="email">
                @error('reporter_email')<p class="field__error">{{ $message }}</p>@enderror
            </div>

            <div class="form-actions">
                <button class="btn btn--primary" type="submit">Send report <x-icon name="arrow-right" :size="16" /></button>
                <a class="btn btn--ghost" href="{{ $portfolio->publicUrl() }}">Back to the page</a>
            </div>
        </form>
    </div>
</div>
@endsection
