<?php require __DIR__ . '/_head.php'; ?>
<div class="tide">
    <header class="tide-top">
        <a class="tide-brand" href="#top"><?= e($site['display_name']) ?></a>
        <p class="tide-top__note"><?= e($content['availability']) ?></p>
        <nav class="tide-nav" aria-label="Sections">
            <a href="#work">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main id="top">
        <section class="tide-hero">
            <div class="tide-hero__copy">
                <p class="tpl-eyebrow"><?= e($content['eyebrow']) ?></p>
                <h1><?= inline_markup($content['headline']) ?></h1>
            </div>
            <div class="tide-hero__side">
                <div class="tide-portrait">
                    <?php if (!empty($site['avatar'])): ?>
                        <img src="<?= e(media_url($site['avatar'])) ?>" alt="<?= e($site['display_name']) ?>" loading="lazy">
                    <?php else: ?>
                        <span><?= e(initials($site['display_name'])) ?></span>
                    <?php endif; ?>
                </div>
                <p class="tide-intro"><?= inline_markup($content['intro']) ?></p>
                <?php if ($content['location'] !== ''): ?>
                    <p class="tide-loc"><?= e($content['location']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="tide-rail" id="work" aria-labelledby="tide-work-title">
            <div class="tide-rail__head">
                <h2 id="tide-work-title">Things I’ve made</h2>
                <p><span class="tide-arrow">→</span> Scroll the shelf</p>
            </div>
            <div class="tide-scroller" tabindex="0" role="region" aria-label="Selected projects, scroll horizontally">
                <?php foreach ($content['projects'] as $i => $project): ?>
                    <?php $href = safe_href($project['url']); $img = media_url($project['image']); ?>
                    <article class="tide-card">
                        <div class="tide-card__image">
                            <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener" aria-label="<?= e($project['title']) ?>"><?php endif; ?>
                            <?php if ($img): ?>
                                <img src="<?= e($img) ?>" alt="<?= e($project['title']) ?>" loading="lazy" decoding="async">
                            <?php else: ?>
                                <div class="tide-card__empty" aria-hidden="true"><span><?= sprintf('%02d', $i + 1) ?></span></div>
                            <?php endif; ?>
                            <?php if ($href): ?></a><?php endif; ?>
                        </div>
                        <div class="tide-card__meta">
                            <span><?= sprintf('%02d', $i + 1) ?> / <?= e($project['year']) ?></span>
                            <span><?= e($project['role']) ?></span>
                        </div>
                        <h3>
                            <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($project['title']) ?></a><?php else: ?><?= e($project['title']) ?><?php endif; ?>
                        </h3>
                        <?php if ($project['summary'] !== ''): ?><p><?= e($project['summary']) ?></p><?php endif; ?>
                    </article>
                <?php endforeach; ?>
                <?php if (!$content['projects']): ?>
                    <p class="tide-empty">The shelf is being re-stocked. Check back soon.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="tide-about" id="about">
            <div class="tide-about__head">
                <p class="tpl-eyebrow">A little context</p>
                <h2><?= e($site['display_name']) ?> <em>makes things.</em></h2>
            </div>
            <div class="tide-about__body">
                <p><?= inline_markup($content['about'] ?: $content['intro']) ?></p>
                <div class="tide-facts">
                    <?php if ($content['location'] !== ''): ?>
                        <p><span>Based in</span><?= e($content['location']) ?></p>
                    <?php endif; ?>
                    <?php if ($content['availability'] !== ''): ?>
                        <p><span>Right now</span><?= e($content['availability']) ?></p>
                    <?php endif; ?>
                    <?php if ($content['links']): ?>
                        <p class="tide-facts__links">
                            <span>Elsewhere</span>
                            <?php foreach ($content['links'] as $link): ?>
                                <?php $href = safe_href($link['url']); ?>
                                <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($link['label']) ?></a><?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="tide-foot" id="contact">
        <div class="tide-foot__inner">
            <div>
                <p class="tpl-eyebrow tpl-eyebrow--light">Say hello</p>
                <?php if ($content['email'] !== ''): ?>
                    <a class="tide-mail" href="mailto:<?= e($content['email']) ?>"><?= e($content['email']) ?></a>
                <?php else: ?>
                    <p class="tide-mail"><?= e($site['display_name']) ?></p>
                <?php endif; ?>
            </div>
            <p class="tide-foot__note"><?= e($content['availability'] ?: 'Open for commissions.') ?></p>
        </div>
        <div class="tide-foot__bottom">
            <span>© <?= date('Y') ?> <?= e($site['display_name']) ?></span>
            <span>Made on Portside</span>
        </div>
    </footer>
</div>
<?php require __DIR__ . '/_badge.php'; ?>
</body>
</html>
