<?php
declare(strict_types=1);

/** Site queries and mutations. */

const CODE_LIMITS = ['html' => 300000, 'css' => 200000, 'js' => 200000];

function find_site_by_slug(string $slug): ?array
{
    $stmt = db()->prepare(
        'SELECT s.*, u.username, u.display_name, u.avatar, u.bio AS maker_bio
         FROM sites s JOIN users u ON u.id = s.user_id
         WHERE s.slug = ? LIMIT 1'
    );
    $stmt->execute([$slug]);
    $site = $stmt->fetch();
    return $site ?: null;
}

function find_site(int $id): ?array
{
    $stmt = db()->prepare(
        'SELECT s.*, u.username, u.display_name, u.avatar
         FROM sites s JOIN users u ON u.id = s.user_id WHERE s.id = ? LIMIT 1'
    );
    $stmt->execute([$id]);
    $site = $stmt->fetch();
    return $site ?: null;
}

function owned_site(int $id): array
{
    $user = require_login();
    $site = find_site($id);
    if (!$site || (int) $site['user_id'] !== (int) $user['id']) {
        abort_page(404, 'No page here.', 'This page either does not exist or belongs to someone else.');
    }
    return $site;
}

function user_sites(int $userId): array
{
    $stmt = db()->prepare(
        'SELECT s.*,
            COALESCE((SELECT SUM(v.count) FROM site_views v
                      WHERE v.site_id = s.id AND v.day >= date("now", "-13 days")), 0) AS views_14d
         FROM sites s WHERE s.user_id = ? ORDER BY s.updated_at DESC'
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function user_site_count(int $userId): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM sites WHERE user_id = ?');
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}

function user_total_views(int $userId): int
{
    $stmt = db()->prepare(
        'SELECT COALESCE(SUM(v.count), 0) FROM site_views v
         JOIN sites s ON s.id = v.site_id WHERE s.user_id = ? AND v.day >= date("now", "-13 days")'
    );
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}

function unique_site_slug(string $seed): string
{
    $base = slugify($seed);
    if ($base === '') {
        $base = 'portfolio';
    }
    $base = mb_substr($base, 0, 32);
    $candidate = $base;
    $i = 2;
    while (!valid_slug($candidate) || find_site_by_slug($candidate)) {
        $candidate = $base . '-' . $i;
        $i++;
        if ($i > 99) {
            $candidate = $base . '-' . bin2hex(random_bytes(3));
            break;
        }
    }
    return $candidate;
}

function create_site(int $userId, string $mode, string $template, string $starter = 'blank'): int
{
    $userStmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch();
    if (!$user) {
        throw new RuntimeException('Unknown user.');
    }

    $now = now_utc();
    $title = $user['display_name'] . ' — Portfolio';
    $slug = unique_site_slug($user['username']);
    $html = $css = $js = '';
    $content = '{}';

    if ($mode === 'code') {
        $kit = code_starter($starter);
        $html = $kit['html'];
        $css = $kit['css'];
        $js = $kit['js'];
        $template = 'code';
        $mode = 'code';
    } else {
        $template = array_key_exists($template, portfolio_templates()) ? $template : 'ledger';
        $content = json_encode(starter_content($template, $user['display_name']), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $mode = 'template';
    }

    $stmt = db()->prepare(
        'INSERT INTO sites (user_id, slug, title, description, mode, template, content_json, html, css, js, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $userId, $slug, $title, '', $mode, $template, $content, $html, $css, $js, $now, $now,
    ]);
    return (int) db()->lastInsertId();
}

function update_site_settings(array $site, array $input): array
{
    $title = trim((string) ($input['title'] ?? ''));
    $slug = mb_strtolower(trim((string) ($input['slug'] ?? '')));
    $description = trim((string) ($input['description'] ?? ''));
    $template = (string) ($input['template'] ?? $site['template']);
    $accent = (string) ($input['accent'] ?? 'blue');

    if (mb_strlen($title) < 2 || mb_strlen($title) > 80) {
        return [false, 'Give the page a title between 2 and 80 characters.'];
    }
    if (!valid_slug($slug)) {
        return [false, 'The link uses 3–40 lowercase letters, numbers or dashes. It cannot be a word we reserve.'];
    }
    if (mb_strlen($description) > 200) {
        return [false, 'Keep the description under 200 characters.'];
    }
    if (!in_array($accent, ['blue', 'cyan', 'pale', 'navy'], true)) {
        $accent = 'blue';
    }
    if ($site['mode'] === 'template' && !array_key_exists($template, portfolio_templates())) {
        return [false, 'Pick one of the available templates.'];
    }

    $stmt = db()->prepare('SELECT id FROM sites WHERE slug = ? AND id != ?');
    $stmt->execute([$slug, $site['id']]);
    if ($stmt->fetch()) {
        return [false, 'That link is already used by another page. Try a different one.'];
    }

    $content = site_content($site);
    $content['accent'] = $accent;
    if ($site['mode'] === 'template') {
        $content['_template'] = $template;
    }

    db()->prepare(
        'UPDATE sites SET title = ?, slug = ?, description = ?, template = ?, content_json = ?, updated_at = ? WHERE id = ?'
    )->execute([
        $title, $slug, $description,
        $site['mode'] === 'template' ? $template : $site['template'],
        json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        now_utc(), $site['id'],
    ]);

    return [true, '', $slug];
}

function toggle_site_published(int $siteId, bool $published): void
{
    $now = now_utc();
    if ($published) {
        db()->prepare('UPDATE sites SET is_published = 1, published_at = COALESCE(published_at, ?), updated_at = ? WHERE id = ?')
            ->execute([$now, $now, $siteId]);
    } else {
        db()->prepare('UPDATE sites SET is_published = 0, updated_at = ? WHERE id = ?')
            ->execute([$now, $siteId]);
    }
}

function delete_site(int $siteId): void
{
    db()->prepare('DELETE FROM sites WHERE id = ?')->execute([$siteId]);
}

function site_path(array $site): string
{
    return '/p/' . $site['slug'];
}

function maker_path(array $user): string
{
    return '/u/' . $user['username'];
}

function site_public_url(array $site): string
{
    return absolute_url(site_path($site));
}

function site_content(array $site): array
{
    $decoded = json_decode((string) ($site['content_json'] ?? ''), true);
    return is_array($decoded) ? $decoded : [];
}

function site_accent(array $site): string
{
    $content = site_content($site);
    $accent = (string) ($content['accent'] ?? 'blue');
    return in_array($accent, ['blue', 'cyan', 'pale', 'navy'], true) ? $accent : 'blue';
}

function site_cover(array $site, string $fallback = 'assets/img/seed/tidal-index.svg'): string
{
    $content = site_content($site);
    foreach (($content['projects'] ?? []) as $project) {
        if (!empty($project['image']) && is_string($project['image'])) {
            return $project['image'];
        }
    }
    return $fallback;
}

function site_projects(array $site): array
{
    $content = site_content($site);
    return is_array($content['projects'] ?? null) ? $content['projects'] : [];
}

function project_count(array $site): int
{
    return count(array_filter(site_projects($site), static fn ($p) => trim((string) ($p['title'] ?? '')) !== ''));
}

/** Published sites for /explore. */
function public_sites(array $filters = [], int $limit = 12, int $offset = 0): array
{
    $where = ['s.is_published = 1'];
    $params = [];

    $mode = $filters['mode'] ?? '';
    if (in_array($mode, ['template', 'code'], true)) {
        $where[] = 's.mode = ?';
        $params[] = $mode;
    }

    $template = $filters['template'] ?? '';
    if ($template !== '' && array_key_exists($template, portfolio_templates())) {
        $where[] = 's.template = ?';
        $params[] = $template;
    }

    $q = trim((string) ($filters['q'] ?? ''));
    if ($q !== '') {
        $where[] = '(s.title LIKE ? OR s.description LIKE ? OR u.display_name LIKE ? OR u.username LIKE ? OR s.slug LIKE ?)';
        $like = '%' . $q . '%';
        array_push($params, $like, $like, $like, $like, $like);
    }

    $order = ($filters['sort'] ?? '') === 'popular' ? 's.views DESC, s.updated_at DESC' : 's.updated_at DESC';

    $sql = 'SELECT s.*, u.username, u.display_name, u.avatar
            FROM sites s JOIN users u ON u.id = s.user_id
            WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . $order . ' LIMIT ? OFFSET ?';
    $stmt = db()->prepare($sql);
    $i = 1;
    foreach ($params as $param) {
        $stmt->bindValue($i++, $param, PDO::PARAM_STR);
    }
    $stmt->bindValue($i++, $limit, PDO::PARAM_INT);
    $stmt->bindValue($i, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function public_site_count(array $filters = []): int
{
    $where = ['s.is_published = 1'];
    $params = [];
    $mode = $filters['mode'] ?? '';
    if (in_array($mode, ['template', 'code'], true)) {
        $where[] = 's.mode = ?';
        $params[] = $mode;
    }
    $template = $filters['template'] ?? '';
    if ($template !== '' && array_key_exists($template, portfolio_templates())) {
        $where[] = 's.template = ?';
        $params[] = $template;
    }
    $q = trim((string) ($filters['q'] ?? ''));
    if ($q !== '') {
        $where[] = '(s.title LIKE ? OR s.description LIKE ? OR u.display_name LIKE ? OR u.username LIKE ? OR s.slug LIKE ?)';
        $like = '%' . $q . '%';
        array_push($params, $like, $like, $like, $like, $like);
    }
    $sql = 'SELECT COUNT(*) FROM sites s JOIN users u ON u.id = s.user_id WHERE ' . implode(' AND ', $where);
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function featured_sites(int $limit = 3): array
{
    $stmt = db()->prepare(
        'SELECT s.*, u.username, u.display_name, u.avatar
         FROM sites s JOIN users u ON u.id = s.user_id
         WHERE s.is_published = 1 AND s.is_featured = 1
         ORDER BY s.updated_at DESC LIMIT ?'
    );
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    if (count($rows) < $limit) {
        $exclude = array_map(static fn ($r) => (int) $r['id'], $rows);
        $sql = 'SELECT s.*, u.username, u.display_name, u.avatar
                FROM sites s JOIN users u ON u.id = s.user_id
                WHERE s.is_published = 1';
        if ($exclude) {
            $sql .= ' AND s.id NOT IN (' . implode(',', array_fill(0, count($exclude), '?')) . ')';
        }
        $sql .= ' ORDER BY s.views DESC LIMIT ?';
        $more = db()->prepare($sql);
        $i = 1;
        foreach ($exclude as $id) {
            $more->bindValue($i++, $id, PDO::PARAM_INT);
        }
        $more->bindValue($i, $limit - count($rows), PDO::PARAM_INT);
        $more->execute();
        $rows = array_merge($rows, $more->fetchAll());
    }
    return $rows;
}

function published_user_sites(int $userId): array
{
    $stmt = db()->prepare(
        'SELECT * FROM sites WHERE user_id = ? AND is_published = 1 ORDER BY updated_at DESC'
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function find_user_by_username(string $username): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([mb_strtolower($username)]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function user_site_limit(array $user): int
{
    return 4;
}

/** Count a public page view, once per visitor session per day. */
function record_site_view(array $site): void
{
    $id = (int) $site['id'];
    $today = today_utc();
    if (isset($_SESSION['seen_sites'][$id]) && $_SESSION['seen_sites'][$id] === $today) {
        return;
    }
    $_SESSION['seen_sites'][$id] = $today;

    $pdo = db();
    $pdo->prepare('UPDATE sites SET views = views + 1 WHERE id = ?')->execute([$id]);
    $pdo->prepare(
        'INSERT INTO site_views (site_id, day, count) VALUES (?, ?, 1)
         ON CONFLICT(site_id, day) DO UPDATE SET count = count + 1'
    )->execute([$id, $today]);
}

/** Daily view counts for the last N days, zero-filled. */
function site_views_series(int $siteId, int $days = 14): array
{
    $start = (new DateTimeImmutable('-' . ($days - 1) . ' days', new DateTimeZone('UTC')))->format('Y-m-d');
    $stmt = db()->prepare('SELECT day, count FROM site_views WHERE site_id = ? AND day >= ? ORDER BY day');
    $stmt->execute([$siteId, $start]);
    $found = [];
    foreach ($stmt->fetchAll() as $row) {
        $found[$row['day']] = (int) $row['count'];
    }
    $series = [];
    for ($i = $days - 1; $i >= 0; $i--) {
        $day = (new DateTimeImmutable('-' . $i . ' days', new DateTimeZone('UTC')))->format('Y-m-d');
        $series[$day] = $found[$day] ?? 0;
    }
    return $series;
}

function user_views_series(int $userId, int $days = 14): array
{
    $start = (new DateTimeImmutable('-' . ($days - 1) . ' days', new DateTimeZone('UTC')))->format('Y-m-d');
    $stmt = db()->prepare(
        'SELECT v.day, SUM(v.count) AS count FROM site_views v
         JOIN sites s ON s.id = v.site_id
         WHERE s.user_id = ? AND v.day >= ? GROUP BY v.day'
    );
    $stmt->execute([$userId, $start]);
    $found = [];
    foreach ($stmt->fetchAll() as $row) {
        $found[$row['day']] = (int) $row['count'];
    }
    $series = [];
    for ($i = $days - 1; $i >= 0; $i--) {
        $day = (new DateTimeImmutable('-' . $i . ' days', new DateTimeZone('UTC')))->format('Y-m-d');
        $series[$day] = $found[$day] ?? 0;
    }
    return $series;
}

function save_site_content(int $siteId, array $content): void
{
    db()->prepare('UPDATE sites SET content_json = ?, updated_at = ? WHERE id = ?')
        ->execute([json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), now_utc(), $siteId]);
}

function save_site_code(int $siteId, string $html, string $css, string $js): array
{
    foreach (CODE_LIMITS as $part => $limit) {
        $value = ${$part};
        if (strlen($value) > $limit) {
            return [false, 'Your ' . strtoupper($part) . ' is over the ' . round($limit / 1000) . ' KB limit. Trim it a little.'];
        }
    }
    db()->prepare('UPDATE sites SET html = ?, css = ?, js = ?, updated_at = ? WHERE id = ?')
        ->execute([$html, $css, $js, now_utc(), $siteId]);
    return [true, ''];
}

function validate_image_upload(array $file, int $maxBytes = 5_000_000): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return [false, 'The image did not upload. Try again.'];
    }
    if (($file['size'] ?? 0) > $maxBytes) {
        return [false, 'Keep images under ' . round($maxBytes / 1_000_000) . ' MB.'];
    }
    $tmp = $file['tmp_name'] ?? '';
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return [false, 'The upload could not be verified.'];
    }
    $info = @getimagesize($tmp);
    if (!$info) {
        return [false, 'That file is not a readable image.'];
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        return [false, 'Use a JPG, PNG or WebP image.'];
    }
    return [true, '', $tmp, $allowed[$mime], $info];
}

/** Re-encode uploads through GD so embedded payloads and odd metadata are dropped. */
function store_image(array $file, string $folder, int $maxWidth = 1600): array
{
    [$ok, $error, $tmp, $ext, $info] = validate_image_upload($file);
    if (!$ok) {
        return [false, $error];
    }

    [$width, $height] = $info;
    $mime = $info['mime'] ?? '';
    $source = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($tmp),
        'image/png' => @imagecreatefrompng($tmp),
        'image/webp' => @imagecreatefromwebp($tmp),
        default => false,
    };
    if (!$source) {
        return [false, 'We could not process that image. Try exporting it again.'];
    }

    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = (int) round($height * ($maxWidth / $width));
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);
        $source = $resized;
    }

    $folder = preg_replace('/[^a-z0-9_-]/i', '', $folder) ?: 'misc';
    $dir = UPLOAD_DIR . '/' . $folder;
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $name = bin2hex(random_bytes(12)) . '.' . ($ext === 'png' ? 'png' : ($ext === 'webp' ? 'webp' : 'jpg'));
    $target = $dir . '/' . $name;

    $saved = match ($ext) {
        'png' => imagepng($source, $target, 6),
        'webp' => imagewebp($source, $target, 84),
        default => imagejpeg($source, $target, 86),
    };
    imagedestroy($source);
    if (!$saved) {
        return [false, 'The image could not be saved. Try a smaller file.'];
    }

    return [true, '', 'uploads/' . $folder . '/' . $name];
}
