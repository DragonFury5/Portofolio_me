<footer class="site-foot">
    <div class="wrap">
        <div class="site-foot__top">
            <div class="site-foot__intro">
                <p class="eyebrow eyebrow--light">Portfolio hosting, no training wheels</p>
                <p class="site-foot__line">Start with a template.<br>Stay for the source code.</p>
            </div>
            <div class="site-foot__cols">
                <div class="site-foot__col">
                    <p class="site-foot__label">Make</p>
                    <a href="{{ auth()->check() ? route('studio.portfolios.create') : route('register') }}">Start a portfolio</a>
                    <a href="{{ route('explore') }}">Explore the work</a>
                    <a href="{{ route('templates.index') }}">Templates</a>
                </div>
                <div class="site-foot__col">
                    <p class="site-foot__label">Learn</p>
                    <a href="{{ route('docs.code') }}">Code guide</a>
                    <a href="{{ route('home') }}#how">How it works</a>
                    <a href="{{ route('home') }}#faq">FAQ</a>
                </div>
                <div class="site-foot__col">
                    <p class="site-foot__label">The small print</p>
                    <a href="{{ route('terms') }}">Terms</a>
                    <a href="{{ route('privacy') }}">Privacy</a>
                    <a href="{{ route('sitemap') }}">Sitemap</a>
                </div>
            </div>
        </div>

        <div class="site-foot__mark" aria-hidden="true">
            <span>Portside</span>
            <svg viewBox="0 0 620 18" fill="none" aria-hidden="true"><path d="M3 11c58-7 92 6 146 3s89-9 140-3 78 10 132 3 83-7 131 1 47 5 65 2" stroke="#60C1E5" stroke-width="3" stroke-linecap="round"/></svg>
        </div>

        <div class="site-foot__bottom">
            <p>© {{ date('Y') }} Portside. Open beta, built by hand.</p>
            <p>Laravel · hand-built CSS · no trackers, no ad slot.</p>
        </div>
    </div>
</footer>
