<?php
declare(strict_types=1);

/** Base URL path of the installation ('' at domain root, '/porto' in a subfolder). */
function base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }
    $env = getenv('APP_BASE');
    if ($env !== false) {
        return $base = rtrim($env, '/');
    }
    if (PHP_SAPI === 'cli-server') {
        return $base = '';
    }
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    return $base = ($dir === '/' || $dir === '.' || $dir === '') ? '' : rtrim($dir, '/');
}

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return base_path() . ($path === '' ? '/' : '/' . $path);
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function upload_url(string $path): string
{
    $path = ltrim($path, '/');
    return $path === '' ? '' : url($path);
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path, int $status = 302): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)), true, $status);
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function request_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}

function current_url(): string
{
    return absolute_url(request_path());
}

/** Request path with the installation's base path removed. */
function route_path(): string
{
    $path = request_path();
    $base = base_path();
    if ($base !== '' && ($path === $base || str_starts_with($path, $base . '/'))) {
        $path = substr($path, strlen($base));
    }
    $path = '/' . trim($path, '/');
    return $path;
}

function absolute_url(string $path = ''): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    if (str_starts_with($path, 'http')) {
        return $path;
    }
    return $scheme . '://' . $host . url($path);
}

function query(string $key, ?string $default = null): ?string
{
    $value = $_GET[$key] ?? null;
    return is_string($value) ? trim($value) : $default;
}

function post(string $key, ?string $default = null): ?string
{
    $value = $_POST[$key] ?? null;
    return is_string($value) ? trim($value) : $default;
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function request_json(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Read-scoped flash message. */
function flash(string $type, string $message): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function take_flashes(): array
{
    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return is_array($messages) ? $messages : [];
}

function flash_now(string $type, string $message): string
{
    return '<div class="flash flash--' . e($type) . '" role="status"><span class="flash__dot"></span>' . e($message) . '</div>';
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    if (!is_post()) {
        return;
    }
    $sent = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($sent) || $sent === '' || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        render('pages/error', [
            'code' => 419,
            'heading' => 'That form expired.',
            'body' => 'Your session changed before the form was sent. Go back, reload the page and try again.',
        ], ['title' => 'Form expired · ' . APP_NAME, 'noindex' => true]);
        exit;
    }
}

/** Render a view inside the marketing layout (or another layout via $page['layout']). */
function render(string $viewFile, array $data = [], array $page = []): void
{
    $content = view($viewFile, $data);
    $page = array_merge([
        'title' => APP_NAME . ' — ' . APP_TAGLINE,
        'description' => APP_DESCRIPTION,
        'layout' => 'layout',
        'noindex' => false,
        'bodyClass' => '',
        'canonical' => current_url(),
        'ogType' => 'website',
        'ogImage' => absolute_url('assets/img/og.png'),
    ], $page);
    $flashes = take_flashes();
    $user = current_user();
    extract($page, EXTR_SKIP);
    require APP_ROOT . '/views/' . $page['layout'] . '.php';
}

function view(string $viewFile, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require APP_ROOT . '/views/' . $viewFile . '.php';
    return (string) ob_get_clean();
}

/** Begin buffering a controller-style page (logic and markup in one file). */
function page_start(): void
{
    ob_start();
}

/** Close the buffer and hand it to the layout. */
function page_end(array $page = []): void
{
    $content = (string) ob_get_clean();
    $page = array_merge([
        'title' => APP_NAME . ' — ' . APP_TAGLINE,
        'description' => APP_DESCRIPTION,
        'layout' => 'layout',
        'noindex' => false,
        'bodyClass' => '',
        'canonical' => current_url(),
        'ogType' => 'website',
        'ogImage' => absolute_url('assets/img/og.png'),
    ], $page);
    $flashes = take_flashes();
    $user = current_user();
    extract($page, EXTR_SKIP);
    require APP_ROOT . '/views/' . $page['layout'] . '.php';
}

function abort_page(int $code, string $heading, string $body): never
{
    http_response_code($code);
    render('pages/error', [
        'code' => $code,
        'heading' => $heading,
        'body' => $body,
    ], ['title' => $heading . ' · ' . APP_NAME, 'noindex' => true]);
    exit;
}

function now_utc(): string
{
    return gmdate('Y-m-d H:i:s');
}

function today_utc(): string
{
    return gmdate('Y-m-d');
}

function format_date(?string $utc, string $format = 'j M Y'): string
{
    if (!$utc) {
        return '—';
    }
    $date = new DateTimeImmutable($utc, new DateTimeZone('UTC'));
    return $date->setTimezone(new DateTimeZone(APP_TIMEZONE))->format($format);
}

function time_ago(?string $utc): string
{
    if (!$utc) {
        return 'never';
    }
    $then = new DateTimeImmutable($utc, new DateTimeZone('UTC'));
    $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    $seconds = max(0, $now->getTimestamp() - $then->getTimestamp());
    if ($seconds < 60) {
        return 'just now';
    }
    if ($seconds < 3600) {
        $m = (int) floor($seconds / 60);
        return $m . ' min ago';
    }
    if ($seconds < 86400) {
        $h = (int) floor($seconds / 3600);
        return $h . ($h === 1 ? ' hour ago' : ' hours ago');
    }
    if ($seconds < 86400 * 30) {
        $d = (int) floor($seconds / 86400);
        return $d . ($d === 1 ? ' day ago' : ' days ago');
    }
    return format_date($utc);
}

function human_number(int $n): string
{
    if ($n < 1000) {
        return (string) $n;
    }
    if ($n < 1000000) {
        return rtrim(rtrim(number_format($n / 1000, 1), '0'), '.') . 'k';
    }
    return rtrim(rtrim(number_format($n / 1000000, 1), '0'), '.') . 'm';
}

function slugify(string $value): string
{
    $value = mb_strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/u', '-', $value) ?? '';
    return trim($value, '-');
}

function valid_slug(string $slug): bool
{
    return (bool) preg_match('/^[a-z0-9](?:[a-z0-9-]{1,38})[a-z0-9]$/', $slug)
        && !in_array($slug, reserved_slugs(), true);
}

function valid_username(string $username): bool
{
    return (bool) preg_match('/^[a-z0-9_](?:[a-z0-9_]{1,22})[a-z0-9_]$/', $username)
        && !in_array($username, reserved_slugs(), true);
}

function reserved_slugs(): array
{
    return [
        'admin', 'api', 'login', 'logout', 'signup', 'dashboard', 'explore', 'templates',
        'settings', 'uploads', 'assets', 'storage', 'p', 'u', 'docs', 'help', 'about',
        'new', 'preview', 'export', 'sitemap', 'robots', 'health', 'portside', 'www',
        'mail', 'support', 'terms', 'privacy', 'code', 'guide', 'search',
    ];
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $letters = '';
    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }
        $letters .= mb_strtoupper(mb_substr($part, 0, 1));
        if (mb_strlen($letters) >= 2) {
            break;
        }
    }
    return $letters !== '' ? $letters : 'P';
}

/** Escape text, then turn *asterisks* into emphasis. Line breaks are preserved. */
function inline_markup(?string $text): string
{
    $safe = e((string) $text);
    $safe = preg_replace('/\*([^*\n]+)\*/u', '<em>$1</em>', $safe) ?? $safe;
    return nl2br($safe, false);
}

function media_url(?string $path): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    if (str_starts_with($path, 'data:image/')) {
        return $path;
    }
    return url(ltrim($path, '/'));
}

/** Only allow links that cannot execute script. */
function safe_href(?string $href): string
{
    $href = trim((string) $href);
    if ($href === '') {
        return '';
    }
    if (preg_match('#^(https?://|mailto:)#i', $href)) {
        return $href;
    }
    if (str_starts_with($href, '/')) {
        return url(ltrim($href, '/'));
    }
    if (str_starts_with($href, '#')) {
        return $href;
    }
    if (preg_match('#^[a-z0-9._~-]+/?[a-z0-9._~/?=&%+-]*$#i', $href)) {
        return url($href);
    }
    return '';
}

function excerpt(?string $text, int $limit = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', (string) $text) ?? '');
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $limit - 1)) . '…';
}

function ensure_https_url(string $url): string
{
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
}

/** Simple fixed-window rate limit stored in SQLite. */
function rate_limit(string $key, int $max, int $windowSeconds): bool
{
    $db = db();
    $now = time();
    $stmt = $db->prepare('SELECT hits, reset_at FROM throttle WHERE key = ?');
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    if (!$row || (int) $row['reset_at'] <= $now) {
        $db->prepare('INSERT INTO throttle (key, hits, reset_at) VALUES (?, 1, ?)
            ON CONFLICT(key) DO UPDATE SET hits = 1, reset_at = excluded.reset_at')
            ->execute([$key, $now + $windowSeconds]);
        return true;
    }
    if ((int) $row['hits'] >= $max) {
        return false;
    }
    $db->prepare('UPDATE throttle SET hits = hits + 1 WHERE key = ?')->execute([$key]);
    return true;
}

function rate_limit_reset(string $key): void
{
    db()->prepare('DELETE FROM throttle WHERE key = ?')->execute([$key]);
}

function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function reading_time(string $text): int
{
    $words = str_word_count(strip_tags($text));
    return max(1, (int) ceil($words / 210));
}
