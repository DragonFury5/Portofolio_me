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
<body class="auth-body <?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to content</a>

<header class="auth-head">
    <div class="wrap auth-head__inner">
        <a class="brand" href="<?= url('/') ?>" aria-label="Portside — home">
            <?= brand_mark(24) ?>
            <span class="brand__word">Portside</span>
        </a>
        <a class="auth-head__back" href="<?= url('/') ?>"><?= icon('arrow-left', 15) ?> Back to the harbour</a>
    </div>
</header>

<main id="main" class="auth-main">
    <div class="auth-grid">
        <aside class="auth-aside" aria-label="About Portside">
            <div class="auth-aside__inner">
                <p class="eyebrow eyebrow--light">A page is a place</p>
                <p class="auth-aside__quote">“Your portfolio should look like it was made by a person, not a platform.”</p>
                <ul class="auth-aside__list">
                    <li><?= icon('layout', 18) ?><span>Four templates that don’t look like templates.</span></li>
                    <li><?= icon('code', 18) ?><span>Or write every line of HTML, CSS and JS yourself.</span></li>
                    <li><?= icon('shield', 18) ?><span>Your code runs sandboxed. Your account stays yours.</span></li>
                </ul>
                <p class="auth-aside__foot">Open beta · free while we’re small</p>
            </div>
            <svg class="auth-aside__art" viewBox="0 0 420 180" fill="none" aria-hidden="true">
                <path d="M6 138c62-38 98 12 154-8s84-54 144-39 74 47 112 31" stroke="#60C1E5" stroke-width="2" stroke-dasharray="5 7" opacity=".55"/>
                <circle cx="76" cy="82" r="34" stroke="#A0E0EF" stroke-width="1.4"/>
                <circle cx="76" cy="82" r="12" fill="#A0E0EF"/>
                <path d="M276 36v96M276 36h52a26 26 0 0 1 0 52h-36" stroke="#A0E0EF" stroke-width="2"/>
                <path d="M258 132h104" stroke="#60C1E5" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </aside>

        <section class="auth-panel">
            <?php if ($flashes): ?>
                <div class="flash-stack flash-stack--auth">
                    <?php foreach ($flashes as $flash): ?>
                        <div class="flash flash--<?= e($flash['type']) ?>" role="status">
                            <span class="flash__icon"><?= icon($flash['type'] === 'error' ? 'x' : ($flash['type'] === 'success' ? 'check' : 'arrow-right'), 16) ?></span>
                            <p><?= e($flash['message']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?= $content ?>
        </section>
    </div>
</main>

<footer class="auth-foot">
    <div class="wrap auth-foot__inner">
        <p>© <?= date('Y') ?> Portside</p>
        <p><a href="<?= url('/terms') ?>">Terms</a> · <a href="<?= url('/privacy') ?>">Privacy</a></p>
    </div>
</footer>
<script src="<?= asset('js/site.js') ?>" defer></script>
</body>
</html>
