<?php
$isPreview = $preview ?? false;
$isEmbed = $embed ?? false;
$canonical = absolute_url(site_path($site));
$metaDescription = trim((string) $site['description']);
if ($metaDescription === '') {
    $metaDescription = excerpt((string) ($content['intro'] ?? ''), 160) ?: 'A portfolio made with Portside.';
}
$ogImage = '';
foreach (($content['projects'] ?? []) as $project) {
    if (!empty($project['image'])) {
        $ogImage = absolute_url(ltrim((string) $project['image'], '/'));
        break;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <?php if ($isPreview || $isEmbed): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php else: ?>
        <link rel="canonical" href="<?= e($canonical) ?>">
        <meta property="og:title" content="<?= e($pageTitle) ?>">
        <meta property="og:description" content="<?= e($metaDescription) ?>">
        <meta property="og:type" content="profile">
        <meta property="og:url" content="<?= e($canonical) ?>">
        <?php if ($ogImage): ?>
            <meta property="og:image" content="<?= e($ogImage) ?>">
            <meta name="twitter:card" content="summary_large_image">
        <?php endif; ?>
    <?php endif; ?>
    <meta name="theme-color" content="#1B365D">
    <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/portfolio.css') ?>">
</head>
<body class="<?= e($bodyClass) ?><?= $isEmbed ? ' is-embed' : '' ?><?= $isPreview ? ' is-preview' : '' ?>">
