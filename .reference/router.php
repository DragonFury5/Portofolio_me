<?php
declare(strict_types=1);

/**
 * Router for PHP's built-in server:
 *   php -S 127.0.0.1:8080 router.php
 * Apache uses .htaccess and index.php instead.
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Only expose the two public asset folders as plain files.
if (preg_match('#^/(assets|uploads)/#', $path)) {
    $file = __DIR__ . $path;
    if (is_file($file)) {
        return false;
    }
    http_response_code(404);
    echo 'Not found';
    return true;
}

require __DIR__ . '/index.php';
