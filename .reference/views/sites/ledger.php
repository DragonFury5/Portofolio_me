<?php require __DIR__ . '/_head.php'; ?>
<div class="ledger">
    <header class="ledger-top">
        <a class="ledger-brand" href="#top"><?= e($site['display_name']) ?><span>®</span></a>
        <nav class="ledger-nav" aria-label="Sections">
            <a href="#work">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main id="top">
        <section class="ledger-intro">
            <div class="ledger-intro__main">
                <p class="tpl-eyebrow"><?= e($content['eyebrow']) ?></p>
                <h1><?= inline_markup($content['headline']) ?></h1>
            </div>
            <div class="ledger-intro__side">
                <p class="ledger-intro__text"><?= inline_markup($content['intro']) ?></p>
                <?php if ($content['availability'] !== ''): ?>
                    <p class="ledger-avail"><span class="ledger-dot"></span><?= e($content['availability']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="ledger-work" id="work" aria-labelledby="work-title">
            <div class="ledger-sec-head">
                <h2 id="work-title">Selected work</h2>
                <span>Index of projects</span>
            </div>

            <?php if ($content['projects']): ?>
                <ol class="ledger-list">
                    <?php foreach ($content['projects'] as $i => $project): ?>
                        <?php
                        $href = safe_href($project['url']);
                        $thumb = media_url($project['image']);
                        ?>
                        <li class="ledger-item">
                            <article class="ledger-row">
                                <span class="ledger-no"><?= sprintf('%02d', $i + 1) ?></span>
                                <div class="ledger-row__body">
                                    <h3 class="ledger-row__title">
                                        <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($project['title']) ?></a><?php else: ?><?= e($project['title']) ?><?php endif; ?>
                                    </h3>
                                    <?php if ($project['summary'] !== ''): ?>
                                        <p class="ledger-row__summary"><?= e($project['summary']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="ledger-row__meta">
                                    <?php if ($project['role'] !== ''): ?><span><?= e($project['role']) ?></span><?php endif; ?>
                                    <?php if ($project['year'] !== ''): ?><span><?= e($project['year']) ?></span><?php endif; ?>
                                </div>
                                <?php if ($thumb !== ''): ?>
                                    <div class="ledger-row__image"><img src="<?= e($thumb) ?>" alt="<?= e($project['title']) ?>" loading="lazy" decoding="async"></div>
                                <?php else: ?>
                                    <div class="ledger-row__image ledger-row__image--empty" aria-hidden="true"><span><?= sprintf('%02d', $i + 1) ?></span></div>
                                <?php endif; ?>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p class="ledger-empty">Work is being catalogued. Check back soon.</p>
            <?php endif; ?>
        </section>

        <section class="ledger-about" id="about" aria-labelledby="about-title">
            <h2 id="about-title" class="ledger-about__label">About</h2>
            <div class="ledger-about__body">
                <p class="ledger-about__lead"><?= inline_markup($content['about'] ?: $content['intro']) ?></p>
                <dl class="ledger-facts">
                    <?php if ($content['location'] !== ''): ?>
                        <div><dt>Based</dt><dd><?= e($content['location']) ?></dd></div>
                    <?php endif; ?>
                    <?php if ($content['availability'] !== ''): ?>
                        <div><dt>Status</dt><dd><?= e($content['availability']) ?></dd></div>
                    <?php endif; ?>
                    <?php if ($content['links']): ?>
                        <div>
                            <dt>Elsewhere</dt>
                            <dd class="ledger-facts__links">
                                <?php foreach ($content['links'] as $link): ?>
                                    <?php $linkHref = safe_href($link['url']); ?>
                                    <?php if ($linkHref): ?>
                                        <a href="<?= e($linkHref) ?>" target="_blank" rel="noopener"><?= e($link['label']) ?> ↗</a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>
        </section>
    </main>

    <footer class="ledger-foot" id="contact">
        <div class="ledger-foot__inner">
            <p class="tpl-eyebrow tpl-eyebrow--light">Say hello</p>
            <?php if ($content['email'] !== ''): ?>
                <a class="ledger-mail" href="mailto:<?= e($content['email']) ?>"><?= e($content['email']) ?></a>
            <?php else: ?>
                <p class="ledger-mail ledger-mail--quiet"><?= e($site['display_name']) ?></p>
            <?php endif; ?>
            <?php if ($content['links']): ?>
                <div class="ledger-foot__links">
                    <?php foreach ($content['links'] as $link): ?>
                        <?php $linkHref = safe_href($link['url']); ?>
                        <?php if ($linkHref): ?><a href="<?= e($linkHref) ?>" target="_blank" rel="noopener"><?= e($link['label']) ?></a><?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="ledger-foot__bottom">
            <span>© <?= date('Y') ?> <?= e($site['display_name']) ?></span>
            <span>Made on Portside</span>
        </div>
    </footer>
</div>
<?php require __DIR__ . '/_badge.php'; ?>
</body>
</html>
