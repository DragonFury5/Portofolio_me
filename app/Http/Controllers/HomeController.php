<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\TemplateRegistry;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(TemplateRegistry $templates): View
    {
        $featured = Portfolio::query()
            ->published()
            ->with('user')
            ->orderByDesc('is_featured')
            ->orderByDesc('views')
            ->limit(3)
            ->get();

        $totalPages = Portfolio::published()->count();
        $totalMakers = Portfolio::published()->distinct('user_id')->count('user_id');

        return view('pages.home', [
            'templates' => $templates->all(),
            'featured' => $featured,
            'totalPages' => $totalPages,
            'totalMakers' => $totalMakers,
        ]);
    }
}
