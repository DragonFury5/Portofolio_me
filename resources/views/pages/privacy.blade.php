@extends('layouts.public')

@section('title', 'Privacy · Portside')
@section('description', 'What Portside stores, what it does not, and how to get your work out.')
@section('canonical', route('privacy'))

@section('content')
<div class="wrap" style="max-width: 860px; padding-top: clamp(48px, 8vw, 96px); padding-bottom: 96px;">
    <header class="page-hero" style="border-bottom: 0; padding-bottom: 16px;">
        <p class="eyebrow">The small print</p>
        <h1>Privacy, plainly.</h1>
        <p class="page-hero__lede">No advertising pixels. No data brokers. No “we may share your information with partners” paragraph that needs a lawyer.</p>
        <div class="page-hero__meta"><span>Last updated {{ date('F Y') }}</span></div>
    </header>

    <article class="prose">
        <h2>What we store</h2>
        <ul>
            <li><strong>Account details:</strong> your name, username, email address and a hashed password. We never store the password itself.</li>
            <li><strong>Things you publish:</strong> page titles, descriptions, project text, links, uploaded images, and any HTML, CSS or JavaScript you write.</li>
            <li><strong>Usage:</strong> a daily view count per page, stored without a third-party tracker. We use a session cookie to avoid counting the same visitor twice in a day.</li>
            <li><strong>Reports:</strong> the page reported, the reason, any details you provide, and an optional email if you want a reply.</li>
        </ul>

        <h2>What we do not do</h2>
        <p>We do not run Google Analytics, advertising pixels, cross-site trackers, fingerprinting scripts or AI training on your portfolio. We do not sell personal data. We do not email you marketing you did not ask for. Portside has no cookie banner because it has no advertising cookies to ask about.</p>

        <h2>Cookies</h2>
        <p>One essential session cookie keeps you logged in, protects forms against cross-site request forgery, and remembers which pages you have already viewed today. The cookie is first-party, HTTP-only and expires when your session ends. Custom code pages are sandboxed and cannot read it.</p>

        <h2>Uploads and public pages</h2>
        <p>Anything you publish is public by design. Uploaded images are served from public URLs, so do not upload private documents. The image pipeline re-encodes JPG, PNG and WebP files to strip metadata, including location data that cameras sometimes embed.</p>

        <h2>Email</h2>
        <p>We use your email for login, account changes and essential service notices. Reporters can provide an email to receive a reply; it is not shown on the site. Demo data uses fictional addresses.</p>

        <h2>Retention and your rights</h2>
        <p>Published pages stay until you unpublish or delete them. Deleting a page removes its content from the live site; backups expire on a short cycle. You can ask to see, correct, export or delete the personal data attached to your account. Write to <a href="mailto:hello@portside.test">hello@portside.test</a> and a person will answer.</p>

        <h2>Security</h2>
        <p>Passwords are hashed, forms are CSRF-protected, uploads are validated and re-encoded, custom code runs in a sandbox with an opaque origin. No system is perfect. If you find a vulnerability, please report it privately instead of publishing it.</p>

        <h2>The short version</h2>
        <p>We need the minimum to host your page. We do not want the rest. If you can export it, you can leave with it.</p>
    </article>
</div>
@endsection
