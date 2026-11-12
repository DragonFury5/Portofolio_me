<?php

declare(strict_types=1);

use App\Models\Portfolio;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** Two-letter initials for an avatar fallback. */
function initials(?string $name): string
{
    $parts = preg_split('/\s+/', trim((string) $name)) ?: [];
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

/**
 * Escape user text, then turn *asterisks* into emphasis.
 * The order matters: nothing is trusted after escaping.
 */
function portfolio_markup(?string $text): string
{
    $safe = e((string) $text);
    $safe = preg_replace('/\*([^*\n]+)\*/u', '<em>$1</em>', $safe) ?? $safe;

    return nl2br($safe, false);
}

/** Only allow URLs that cannot execute script. */
function safe_href(?string $href): string
{
    $href = trim((string) $href);

    if ($href === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $href) === 1 || preg_match('#^mailto:#i', $href) === 1) {
        return $href;
    }

    if (str_starts_with($href, '#')) {
        return $href;
    }

    if (str_starts_with($href, '/') && ! str_starts_with($href, '//')) {
        return $href;
    }

    // Allow short relative paths such as "work/project".
    if (preg_match('#^[a-z0-9._~/-]+(?:\?[a-z0-9._~=&%+-]*)?$#i', $href) === 1) {
        return $href;
    }

    return '';
}

/** Resolve a stored image path or URL for use in a template. */
function media_url(?string $path): string
{
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $path) === 1) {
        return $path;
    }

    if (str_starts_with($path, '//')) {
        return '';
    }

    return asset(ltrim($path, '/'));
}

function portfolio_cover(Portfolio $portfolio): ?string
{
    foreach ($portfolio->normalizedContent()['projects'] as $project) {
        if (($project['image'] ?? '') !== '') {
            return media_url($project['image']);
        }
    }

    return null;
}

function human_number(int|float $number): string
{
    $number = (int) $number;

    if ($number < 1000) {
        return (string) $number;
    }

    if ($number < 1_000_000) {
        return rtrim(rtrim(number_format($number / 1000, 1), '0'), '.').'k';
    }

    return rtrim(rtrim(number_format($number / 1_000_000, 1), '0'), '.').'m';
}

function excerpt_text(?string $text, int $limit = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', (string) $text) ?? '');

    if (mb_strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, $limit - 1)).'…';
}

function format_date(?Carbon $date, string $format = 'j M Y'): string
{
    return $date?->format($format) ?? '—';
}

function time_ago(?Carbon $date): string
{
    if (! $date) {
        return 'never';
    }

    return $date->diffForHumans(short: true);
}

/** Slugs that would collide with a real route. */
function reserved_slugs(): array
{
    return [
        'admin', 'api', 'login', 'logout', 'register', 'signup', 'dashboard', 'explore',
        'templates', 'settings', 'uploads', 'assets', 'storage', 'p', 'u', 'docs', 'help',
        'about', 'new', 'preview', 'export', 'sitemap', 'robots', 'health', 'portside',
        'www', 'mail', 'support', 'terms', 'privacy', 'code', 'guide', 'search', 'report',
        'up', 'account',
    ];
}

function valid_username(?string $username): bool
{
    $username = (string) $username;

    return preg_match('/^[a-z0-9](?:[a-z0-9_]{1,22})[a-z0-9]$/', $username) === 1
        && ! in_array($username, reserved_slugs(), true);
}

function valid_portfolio_slug(?string $slug): bool
{
    $slug = (string) $slug;

    return preg_match('/^[a-z0-9](?:[a-z0-9-]{1,38})[a-z0-9]$/', $slug) === 1
        && ! in_array($slug, reserved_slugs(), true);
}

function slugify_name(string $value, string $fallback = 'portfolio'): string
{
    $slug = Str::slug($value);

    return $slug !== '' ? mb_substr($slug, 0, 32) : $fallback;
}

function template_meta(?string $key): array
{
    return app(\App\Services\TemplateRegistry::class)->find($key);
}
