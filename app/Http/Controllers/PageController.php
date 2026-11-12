<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
    public function codeGuide(): View
    {
        return view('pages.code-guide');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function sitemap(): Response
    {
        $portfolios = Portfolio::query()
            ->published()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $makers = User::query()
            ->whereHas('portfolios', fn ($query) => $query->published())
            ->get(['username', 'updated_at']);

        $xml = view('pages.sitemap', [
            'portfolios' => $portfolios,
            'makers' => $makers,
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n"
            ."Allow: /\n"
            ."Disallow: /dashboard\n"
            ."Disallow: /settings\n"
            ."Disallow: /admin\n"
            ."Disallow: /studio\n"
            ."Disallow: /login\n"
            ."Disallow: /register\n"
            ."\nSitemap: ".route('sitemap')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * Serve the self-hosted fonts with CORS so sandboxed, opaque-origin
     * portfolio pages can use them without opening a same-origin hole.
     */
    public function font(string $file): BinaryFileResponse
    {
        $allowed = [
            'fraunces-latin-italic-400-700.woff2',
            'fraunces-latin-normal-400-700.woff2',
            'public-sans-latin-italic-400-600.woff2',
            'public-sans-latin-normal-400-700.woff2',
        ];

        abort_unless(in_array($file, $allowed, true), 404);

        return response()->file(public_path('assets/fonts/'.$file), [
            'Content-Type' => 'font/woff2',
            'Access-Control-Allow-Origin' => '*',
            'Cross-Origin-Resource-Policy' => 'cross-origin',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
