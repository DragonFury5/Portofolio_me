<?php require __DIR__ . '/_head.php'; ?>
<?php $initials = initials($site['display_name']); ?>
<div class="signal">
    <aside class="signal-rail">
        <div class="signal-rail__top">
            <a class="signal-mark" href="#top" aria-label="<?= e($site['display_name']) ?> — top"><?= e($initials) ?></a>
            <p class="signal-name"><?= e($site['display_name']) ?></p>
            <p class="signal-role"><?= e($content['eyebrow']) ?></p>
            <?php if ($content['availability'] !== ''): ?>
                <p class="signal-status"><span></span><?= e($content['availability']) ?></p>
            <?php endif; ?>
        </div>

        <nav class="signal-nav" aria-label="Sections">
            <a href="#work"><span>01</span>Work</a>
            <a href="#about"><span>02</span>About</a>
            <a href="#contact"><span>03</span>Contact</a>
        </nav>

        <div class="signal-rail__bottom">
            <?php if ($content['location'] !== ''): ?>
                <p class="signal-meta"><span>Based</span><?= e($content['location']) ?></p>
            <?php endif; ?>
            <?php if ($content['links']): ?>
                <div class="signal-links">
                    <?php foreach ($content['links'] as $link): ?>
                        <?php $href = safe_href($link['url']); ?>
                        <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($link['label']) ?><span>↗</span></a><?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($content['email'] !== ''): ?>
                <a class="signal-mail-sm" href="mailto:<?= e($content['email']) ?>"><?= e($content['email']) ?></a>
            <?php endif; ?>
            <p class="signal-colophon">Built by hand · <?= date('Y') ?></p>
        </div>
    </aside>

    <main class="signal-main" id="top">
        <section class="signal-intro">
            <p class="tpl-eyebrow"><?= e($content['location'] ?: 'Independent practice') ?> · Portfolio</p>
            <h1><?= inline_markup($content['headline']) ?></h1>
            <p class="signal-intro__text"><?= inline_markup($content['intro']) ?></p>
        </section>

        <section class="signal-work" id="work" aria-labelledby="signal-work-title">
            <div class="signal-sec-head">
                <h2 id="signal-work-title">Selected work</h2>
                <span><?= count($content['projects']) ?> projects</span>
            </div>

            <?php if ($content['projects']): ?>
                <ol class="signal-list">
                    <?php foreach ($content['projects'] as $i => $project): ?>
                        <?php $href = safe_href($project['url']); ?>
                        <li class="signal-item">
                            <span class="signal-item__no"><?= sprintf('%02d', $i + 1) ?></span>
                            <div class="signal-item__body">
                                <h3>
                                    <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($project['title']) ?><span class="signal-arrow">↗</span></a><?php else: ?><?= e($project['title']) ?><?php endif; ?>
                                </h3>
                                <?php if ($project['summary'] !== ''): ?><p><?= e($project['summary']) ?></p><?php endif; ?>
                            </div>
                            <div class="signal-item__meta">
                                <span><?= e($project['role']) ?></span>
                                <span><?= e($project['year']) ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p class="signal-empty">Projects will appear here once they are published.</p>
            <?php endif; ?>
        </section>

        <section class="signal-about" id="about">
            <div class="signal-about__label">
                <h2>About</h2>
                <span>02</span>
            </div>
            <div class="signal-about__body">
                <p class="signal-about__lead"><?= inline_markup($content['about'] ?: $content['intro']) ?></p>
                <div class="signal-facts">
                    <?php if ($content['location'] !== ''): ?>
                        <div><span>Location</span><strong><?= e($content['location']) ?></strong></div>
                    <?php endif; ?>
                    <?php if ($content['availability'] !== ''): ?>
                        <div><span>Availability</span><strong><?= e($content['availability']) ?></strong></div>
                    <?php endif; ?>
                    <?php if ($content['links']): ?>
                        <div>
                            <span>Elsewhere</span>
                            <strong>
                                <?php foreach ($content['links'] as $link): ?>
                                    <?php $href = safe_href($link['url']); ?>
                                    <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($link['label']) ?></a><?php endif; ?>
                                <?php endforeach; ?>
                            </strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="signal-contact" id="contact">
            <p class="tpl-eyebrow">03 / Contact</p>
            <h2>Have something<br>worth building?</h2>
            <?php if ($content['email'] !== ''): ?>
                <a class="signal-mail" href="mailto:<?= e($content['email']) ?>"><?= e($content['email']) ?><span>↗</span></a>
            <?php endif; ?>
            <p class="signal-contact__note"><?= e($content['availability'] ?: 'Open to new work.') ?></p>
        </section>

        <footer class="signal-foot">
            <span>© <?= date('Y') ?> <?= e($site['display_name']) ?></span>
            <span>Hosted on Portside</span>
        </footer>
    </main>
</div>
<?php require __DIR__ . '/_badge.php'; ?>
</body>
</html>
