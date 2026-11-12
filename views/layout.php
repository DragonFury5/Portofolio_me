<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <?php if ($noindex): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php else: ?>
        <link rel="canonical" href="<?= e($canonical) ?>">
        <meta property="og:title" content="<?= e($title) ?>">
        <meta property="og:description" content="<?= e($description) ?>">
        <meta property="og:type" content="<?= e($ogType) ?>">
        <meta property="og:url" content="<?= e($canonical) ?>">
        <meta property="og:image" content="<?= e($ogImage) ?>">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?= e($title) ?>">
        <meta name="twitter:description" content="<?= e($description) ?>">
        <meta name="twitter:image" content="<?= e($ogImage) ?>">
    <?php endif; ?>
    <meta name="theme-color" content="#1B365D">
    <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/site.css') ?>">
</head>
<body class="site-body <?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to content</a>
<?php require APP_ROOT . '/views/partials/header.php'; ?>

<main id="main">
    <?php if ($flashes): ?>
        <div class="wrap flash-stack">
            <?php foreach ($flashes as $flash): ?>
                <div class="flash flash--<?= e($flash['type']) ?>" role="status">
                    <span class="flash__icon"><?= icon($flash['type'] === 'error' ? 'x' : ($flash['type'] === 'success' ? 'check' : 'arrow-right'), 16) ?></span>
                    <p><?= e($flash['message']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>

<?php require APP_ROOT . '/views/partials/footer.php'; ?>
<script src="<?= asset('js/site.js') ?>" defer></script>
</body>
</html>
