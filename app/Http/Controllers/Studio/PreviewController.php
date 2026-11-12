<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Services\PortfolioRenderer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PreviewController extends Controller
{
    public function __invoke(Request $request, Portfolio $portfolio, PortfolioRenderer $renderer): Response
    {
        abort_unless($portfolio->user_id === $request->user()->id, 404);

        if ($portfolio->isCode()) {
            return response($renderer->codeDocument($portfolio), 200, array_merge(
                $renderer->sandboxHeaders(),
                ['X-Robots-Tag' => 'noindex, nofollow'],
            ));
        }

        $data = $renderer->viewData($portfolio, embed: true, preview: true);

        return response()
            ->view('portfolios.templates.'.$data['templateKey'], $data)
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Content-Security-Policy', "frame-ancestors 'self'");
    }
}
