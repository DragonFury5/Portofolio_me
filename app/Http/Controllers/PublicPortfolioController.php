<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioView;
use App\Services\PortfolioRenderer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicPortfolioController extends Controller
{
    public function show(Request $request, string $slug, PortfolioRenderer $renderer): View|Response
    {
        $portfolio = Portfolio::query()->with('user')->where('slug', $slug)->firstOrFail();
        $isOwner = $request->user()?->id === $portfolio->user_id;

        abort_unless($portfolio->is_published || $isOwner, 404);

        $embed = $request->boolean('embed');

        if (! $portfolio->is_published) {
            $embed = true;
            $isOwner = true;
        }

        if ($portfolio->is_published && ! $embed) {
            $this->recordView($request, $portfolio);
        }

        if ($portfolio->isCode()) {
            return response($renderer->codeDocument($portfolio), 200, $renderer->sandboxHeaders());
        }

        return $renderer->templateView($portfolio, embed: $embed, preview: $isOwner && ! $portfolio->is_published);
    }

    private function recordView(Request $request, Portfolio $portfolio): void
    {
        $seen = $request->session()->get('seen_portfolios', []);
        $today = now()->toDateString();

        if (($seen[$portfolio->id] ?? null) === $today) {
            return;
        }

        $seen[$portfolio->id] = $today;
        $request->session()->put('seen_portfolios', $seen);

        $portfolio->increment('views');

        DB::table('portfolio_views')->upsert(
            [[
                'portfolio_id' => $portfolio->id,
                'day' => $today,
                'count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]],
            ['portfolio_id', 'day'],
            ['count' => DB::raw('portfolio_views.count + 1'), 'updated_at' => now()],
        );
    }
}
