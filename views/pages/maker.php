<?php
$isSelf = is_logged_in() && (int) current_user()['id'] === (int) $maker['id'];
?>
<div class="maker-hero">
    <div class="wrap maker-hero__grid">
        <div>
            <div class="maker-hero__avatar">
                <?php $avatarSize = 64; $user = $maker; require APP_ROOT . '/views/partials/avatar.php'; ?>
                <div>
                    <h1><?= e($maker['display_name']) ?></h1>
                    <p class="maker-hero__handle">@<?= e($maker['username']) ?></p>
                </div>
            </div>
            <?php if ($maker['bio'] !== ''): ?>
                <p class="maker-hero__bio"><?= e($maker['bio']) ?></p>
            <?php endif; ?>
            <div class="maker-hero__meta">
                <?php if ($maker['location'] !== ''): ?>
                    <span><?= icon('globe', 13) ?> <?= e($maker['location']) ?></span>
                <?php endif; ?>
                <span><?= icon('clock', 13) ?> Joined <?= format_date($maker['created_at'], 'F Y') ?></span>
                <?php if ($maker['website'] !== ''): ?>
                    <?php $siteHref = safe_href($maker['website']); ?>
                    <?php if ($siteHref): ?><a href="<?= e($siteHref) ?>" target="_blank" rel="noopener"><?= icon('external', 13) ?> <?= e(preg_replace('#^https?://#', '', $maker['website'])) ?></a><?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <?php if ($isSelf): ?>
                <div class="side-card">
                    <p class="side-card__label">This is your public page</p>
                    <h3>Looking good.</h3>
                    <p>Anyone with this link can see your published pages. Drafts stay private.</p>
                    <div class="side-card__links">
                        <a href="<?= url('/settings') ?>"><?= icon('settings', 15) ?> Edit your profile</a>
                        <a href="<?= url('/dashboard') ?>"><?= icon('layout', 15) ?> Back to the studio</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="side-card">
                    <p class="side-card__label">On Portside</p>
                    <h3><?= count($sites) ?> published <?= count($sites) === 1 ? 'page' : 'pages' ?></h3>
                    <p>Every page here is a working site. Open one, then go looking for the next.</p>
                    <div class="side-card__links">
                        <a href="<?= url('/explore') ?>"><?= icon('arrow-right', 15) ?> More makers</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<section class="wrap">
    <?php if ($sites): ?>
        <div class="maker-sites">
            <?php foreach ($sites as $site): ?>
                <a class="maker-site" href="<?= url('/p/' . $site['slug']) ?>">
                    <div class="maker-site__frame">
                        <iframe src="<?= url('/p/' . $site['slug'] . '?embed=1') ?>"
                                title="Preview of <?= e($site['title']) ?>"
                                loading="lazy" tabindex="-1" aria-hidden="true"
                                sandbox="allow-scripts allow-forms allow-popups allow-modals"></iframe>
                    </div>
                    <div class="maker-site__meta">
                        <h2><?= e($site['title']) ?></h2>
                        <p><?= e($site['mode'] === 'code' ? 'Hand-coded' : template_or_default($site['template'])['name']) ?> · <?= human_number((int) $site['views']) ?> views</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="margin: 48px 0 80px;">
            <h2>Nothing published yet.</h2>
            <p><?= e($maker['display_name']) ?> has not put a page in the water. There is plenty of other work to see.</p>
            <a class="btn btn--primary" href="<?= url('/explore') ?>">Explore the harbour</a>
        </div>
    <?php endif; ?>
</section>
