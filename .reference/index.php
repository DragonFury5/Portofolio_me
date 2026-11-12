<?php
declare(strict_types=1);

require __DIR__ . '/inc/bootstrap.php';

if (is_post()) {
    csrf_verify();
}

$path = route_path();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* Routes are matched in order. Placeholders become named parameters. */
$routes = [
    ['GET', '/', static function (): void {
        require APP_ROOT . '/views/pages/home.php';
    }],

    ['GET', '/explore', static function (): void {
        require APP_ROOT . '/views/pages/explore.php';
    }],

    ['GET', '/templates', static function (): void {
        require APP_ROOT . '/views/pages/templates.php';
    }],

    ['GET', '/templates/{key}/preview', static function (array $p): void {
        $templates = portfolio_templates();
        if (!isset($templates[$p['key']])) {
            abort_page(404, 'No template with that name.', 'The template you asked for does not exist. Browse the four we make.');
        }
        $key = $p['key'];
        $meta = $templates[$key];
        $sample = [
            'id' => 0,
            'slug' => 'sample-' . $key,
            'title' => $meta['name'] . ' — sample portfolio',
            'description' => $meta['description'],
            'template' => $key,
            'display_name' => 'Sample Maker',
            'username' => 'sample',
            'avatar' => '',
            'content_json' => json_encode(starter_content($key, 'Sample Maker'), JSON_UNESCAPED_SLASHES),
            'is_published' => 1,
        ];
        header('Content-Type: text/html; charset=utf-8');
        header('X-Robots-Tag: noindex');
        echo render_template_site($sample, true, true);
        exit;
    }],

    ['GET', '/docs/code', static function (): void {
        require APP_ROOT . '/views/pages/code-guide.php';
    }],

    ['GET', '/terms', static function (): void {
        require APP_ROOT . '/views/pages/terms.php';
    }],

    ['GET', '/privacy', static function (): void {
        require APP_ROOT . '/views/pages/privacy.php';
    }],

    ['GET', '/login', static function (): void {
        if (is_logged_in()) {
            redirect('/dashboard');
        }
        require APP_ROOT . '/views/pages/login.php';
    }],

    ['POST', '/login', static function (): void {
        require APP_ROOT . '/views/pages/login.php';
    }],

    ['GET', '/signup', static function (): void {
        if (is_logged_in()) {
            redirect('/dashboard');
        }
        require APP_ROOT . '/views/pages/signup.php';
    }],

    ['POST', '/signup', static function (): void {
        require APP_ROOT . '/views/pages/signup.php';
    }],

    ['POST', '/logout', static function (): void {
        logout_user();
        flash('success', 'Logged out. Your pages are still here when you come back.');
        redirect('/');
    }],

    ['GET', '/dashboard', static function (): void {
        require APP_ROOT . '/views/app/dashboard.php';
    }],

    ['GET', '/dashboard/new', static function (): void {
        require APP_ROOT . '/views/app/new-site.php';
    }],

    ['POST', '/dashboard/new', static function (): void {
        require APP_ROOT . '/views/app/new-site.php';
    }],

    ['GET', '/dashboard/site/{id}', static function (array $p): void {
        $site = owned_site((int) $p['id']);
        redirect('/dashboard/site/' . $site['id'] . '/' . ($site['mode'] === 'code' ? 'code' : 'content'));
    }],

    ['GET', '/dashboard/site/{id}/settings', static function (array $p): void {
        require APP_ROOT . '/views/app/site-settings.php';
    }],

    ['POST', '/dashboard/site/{id}/settings', static function (array $p): void {
        require APP_ROOT . '/views/app/site-settings.php';
    }],

    ['GET', '/dashboard/site/{id}/content', static function (array $p): void {
        $site = owned_site((int) $p['id']);
        if ($site['mode'] !== 'template') {
            redirect('/dashboard/site/' . $site['id'] . '/code');
        }
        require APP_ROOT . '/views/app/site-content.php';
    }],

    ['POST', '/dashboard/site/{id}/content', static function (array $p): void {
        require APP_ROOT . '/views/app/site-content.php';
    }],

    ['GET', '/dashboard/site/{id}/code', static function (array $p): void {
        $site = owned_site((int) $p['id']);
        if ($site['mode'] !== 'code') {
            redirect('/dashboard/site/' . $site['id'] . '/content');
        }
        require APP_ROOT . '/views/app/site-code.php';
    }],

    ['POST', '/dashboard/site/{id}/code', static function (array $p): void {
        require APP_ROOT . '/views/app/site-code.php';
    }],

    ['GET', '/dashboard/site/{id}/preview', static function (array $p): void {
        $site = owned_site((int) $p['id']);
        header('X-Robots-Tag: noindex');
        if ($site['mode'] === 'code') {
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Security-Policy: sandbox allow-scripts allow-forms allow-popups allow-modals allow-downloads');
            echo render_code_site($site);
        } else {
            header('Content-Type: text/html; charset=utf-8');
            echo render_template_site($site, false, true);
        }
        exit;
    }],

    ['POST', '/dashboard/site/{id}/publish', static function (array $p): void {
        $site = owned_site((int) $p['id']);
        $wanted = (post('published') ?? '1') === '1';
        toggle_site_published((int) $site['id'], $wanted);
        flash('success', $wanted ? 'Your page is live at /p/' . $site['slug'] . '.' : 'Your page is back in draft. No one else can see it.');
        redirect('/dashboard/site/' . $site['id'] . '/' . ($site['mode'] === 'code' ? 'code' : 'content'));
    }],

    ['POST', '/dashboard/site/{id}/delete', static function (array $p): void {
        $user = require_login();
        $site = owned_site((int) $p['id']);
        if (post('confirm') !== $site['slug']) {
            flash('error', 'Type the page address to confirm deletion.');
            redirect('/dashboard/site/' . $site['id'] . '/settings');
        }
        delete_site((int) $site['id']);
        flash('success', '“' . $site['title'] . '” was deleted.');
        redirect('/dashboard');
    }],

    ['GET', '/settings', static function (): void {
        require APP_ROOT . '/views/app/settings.php';
    }],

    ['POST', '/settings', static function (): void {
        require APP_ROOT . '/views/app/settings.php';
    }],

    ['POST', '/api/upload', static function (): void {
        $user = require_login();
        $kind = post('kind') === 'avatar' ? 'avatar' : 'project';
        if (!isset($_FILES['file'])) {
            json_response(['ok' => false, 'error' => 'No image was sent.'], 422);
        }
        $maxWidth = $kind === 'avatar' ? 512 : 1600;
        [$ok, $error, $path] = store_image($_FILES['file'], 'u' . $user['id'], $maxWidth);
        if (!$ok) {
            json_response(['ok' => false, 'error' => $error], 422);
        }
        json_response(['ok' => true, 'path' => $path, 'url' => url($path)]);
    }],

    ['GET', '/export/{id}', static function (array $p): void {
        $site = owned_site((int) $p['id']);
        if ($site['mode'] !== 'code') {
            flash('error', 'Only custom-code pages can be exported as a single file.');
            redirect('/dashboard/site/' . $site['id'] . '/content');
        }
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $site['slug'] . '.html"');
        echo render_code_site($site);
        exit;
    }],

    ['GET', '/p/{slug}', static function (array $p): void {
        require APP_ROOT . '/views/public-site.php';
    }],

    ['GET', '/u/{username}', static function (array $p): void {
        $maker = find_user_by_username($p['username']);
        if (!$maker) {
            abort_page(404, 'No maker by that name.', 'This profile either never existed or has packed up. Explore the work that is here.');
        }
        $sites = published_user_sites((int) $maker['id']);
        render('pages/maker', [
            'maker' => $maker,
            'sites' => $sites,
        ], [
            'title' => $maker['display_name'] . ' (@' . $maker['username'] . ') · ' . APP_NAME,
            'description' => excerpt($maker['bio'] ?: $maker['display_name'] . ' makes portfolios on Portside.', 160),
            'canonical' => absolute_url(maker_path($maker)),
            'ogType' => 'profile',
        ]);
    }],

    ['GET', '/admin', static function (): void {
        require APP_ROOT . '/views/app/admin.php';
    }],

    ['POST', '/admin/site/{id}', static function (array $p): void {
        require APP_ROOT . '/views/app/admin.php';
    }],

    ['GET', '/sitemap.xml', static function (): void {
        require APP_ROOT . '/views/pages/sitemap.php';
    }],

    ['GET', '/robots.txt', static function (): void {
        header('Content-Type: text/plain; charset=utf-8');
        $base = absolute_url('');
        echo "User-agent: *\n";
        echo "Disallow: /dashboard\nDisallow: /settings\nDisallow: /admin\nDisallow: /api/\nDisallow: /login\nDisallow: /signup\n";
        echo 'Sitemap: ' . $base . "sitemap.xml\n";
        exit;
    }],

    ['GET', '/health', static function (): void {
        $ok = true;
        try {
            db()->query('SELECT 1');
        } catch (Throwable $e) {
            $ok = false;
        }
        json_response(['ok' => $ok, 'version' => APP_VERSION, 'time' => now_utc()], $ok ? 200 : 500);
    }],
];

$dispatch = null;
$params = [];
foreach ($routes as [$routeMethod, $pattern, $handler]) {
    $regex = '#^' . preg_replace('/\{([a-z]+)\}/i', '(?P<$1>[A-Za-z0-9_-]+)', $pattern) . '$#';
    if (preg_match($regex, $path, $matches)) {
        if ($routeMethod !== $method) {
            http_response_code(405);
            header('Allow: ' . $routeMethod);
            continue;
        }
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        $dispatch = $handler;
        break;
    }
}

if ($dispatch === null) {
    abort_page(404, 'That page slipped the mooring.', 'The link is broken or the page has moved. Try the work, the templates, or head home.');
}

$dispatch($params);
