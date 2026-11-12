<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\TemplateRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function __invoke(Request $request, TemplateRegistry $templates): View
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'mode' => (string) $request->query('mode', ''),
            'template' => (string) $request->query('template', ''),
            'sort' => (string) $request->query('sort', 'recent'),
        ];

        if (! in_array($filters['mode'], ['', Portfolio::MODE_TEMPLATE, Portfolio::MODE_CODE], true)) {
            $filters['mode'] = '';
        }

        if (! in_array($filters['sort'], ['recent', 'popular'], true)) {
            $filters['sort'] = 'recent';
        }

        if ($filters['template'] !== '' && ! $templates->exists($filters['template'])) {
            $filters['template'] = '';
        }

        $query = Portfolio::query()
            ->published()
            ->with('user')
            ->when($filters['mode'] !== '', fn (Builder $q) => $q->where('mode', $filters['mode']))
            ->when($filters['template'] !== '', fn (Builder $q) => $q->where('template', $filters['template']))
            ->when($filters['q'] !== '', function (Builder $q) use ($filters) {
                $like = '%'.$filters['q'].'%';

                $q->where(function (Builder $inner) use ($like) {
                    $inner->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('slug', 'like', $like)
                        ->orWhereHas('user', fn (Builder $user) => $user
                            ->where('name', 'like', $like)
                            ->orWhere('username', 'like', $like));
                });
            });

        $filters['sort'] === 'popular'
            ? $query->orderByDesc('views')->orderByDesc('updated_at')
            : $query->orderByDesc('updated_at');

        $portfolios = $query->paginate(9)->withQueryString();

        return view('pages.explore', [
            'portfolios' => $portfolios,
            'filters' => $filters,
            'templates' => $templates->all(),
            'hasFilters' => $filters['q'] !== '' || $filters['mode'] !== '' || $filters['template'] !== '',
        ]);
    }
}
