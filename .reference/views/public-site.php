<?php
$site = find_site_by_slug($p['slug']);
if (!$site) {
    abort_page(404, 'No page at this address.', 'The link may be wrong, or the maker may have taken the page down.');
}

$viewer = current_user();
$isOwner = $viewer && (int) $viewer['id'] === (int) $site['user_id'];
if (!$site['is_published'] && !$isOwner) {
    abort_page(404, 'No page at this address.', 'The link may be wrong, or the maker may have taken the page down.');
}

$isEmbed = isset($_GET['embed']);
$isPreview = !$site['is_published'] && $isOwner;

if (!$isEmbed && !$isPreview) {
    record_site_view($site);
}

if ($isPreview) {
    header('Cache-Control: no-store');
}

if ($site['mode'] === 'code') {
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Security-Policy: sandbox allow-scripts allow-forms allow-popups allow-modals allow-downloads allow-top-navigation-by-user-activation');
    header('X-Robots-Tag: noindex');
    echo render_code_site($site);
    exit;
}

$html = render_template_site($site, $isEmbed, $isPreview);

if ($isPreview && !$isEmbed) {
    $bar = '<div class="ps-draft-bar">'
        . '<strong>Draft preview</strong>'
        . '<span>Only you can see this page.</span>'
        . '<a href="' . e(url('/dashboard/site/' . $site['id'] . '/settings')) . '">Settings</a>'
        . '<form method="post" action="' . e(url('/dashboard/site/' . $site['id'] . '/publish')) . '">'
        . csrf_field()
        . '<input type="hidden" name="published" value="1">'
        . '<button type="submit">Publish now</button>'
        . '</form>'
        . '</div>';
    $html = preg_replace('/<body([^>]*)>/i', '<body$1>' . $bar, $html, 1) ?? $html;
}

header('Content-Type: text/html; charset=utf-8');
echo $html;
