<?php
declare(strict_types=1);

/** Authentication, sessions and ownership checks. */

function current_user(): ?array
{
    static $user = false;
    if ($user !== false) {
        return $user;
    }
    $id = $_SESSION['uid'] ?? null;
    if (!is_int($id) && !ctype_digit((string) $id)) {
        return $user = null;
    }
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int) $id]);
    $found = $stmt->fetch();
    return $user = ($found ?: null);
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        $next = request_path();
        if (($_SERVER['QUERY_STRING'] ?? '') !== '') {
            $next .= '?' . $_SERVER['QUERY_STRING'];
        }
        flash('info', 'Log in to get to your studio.');
        redirect('/login?next=' . rawurlencode($next));
    }
    return $user;
}

function require_admin(): array
{
    $user = require_login();
    if (!(int) $user['is_admin']) {
        abort_page(403, 'This room is staff only.', 'Your account does not have access to moderation tools.');
    }
    return $user;
}

function login_user(int $id): void
{
    session_regenerate_id(true);
    $_SESSION['uid'] = $id;
    unset($_SESSION['_csrf']);
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}

function attempt_login(string $identifier, string $password): array
{
    $identifier = trim($identifier);
    $row = null;
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([mb_strtolower($identifier)]);
        $row = $stmt->fetch();
    } else {
        $stmt = db()->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([mb_strtolower($identifier)]);
        $row = $stmt->fetch();
    }

    if (!$row || !password_verify($password, $row['password_hash'])) {
        return [false, 'That email and password do not match an account.'];
    }
    if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
        db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $row['id']]);
    }
    login_user((int) $row['id']);
    return [true, ''];
}

function register_user(string $username, string $email, string $password, string $displayName): array
{
    $username = mb_strtolower(trim($username));
    $email = mb_strtolower(trim($email));
    $displayName = trim($displayName);

    if (!valid_username($username)) {
        return [false, 'Usernames use 3–24 lowercase letters, numbers or underscores, and start and end with a letter or number.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
        return [false, 'That email address does not look right.'];
    }
    if (mb_strlen($password) < 10) {
        return [false, 'Use at least 10 characters for your password.'];
    }
    if (mb_strlen($displayName) < 2 || mb_strlen($displayName) > 60) {
        return [false, 'Tell us the name you want on your page (2–60 characters).'];
    }

    $stmt = db()->prepare('SELECT username, email FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return [false, 'That username or email is already taken.'];
    }

    $stmt = db()->prepare('INSERT INTO users (username, email, password_hash, display_name, created_at)
        VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $displayName, now_utc()]);
    login_user((int) db()->lastInsertId());
    return [true, ''];
}

function update_password(int $userId, string $current, string $new): array
{
    $stmt = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $hash = (string) $stmt->fetchColumn();
    if (!password_verify($current, $hash)) {
        return [false, 'Your current password is not right.'];
    }
    if (mb_strlen($new) < 10) {
        return [false, 'Use at least 10 characters for the new password.'];
    }
    db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
        ->execute([password_hash($new, PASSWORD_DEFAULT), $userId]);
    return [true, ''];
}
