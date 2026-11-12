<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1B365D">
    <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/site.css') ?>">
</head>
<body class="app-body <?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to content</a>
<?php
$appUser = $user ?? null;
?>
<header class="app-head">
    <div class="app-head__inner">
        <div class="app-head__left">
            <a class="brand brand--app" href="<?= url('/dashboard') ?>" aria-label="Portside studio">
                <?= brand_mark(22) ?>
                <span class="brand__word">Portside</span>
                <span class="app-head__tag">Studio</span>
            </a>
            <nav class="app-nav" aria-label="Studio">
                <a href="<?= url('/dashboard') ?>" <?= route_path() === '/dashboard' ? 'aria-current="page"' : '' ?>>Your pages</a>
                <a href="<?= url('/explore') ?>">Explore</a>
                <a href="<?= url('/templates') ?>">Templates</a>
            </nav>
        </div>
        <div class="app-head__right">
            <a class="btn btn--sm btn--outline app-head__new" href="<?= url('/dashboard/new') ?>"><?= icon('plus', 15) ?> New page</a>
            <?php if ($appUser): ?>
                <details class="user-menu user-menu--app">
                    <summary class="user-menu__trigger" aria-label="Your account">
                        <?php $avatarSize = 30; require APP_ROOT . '/views/partials/avatar.php'; ?>
                        <span class="user-menu__name"><?= e($appUser['display_name']) ?></span>
                        <?= icon('chevron-down', 14, 'user-menu__chev') ?>
                    </summary>
                    <div class="user-menu__panel">
                        <p class="user-menu__handle">@<?= e($appUser['username']) ?></p>
                        <a href="<?= url('/u/' . $appUser['username']) ?>"><?= icon('globe', 16) ?> Public profile</a>
                        <a href="<?= url('/settings') ?>"><?= icon('settings', 16) ?> Account settings</a>
                        <a href="<?= url('/docs/code') ?>"><?= icon('code', 16) ?> Code guide</a>
                        <form method="post" action="<?= url('/logout') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="user-menu__logout"><?= icon('log-out', 16) ?> Log out</button>
                        </form>
                    </div>
                </details>
            <?php endif; ?>
        </div>
    </div>
</header>

<main id="main" class="app-main">
<div class="wrap app-shell">
    <?php if ($flashes): ?>
        <div class="flash-stack flash-stack--app">
            <?php foreach ($flashes as $flash): ?>
                <div class="flash flash--<?= e($flash['type']) ?>" role="status">
                    <span class="flash__icon"><?= icon($flash['type'] === 'error' ? 'x' : ($flash['type'] === 'success' ? 'check' : 'arrow-right'), 16) ?></span>
                    <p><?= e($flash['message']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
</div>
</main>

<footer class="app-foot">
    <div class="wrap app-foot__inner">
        <p>Portside Studio · open beta</p>
        <p><a href="<?= url('/') ?>">Back to portside</a> · <a href="<?= url('/docs/code') ?>">Code guide</a></p>
    </div>
</footer>
<script src="<?= asset('js/site.js') ?>" defer></script>
<?= $scripts ?? '' ?>
</body>
</html>
