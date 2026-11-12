<?php if (empty($embed) && empty($preview)): ?>
    <a class="ps-badge" href="<?= url('/') ?>" target="_blank" rel="noopener">
        <?= brand_mark(14, 'ps-badge__mark') ?>
        <span>Made with Portside</span>
    </a>
<?php endif; ?>
