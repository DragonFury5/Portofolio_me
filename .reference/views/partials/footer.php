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
                    <a href="<?= url('/signup') ?>">Start a portfolio</a>
                    <a href="<?= url('/explore') ?>">Explore the work</a>
                    <a href="<?= url('/templates') ?>">Templates</a>
                </div>
                <div class="site-foot__col">
                    <p class="site-foot__label">Learn</p>
                    <a href="<?= url('/docs/code') ?>">Code guide</a>
                    <a href="<?= url('/#how') ?>">How it works</a>
                    <a href="<?= url('/#faq') ?>">FAQ</a>
                </div>
                <div class="site-foot__col">
                    <p class="site-foot__label">The small print</p>
                    <a href="<?= url('/terms') ?>">Terms</a>
                    <a href="<?= url('/privacy') ?>">Privacy</a>
                    <a href="<?= url('/sitemap.xml') ?>">Sitemap</a>
                </div>
            </div>
        </div>

        <div class="site-foot__mark" aria-hidden="true">
            <span>Portside</span>
            <svg viewBox="0 0 620 18" fill="none" aria-hidden="true"><path d="M3 11c58-7 92 6 146 3s89-9 140-3 78 10 132 3 83-7 131 1 47 5 65 2" stroke="#60C1E5" stroke-width="3" stroke-linecap="round"/></svg>
        </div>

        <div class="site-foot__bottom">
            <p>© <?= date('Y') ?> Portside. Open beta, built by hand.</p>
            <p>PHP · SQLite · a stubborn grid. No trackers, no ad slot.</p>
        </div>
    </div>
</footer>
