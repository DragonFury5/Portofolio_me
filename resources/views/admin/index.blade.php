@extends('layouts.app')

@section('title', 'Moderation · Portside Studio')
@section('body_class', 'app-body')

@section('content')
<header class="studio-head">
    <div>
        <p class="eyebrow">Staff · Moderation</p>
        <h1>The quiet desk.</h1>
        <p>Reports, featured pages, and taking a page back to draft. No growth metrics, no queue for the sake of a queue.</p>
        <div class="studio-head__meta">
            <span>{{ $reports->count() }} open {{ $reports->count() === 1 ? 'report' : 'reports' }}</span>
            <span>{{ $livePages }} live pages</span>
            <a href="{{ route('dashboard') }}">Back to your pages</a>
        </div>
    </div>
    <a class="btn btn--outline" href="{{ route('explore') }}">See Explore</a>
</header>

<div class="stats-strip">
    <div class="stat">
        <span class="stat__value">{{ $pages }}</span>
        <span class="stat__label">Pages on the platform</span>
    </div>
    <div class="stat">
        <span class="stat__value">{{ $livePages }}</span>
        <span class="stat__label">Published</span>
    </div>
    <div class="stat">
        <span class="stat__value">{{ $makers }}</span>
        <span class="stat__label">Makers with pages</span>
    </div>
</div>

<div class="section-label">
    <h2>Open reports</h2>
    <span>Oldest first? Newest first. People first.</span>
</div>

@if ($reports->isNotEmpty())
    <div class="admin-table-wrap" style="margin-bottom: 40px;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Reason</th>
                    <th>Details</th>
                    <th>Reported</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ $report->portfolio->publicUrl() }}" target="_blank" rel="noopener" style="font-weight: 600; text-decoration: underline; text-underline-offset: 2px;">{{ $report->portfolio->title }}</a>
                            <div class="form-note">{{ $report->portfolio->user->name }} · /p/{{ $report->portfolio->slug }}</div>
                        </td>
                        <td>{{ $reasons[$report->reason] ?? $report->reason }}</td>
                        <td style="max-width: 340px; white-space: normal;">{{ $report->details ?: '—' }}@if ($report->reporter_email)<div class="form-note">{{ $report->reporter_email }}</div>@endif</td>
                        <td>{{ time_ago($report->created_at) }}</td>
                        <td>
                            <div class="admin-actions">
                                <form method="post" action="{{ route('admin.reports.resolve', $report) }}">
                                    @csrf
                                    <button class="btn btn--sm btn--outline" type="submit">Resolve</button>
                                </form>
                                <form method="post" action="{{ route('admin.pages.unpublish', $report->portfolio) }}">
                                    @csrf
                                    <button class="btn btn--sm btn--danger" type="submit">Unpublish</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="empty-state" style="margin: 20px 0 48px;">
        <h2>Nothing waiting.</h2>
        <p>No open reports. Go look at the work instead; the report button is on every published template page.</p>
    </div>
@endif

<div class="section-label">
    <h2>Recently edited pages</h2>
    <span>Feature the good ones. Unpublish the others.</span>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Page</th>
                <th>Maker</th>
                <th>Mode</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recentPages as $page)
                <tr>
                    <td>
                        <a href="{{ $page->is_published ? $page->publicUrl() : route('dashboard') }}" @if($page->is_published) target="_blank" rel="noopener" @endif style="font-weight: 600;">{{ $page->title }}</a>
                        <div class="form-note">/p/{{ $page->slug }}</div>
                    </td>
                    <td>{{ $page->user->name }}</td>
                    <td>{{ $page->isCode() ? 'Code' : $page->templateMeta()['name'] }}</td>
                    <td><span class="status {{ $page->is_published ? 'status--live' : 'status--draft' }}">{{ $page->is_published ? 'Live' : 'Draft' }}</span></td>
                    <td>{{ $page->is_featured ? 'Yes' : 'No' }}</td>
                    <td>
                        <div class="admin-actions">
                            <form method="post" action="{{ route('admin.pages.feature', $page) }}">
                                @csrf
                                <button class="btn btn--sm btn--outline" type="submit">{{ $page->is_featured ? 'Unfeature' : 'Feature' }}</button>
                            </form>
                            @if ($page->is_published)
                                <form method="post" action="{{ route('admin.pages.unpublish', $page) }}">
                                    @csrf
                                    <button class="btn btn--sm btn--danger" type="submit">Unpublish</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
