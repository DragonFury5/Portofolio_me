<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public const REASONS = [
        'stolen-work' => 'Stolen or uncredited work',
        'harassment' => 'Harassment or hate',
        'malware' => 'Malware or phishing',
        'illegal' => 'Illegal content',
        'impersonation' => 'Impersonation',
        'other' => 'Something else',
    ];

    public function create(string $slug): View
    {
        $portfolio = Portfolio::query()->published()->with('user')->where('slug', $slug)->firstOrFail();

        return view('pages.report', [
            'portfolio' => $portfolio,
            'reasons' => self::REASONS,
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $portfolio = Portfolio::query()->published()->where('slug', $slug)->firstOrFail();

        if (filled($request->input('website'))) {
            return redirect()->route('portfolios.show', $portfolio->slug);
        }

        $key = 'report|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['reason' => 'Too many reports from this connection. Try again later.']);
        }

        RateLimiter::hit($key, 300);

        $data = $request->validate([
            'reason' => ['required', Rule::in(array_keys(self::REASONS))],
            'details' => ['nullable', 'string', 'max:1200'],
            'reporter_email' => ['nullable', 'email', 'max:190'],
        ]);

        Report::create([
            'portfolio_id' => $portfolio->id,
            'reason' => $data['reason'],
            'details' => $data['details'] ?? null,
            'reporter_email' => $data['reporter_email'] ?? null,
            'status' => 'open',
        ]);

        return redirect()->route('portfolios.show', $portfolio->slug)
            ->with('status', 'Report received. A human will look at it.');
    }
}
