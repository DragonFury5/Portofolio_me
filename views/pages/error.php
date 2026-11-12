<section class="error-page">
    <div class="wrap error-grid">
        <div>
            <p class="eyebrow">Error <?= (int) $code ?></p>
            <div class="error-code"><?= (int) $code ?></div>
        </div>
        <div>
            <h1><?= e($heading) ?></h1>
            <p><?= e($body) ?></p>
            <div class="error-links">
                <a class="btn btn--primary" href="<?= url('/') ?>">Back to portside</a>
                <a class="btn btn--outline" href="<?= url('/explore') ?>">Explore the work</a>
            </div>
        </div>
    </div>
    <div class="wrap" style="margin-top: 48px;">
        <svg viewBox="0 0 1200 120" fill="none" aria-hidden="true" style="width:100%;height:auto;color:var(--brand-primary);">
            <path d="M0 86c96-34 170 16 262-8s150-38 242-16 146 44 238 20 164-44 256-18 132 30 202 18" stroke="currentColor" stroke-width="1.5" opacity=".3"/>
            <path d="M0 104c120-26 190 18 300-2s166-30 268-10 154 34 250 8 182-36 282-12 70 12 100 6" stroke="#60C1E5" stroke-width="1.5" opacity=".65"/>
        </svg>
    </div>
</section>
