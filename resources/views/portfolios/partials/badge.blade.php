<div class="ps-badge">
    <a class="ps-badge__home" href="{{ route('home') }}" target="_blank" rel="noopener">
        <x-brand-mark :size="14" class="ps-badge__mark" />
        <span>Made with Portside</span>
    </a>
    <a class="ps-badge__report" href="{{ route('reports.create', $portfolio->slug) }}">Report</a>
</div>
