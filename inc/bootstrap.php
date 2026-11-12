<?php
declare(strict_types=1);

/**
 * Portside — application bootstrap.
 * Plain PHP 8 + SQLite. No build step, no dependency manager required.
 */

const APP_NAME = 'Portside';
const APP_TAGLINE = 'Your work, at home.';
const APP_DESCRIPTION = 'A portfolio host for people who would rather build their own page. Start from a template or write the HTML, CSS and JS yourself.';
const APP_VERSION = '0.9.0';
const APP_TIMEZONE = 'Asia/Jakarta';

define('APP_ROOT', dirname(__DIR__));
define('STORAGE_DIR', APP_ROOT . '/storage');
define('UPLOAD_DIR', APP_ROOT . '/uploads');
define('UPLOAD_URL', '/uploads');

date_default_timezone_set(APP_TIMEZONE);
mb_internal_encoding('UTF-8');

$debug = getenv('APP_DEBUG') !== '0';
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/helpers.php';

if (!is_dir(STORAGE_DIR)) {
    @mkdir(STORAGE_DIR, 0775, true);
}
if (!is_dir(UPLOAD_DIR)) {
    @mkdir(UPLOAD_DIR, 0775, true);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('portside_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => base_path() ?: '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/sites.php';
require __DIR__ . '/templates.php';
require __DIR__ . '/code-starters.php';
require __DIR__ . '/icons.php';
