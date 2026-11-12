<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioView;
use App\Services\TemplateRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, TemplateRegistry $templates): View
    {
        $user = $request->user();
        $start = now()->subDays(13)->startOfDay();

        $pages = $user->portfolios()
            ->withSum(['dailyViews as views_14d' => fn ($query) => $query->where('day', '>=', $start)], 'count')
            ->orderByDesc('updated_at')
            ->get();

        $series = [];

        for ($i = 13; $i >= 0; $i--) {
            $series[now()->subDays($i)->toDateString()] = 0;
        }

        $rows = PortfolioView::query()
            ->join('portfolios', 'portfolios.id', '=', 'portfolio_views.portfolio_id')
            ->where('portfolios.user_id', $user->id)
            ->where('portfolio_views.day', '>=', $start->toDateString())
            ->groupBy('portfolio_views.day')
            ->selectRaw('portfolio_views.day, SUM(portfolio_views.count) as total')
            ->pluck('total', 'day');

        foreach ($rows as $day => $count) {
            $key = substr((string) $day, 0, 10);
            $series[$key] = (int) $count;
        }

        return view('studio.dashboard', [
            'user' => $user,
            'pages' => $pages,
            'series' => $series,
            'views14' => array_sum($series),
            'publishedCount' => $pages->where('is_published', true)->count(),
            'draftCount' => $pages->where('is_published', false)->count(),
            'maxPages' => TemplateRegistry::MAX_PAGES,
            'templates' => $templates->all(),
        ]);
    }
}
