@extends('layouts.public')

@section('title', 'House rules · Portside')
@section('description', 'The short, readable terms for using Portside: your work, your code, and the things that do not belong here.')
@section('canonical', route('terms'))

@section('content')
<div class="wrap" style="max-width: 860px; padding-top: clamp(48px, 8vw, 96px); padding-bottom: 96px;">
    <header class="page-hero" style="border-bottom: 0; padding-bottom: 16px;">
        <p class="eyebrow">The small print</p>
        <h1>House rules.</h1>
        <p class="page-hero__lede">Short version: publish your own work, do not hurt people, and do not make us regret hosting JavaScript.</p>
        <div class="page-hero__meta"><span>Last updated {{ date('F Y') }}</span></div>
    </header>

    <article class="prose">
        <h2>Your account</h2>
        <p>You need an account to publish. Keep your password to yourself, and use an address you can still open next year. You are responsible for what happens under your account. One person per account; shared studios can ask us about a team plan that does not exist yet.</p>

        <h2>Your work stays yours</h2>
        <p>You own the words, images and code you upload. You give Portside permission to host and display that material for the purpose of running your page. That permission ends when you delete the page, except for backup copies that take a short while to disappear.</p>
        <p>Only upload work you made or have explicit permission to use. Credit collaborators. Do not use a client’s work in a way that breaks your agreement with them.</p>

        <h2>What does not belong here</h2>
        <ul>
            <li>Malware, phishing, crypto scams, or pages that pretend to be another service.</li>
            <li>Harassment, hate speech, threats, or non-consensual intimate imagery.</li>
            <li>Stolen work, impersonation, or deliberately misleading credentials.</li>
            <li>Content that is illegal where you or your visitors live.</li>
            <li>Traffic tricks: hidden redirects, cloaking, spam pages, or attempts to break the sandbox.</li>
        </ul>
        <p>Pages can be reported from the badge on a published page or at <code>/report/your-address</code>. A human reviews reports. We can unpublish or remove a page, and close an account for repeated or serious abuse.</p>

        <h2>Code pages and the sandbox</h2>
        <p>Custom HTML, CSS and JavaScript run in a sandboxed browser context. That protects Portside and its visitors from your code, not the other way around. Do not attempt to attack the platform, other accounts, or visitors. There is no server-side execution, no database access, and no guarantee that a third-party library will keep working.</p>

        <h2>Availability and limits</h2>
        <p>The beta is free and provided as-is. We aim to keep it online, but no service is perfect. Current limits are four pages, 5 MB per image, 300 KB of HTML and 200 KB each of CSS and JavaScript. We may change limits with notice on this page. Take a copy of code pages you care about; the export button exists so you are never trapped.</p>

        <h2>Ending it</h2>
        <p>You can delete a page from its settings at any time. Account deletion is available on request while the beta is small. We can suspend an account that breaks these rules, and we will tell you why unless doing so would put someone else at risk.</p>

        <h2>Questions</h2>
        <p>These rules are deliberately readable, not a substitute for legal advice. If something is unclear, write to <a href="mailto:hello@portside.test">hello@portside.test</a>.</p>
    </article>
</div>
@endsection
