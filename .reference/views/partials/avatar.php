<?php
$u = $user ?? null;
$size = $avatarSize ?? 32;
$name = $u['display_name'] ?? 'Portside';
$tone = $u ? ((abs(crc32((string) $u['username'])) % 4) + 1) : 1;
$src = $u['avatar'] ?? '';
?>
<?php if ($src !== ''): ?>
    <img class="avatar" src="<?= e(media_url($src)) ?>" width="<?= (int) $size ?>" height="<?= (int) $size ?>"
         alt="<?= e($name) ?>" loading="lazy" decoding="async"
         style="--avatar-size:<?= (int) $size ?>px">
<?php else: ?>
    <span class="avatar avatar--tone-<?= (int) $tone ?>" aria-hidden="true"
          style="--avatar-size:<?= (int) $size ?>px"><?= e(initials($name)) ?></span>
<?php endif; ?>
