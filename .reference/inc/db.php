<?php
declare(strict_types=1);

/** Shared SQLite connection. The schema is created on first boot. */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dataDir = STORAGE_DIR . '/data';
    if (!is_dir($dataDir)) {
        @mkdir($dataDir, 0775, true);
    }
    $path = $dataDir . '/portside.sqlite';

    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA busy_timeout = 5000');

    migrate_database($pdo);

    return $pdo;
}

function migrate_database(PDO $pdo): void
{
    $version = (int) $pdo->query('PRAGMA user_version')->fetchColumn();
    if ($version >= 1) {
        return;
    }

    $pdo->exec('BEGIN IMMEDIATE');
    try {
        $pdo->exec(<<<'SQL'
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                display_name TEXT NOT NULL,
                bio TEXT NOT NULL DEFAULT '',
                avatar TEXT NOT NULL DEFAULT '',
                location TEXT NOT NULL DEFAULT '',
                website TEXT NOT NULL DEFAULT '',
                is_admin INTEGER NOT NULL DEFAULT 0,
                created_at TEXT NOT NULL
            );

            CREATE TABLE IF NOT EXISTS sites (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                slug TEXT NOT NULL UNIQUE,
                title TEXT NOT NULL,
                description TEXT NOT NULL DEFAULT '',
                mode TEXT NOT NULL DEFAULT 'template' CHECK (mode IN ('template', 'code')),
                template TEXT NOT NULL DEFAULT 'ledger',
                content_json TEXT NOT NULL DEFAULT '{}',
                html TEXT NOT NULL DEFAULT '',
                css TEXT NOT NULL DEFAULT '',
                js TEXT NOT NULL DEFAULT '',
                is_published INTEGER NOT NULL DEFAULT 0,
                is_featured INTEGER NOT NULL DEFAULT 0,
                views INTEGER NOT NULL DEFAULT 0,
                published_at TEXT,
                created_at TEXT NOT NULL,
                updated_at TEXT NOT NULL
            );

            CREATE INDEX IF NOT EXISTS sites_user_idx ON sites (user_id);
            CREATE INDEX IF NOT EXISTS sites_public_idx ON sites (is_published, updated_at);

            CREATE TABLE IF NOT EXISTS site_views (
                site_id INTEGER NOT NULL REFERENCES sites(id) ON DELETE CASCADE,
                day TEXT NOT NULL,
                count INTEGER NOT NULL DEFAULT 0,
                PRIMARY KEY (site_id, day)
            );

            CREATE TABLE IF NOT EXISTS throttle (
                key TEXT PRIMARY KEY,
                hits INTEGER NOT NULL DEFAULT 0,
                reset_at INTEGER NOT NULL
            );

            CREATE TABLE IF NOT EXISTS reports (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                site_id INTEGER NOT NULL REFERENCES sites(id) ON DELETE CASCADE,
                reason TEXT NOT NULL,
                detail TEXT NOT NULL DEFAULT '',
                reporter_email TEXT NOT NULL DEFAULT '',
                created_at TEXT NOT NULL,
                resolved INTEGER NOT NULL DEFAULT 0
            );
        SQL);

        $pdo->exec('PRAGMA user_version = 1');
        $pdo->exec('COMMIT');
    } catch (Throwable $e) {
        $pdo->exec('ROLLBACK');
        throw $e;
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count === 0) {
        require_once APP_ROOT . '/inc/seed.php';
        seed_database($pdo);
    }
}
