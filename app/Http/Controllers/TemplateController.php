<?php

namespace App\Http\Controllers;

use App\Services\PortfolioRenderer;
use App\Services\TemplateRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    public function index(TemplateRegistry $templates): View
    {
        return view('pages.templates', [
            'templates' => $templates->all(),
        ]);
    }

    public function preview(Request $request, string $template, TemplateRegistry $templates, PortfolioRenderer $renderer): View
    {
        abort_unless($templates->exists($template), 404);

        $portfolio = $renderer->samplePortfolio($template);

        return $renderer->templateView($portfolio, embed: $request->boolean('embed'), preview: true);
    }
}
