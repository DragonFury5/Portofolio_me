<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\View\View;

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
}
