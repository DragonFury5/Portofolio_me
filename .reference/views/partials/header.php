<?php
$path = route_path();
$navUser = $user ?? null;
?>
<header class="site-head">
    <div class="wrap site-head__inner">
        <a class="brand" href="<?= url('/') ?>" aria-label="Portside — home">
            <?= brand_mark(26) ?>
            <span class="brand__word">Portside</span>
        </a>

        <nav class="site-nav" aria-label="Main">
            <a href="<?= url('/explore') ?>" <?= $path === '/explore' ? 'aria-current="page"' : '' ?>>Explore</a>
            <a href="<?= url('/templates') ?>" <?= $path === '/templates' ? 'aria-current="page"' : '' ?>>Templates</a>
            <a href="<?= url('/docs/code') ?>" <?= $path === '/docs/code' ? 'aria-current="page"' : '' ?>>Code guide</a>
            <a href="<?= url('/#faq') ?>">FAQ</a>
        </nav>

        <div class="head-actions">
            <?php if ($navUser): ?>
                <a class="btn btn--sm btn--outline head-studio" href="<?= url('/dashboard') ?>">Studio <?= icon('arrow-right', 16) ?></a>
                <details class="user-menu">
                    <summary class="user-menu__trigger" aria-label="Your account">
                        <?php $avatarSize = 30; require APP_ROOT . '/views/partials/avatar.php'; ?>
                        <span class="user-menu__name"><?= e($navUser['display_name']) ?></span>
                        <?= icon('chevron-down', 14, 'user-menu__chev') ?>
                    </summary>
                    <div class="user-menu__panel">
                        <p class="user-menu__handle">@<?= e($navUser['username']) ?></p>
                        <a href="<?= url('/dashboard') ?>"><?= icon('layout', 16) ?> Studio</a>
                        <a href="<?= url('/u/' . $navUser['username']) ?>"><?= icon('globe', 16) ?> Public profile</a>
                        <a href="<?= url('/settings') ?>"><?= icon('settings', 16) ?> Settings</a>
                        <form method="post" action="<?= url('/logout') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="user-menu__logout"><?= icon('log-out', 16) ?> Log out</button>
                        </form>
                    </div>
                </details>
            <?php else: ?>
                <a class="head-login" href="<?= url('/login') ?>">Log in</a>
                <a class="btn btn--sm btn--outline" href="<?= url('/signup') ?>">Start free</a>
            <?php endif; ?>
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu">
                <?= icon('menu', 22) ?>
            </button>
        </div>
    </div>
</header>

<div class="mobile-nav" id="mobile-nav" hidden>
    <div class="wrap">
        <nav class="mobile-nav__links" aria-label="Mobile">
            <a href="<?= url('/explore') ?>">Explore</a>
            <a href="<?= url('/templates') ?>">Templates</a>
            <a href="<?= url('/docs/code') ?>">Code guide</a>
            <a href="<?= url('/#faq') ?>">FAQ</a>
            <?php if ($navUser): ?>
                <a href="<?= url('/dashboard') ?>">Studio</a>
                <a href="<?= url('/u/' . $navUser['username']) ?>">Public profile</a>
                <a href="<?= url('/settings') ?>">Settings</a>
            <?php endif; ?>
        </nav>
        <div class="mobile-nav__actions">
            <?php if ($navUser): ?>
                <form method="post" action="<?= url('/logout') ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn--outline btn--block" type="submit">Log out</button>
                </form>
            <?php else: ?>
                <a class="btn btn--outline btn--block" href="<?= url('/login') ?>">Log in</a>
                <a class="btn btn--primary btn--block" href="<?= url('/signup') ?>">Start a portfolio</a>
            <?php endif; ?>
        </div>
    </div>
</div>
