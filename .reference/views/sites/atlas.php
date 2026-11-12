<?php require __DIR__ . '/_head.php'; ?>
<?php
$heroProject = null;
$rest = $content['projects'];
if ($rest) {
    $heroProject = array_shift($rest);
}
?>
<div class="atlas">
    <header class="atlas-top">
        <a class="atlas-brand" href="#top"><?= e($site['display_name']) ?></a>
        <p class="atlas-top__role"><?= e($content['eyebrow']) ?></p>
        <nav class="atlas-nav" aria-label="Sections">
            <a href="#work">Work</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main id="top">
        <section class="atlas-hero">
            <div class="atlas-hero__copy">
                <h1><?= inline_markup($content['headline']) ?></h1>
                <p class="atlas-intro"><?= inline_markup($content['intro']) ?></p>
                <?php if ($content['availability'] !== ''): ?>
                    <p class="atlas-avail"><span></span><?= e($content['availability']) ?></p>
                <?php endif; ?>
            </div>
            <?php if ($heroProject): ?>
                <?php $heroImg = media_url($heroProject['image']); ?>
                <figure class="atlas-hero__figure">
                    <?php if ($heroImg): ?>
                        <img src="<?= e($heroImg) ?>" alt="<?= e($heroProject['title']) ?>" loading="eager" decoding="async">
                    <?php else: ?>
                        <div class="atlas-plate-empty" aria-hidden="true"><span><?= sprintf('%02d', 1) ?></span></div>
                    <?php endif; ?>
                    <figcaption>
                        <span><?= e($heroProject['title']) ?></span>
                        <span><?= e($heroProject['year']) ?></span>
                    </figcaption>
                </figure>
            <?php endif; ?>
        </section>

        <section class="atlas-work" id="work" aria-labelledby="atlas-work-title">
            <div class="atlas-sec-head">
                <h2 id="atlas-work-title">Selected work</h2>
                <span>Plates &amp; commissions</span>
            </div>

            <div class="atlas-grid">
                <?php foreach ($rest as $i => $project): ?>
                    <?php $img = media_url($project['image']); $href = safe_href($project['url']); ?>
                    <article class="atlas-plate">
                        <div class="atlas-plate__frame">
                            <?php if ($img): ?>
                                <img src="<?= e($img) ?>" alt="<?= e($project['title']) ?>" loading="lazy" decoding="async">
                            <?php else: ?>
                                <div class="atlas-plate-empty" aria-hidden="true"><span><?= sprintf('%02d', $i + 2) ?></span></div>
                            <?php endif; ?>
                        </div>
                        <div class="atlas-plate__meta">
                            <div>
                                <span class="atlas-plate__no"><?= sprintf('%02d', $i + 2) ?></span>
                                <h3>
                                    <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($project['title']) ?></a><?php else: ?><?= e($project['title']) ?><?php endif; ?>
                                </h3>
                            </div>
                            <p><?= e($project['role']) ?><?= $project['role'] !== '' && $project['year'] !== '' ? ' · ' : '' ?><?= e($project['year']) ?></p>
                        </div>
                        <?php if ($project['summary'] !== ''): ?>
                            <p class="atlas-plate__summary"><?= e($project['summary']) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>

                <?php if (!$rest && !$heroProject): ?>
                    <p class="atlas-empty">Work is being catalogued. Check back soon.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="atlas-about" id="about">
            <div class="atlas-about__label">
                <p class="tpl-eyebrow tpl-eyebrow--light">About</p>
                <p class="atlas-about__loc"><?= e($content['location']) ?></p>
            </div>
            <div class="atlas-about__body">
                <p class="atlas-about__lead"><?= inline_markup($content['about'] ?: $content['intro']) ?></p>
                <?php if ($content['links']): ?>
                    <div class="atlas-links">
                        <?php foreach ($content['links'] as $link): ?>
                            <?php $href = safe_href($link['url']); ?>
                            <?php if ($href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener"><?= e($link['label']) ?> ↗</a><?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="atlas-foot" id="contact">
        <div class="atlas-foot__top">
            <p class="tpl-eyebrow tpl-eyebrow--light">Commissions &amp; prints</p>
            <?php if ($content['email'] !== ''): ?>
                <a class="atlas-mail" href="mailto:<?= e($content['email']) ?>"><?= e($content['email']) ?></a>
            <?php else: ?>
                <p class="atlas-mail"><?= e($site['display_name']) ?></p>
            <?php endif; ?>
        </div>
        <div class="atlas-foot__bottom">
            <span><?= e($content['location']) ?></span>
            <span>© <?= date('Y') ?> <?= e($site['display_name']) ?></span>
            <span>Made on Portside</span>
        </div>
    </footer>
</div>
<?php require __DIR__ . '/_badge.php'; ?>
</body>
</html>
