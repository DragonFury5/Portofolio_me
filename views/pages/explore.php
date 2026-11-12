<?php
page_start();

$pageNum = max(1, (int) (query('page') ?? 1));
$perPage = 9;
$filters = [
    'q' => trim((string) query('q', '')),
    'mode' => query('mode', '') ?? '',
    'template' => query('template', '') ?? '',
    'sort' => query('sort', 'recent') ?? 'recent',
];
if (!in_array($filters['mode'], ['', 'template', 'code'], true)) {
    $filters['mode'] = '';
}
if (!in_array($filters['sort'], ['recent', 'popular'], true)) {
    $filters['sort'] = 'recent';
}
if ($filters['template'] !== '' && !array_key_exists($filters['template'], portfolio_templates())) {
    $filters['template'] = '';
}

$total = public_site_count($filters);
$pages = max(1, (int) ceil($total / $perPage));
$pageNum = min($pageNum, $pages);
$sites = public_sites($filters, $perPage, ($pageNum - 1) * $perPage);

$buildUrl = static function (array $overrides = []) use ($filters, $pageNum): string {
    $params = array_merge($filters, ['page' => $pageNum], $overrides);
    foreach ($params as $key => $value) {
        if ($value === '' || ($key === 'page' && (int) $value === 1)) {
            unset($params[$key]);
        }
    }
    $query = http_build_query($params);
    return url('/explore') . ($query !== '' ? '?' . $query : '');
};

$hasFilters = $filters['q'] !== '' || $filters['mode'] !== '' || $filters['template'] !== '';
?>
<div class="wrap">
    <header class="explore-head">
        <div>
            <p class="eyebrow">The harbour</p>
            <h1>Work worth<br>a slow look.</h1>
        </div>
        <div class="explore-head__aside">
            <p class="explore-head__count"><?= number_format($total) ?> published <?= $total === 1 ? 'page' : 'pages' ?>, all running live.</p>
            <form class="search-bar" method="get" action="<?= url('/explore') ?>" role="search">
                <?php if ($filters['mode'] !== ''): ?><input type="hidden" name="mode" value="<?= e($filters['mode']) ?>"><?php endif; ?>
                <?php if ($filters['template'] !== ''): ?><input type="hidden" name="template" value="<?= e($filters['template']) ?>"><?php endif; ?>
                <?php if ($filters['sort'] !== 'recent'): ?><input type="hidden" name="sort" value="<?= e($filters['sort']) ?>"><?php endif; ?>
                <?= icon('eye', 17) ?>
                <label class="visually-hidden" for="explore-q">Search pages and makers</label>
                <input id="explore-q" type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Search makers, pages, projects…">
                <button class="btn btn--sm btn--primary" type="submit">Search</button>
            </form>
        </div>
    </header>

    <div class="explore-toolbar">
        <div class="explore-toolbar__group">
            <a class="filter-chip" href="<?= e($buildUrl(['mode' => '', 'template' => '', 'page' => 1])) ?>" <?= $filters['mode'] === '' && $filters['template'] === '' ? 'aria-current="true"' : '' ?>>Everything</a>
            <a class="filter-chip" href="<?= e($buildUrl(['mode' => 'template', 'template' => '', 'page' => 1])) ?>" <?= $filters['mode'] === 'template' ? 'aria-current="true"' : '' ?>>Templates</a>
            <a class="filter-chip" href="<?= e($buildUrl(['mode' => 'code', 'template' => '', 'page' => 1])) ?>" <?= $filters['mode'] === 'code' ? 'aria-current="true"' : '' ?>>Hand-coded</a>
            <?php foreach (portfolio_templates() as $key => $template): ?>
                <a class="filter-chip" href="<?= e($buildUrl(['mode' => 'template', 'template' => $key, 'page' => 1])) ?>" <?= $filters['template'] === $key ? 'aria-current="true"' : '' ?>><?= e($template['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <form method="get" action="<?= url('/explore') ?>">
            <input type="hidden" name="q" value="<?= e($filters['q']) ?>">
            <input type="hidden" name="mode" value="<?= e($filters['mode']) ?>">
            <input type="hidden" name="template" value="<?= e($filters['template']) ?>">
            <label class="visually-hidden" for="explore-sort">Sort pages</label>
            <select class="explore-sort" id="explore-sort" name="sort" onchange="this.form.submit()">
                <option value="recent" <?= $filters['sort'] === 'recent' ? 'selected' : '' ?>>Recently updated</option>
                <option value="popular" <?= $filters['sort'] === 'popular' ? 'selected' : '' ?>>Most viewed</option>
            </select>
            <noscript><button class="btn btn--sm btn--outline" type="submit">Sort</button></noscript>
        </form>
    </div>

    <?php if ($filters['q'] !== ''): ?>
        <p class="muted" style="padding-top: 18px; font-size: 13px; margin: 0;">
            <?= number_format($total) ?> <?= $total === 1 ? 'result' : 'results' ?> for “<?= e($filters['q']) ?>”.
            <a href="<?= url('/explore') ?>" style="color: var(--brand-secondary); text-decoration: underline;">Clear search</a>
        </p>
    <?php endif; ?>

    <?php if ($sites): ?>
        <div class="explore-grid">
            <?php foreach ($sites as $index => $site): ?>
                <a class="explore-card" href="<?= url('/p/' . $site['slug']) ?>">
                    <div class="explore-card__frame">
                        <iframe src="<?= url('/p/' . $site['slug'] . '?embed=1') ?>"
                                title="Preview of <?= e($site['title']) ?>"
                                loading="<?= $index < 3 ? 'eager' : 'lazy' ?>"
                                tabindex="-1" aria-hidden="true"
                                sandbox="allow-scripts allow-forms allow-popups allow-modals"></iframe>
                    </div>
                    <div class="explore-card__meta">
                        <div>
                            <h2><?= e($site['title']) ?></h2>
                            <p><?= e($site['display_name']) ?> · <?= e($site['mode'] === 'code' ? 'Hand-coded' : template_or_default($site['template'])['name']) ?> · <?= human_number((int) $site['views']) ?> views</p>
                        </div>
                        <span class="tag explore-card__tag"><?= $site['mode'] === 'code' ? 'Code' : 'Template' ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($pages > 1): ?>
            <nav class="pagination" aria-label="Pages">
                <?php if ($pageNum > 1): ?>
                    <a class="page-btn" href="<?= e($buildUrl(['page' => $pageNum - 1])) ?>" aria-label="Previous page"><?= icon('arrow-left', 14) ?></a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a class="page-btn" href="<?= e($buildUrl(['page' => $i])) ?>" <?= $i === $pageNum ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($pageNum < $pages): ?>
                    <a class="page-btn" href="<?= e($buildUrl(['page' => $pageNum + 1])) ?>" aria-label="Next page"><?= icon('arrow-right', 14) ?></a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state" style="margin: 40px 0 24px;">
            <h2><?= $hasFilters ? 'Nothing matches that yet.' : 'The harbour is quiet.' ?></h2>
            <p><?= $hasFilters ? 'Try a broader search, or clear the filters and see everything.' : 'No pages have been published yet. Be the first to put something in the water.' ?></p>
            <div class="error-links">
                <?php if ($hasFilters): ?><a class="btn btn--outline" href="<?= url('/explore') ?>">Clear filters</a><?php endif; ?>
                <a class="btn btn--primary" href="<?= url('/signup') ?>">Start a portfolio</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
page_end([
    'title' => 'Explore portfolios · ' . APP_NAME,
    'description' => 'Browse live portfolios made with Portside — editorial templates, hand-coded experiments, and everything between.',
    'canonical' => absolute_url('/explore'),
    'bodyClass' => 'explore-page',
]);
