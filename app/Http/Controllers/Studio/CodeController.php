<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Services\TemplateRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CodeController extends Controller
{
    public function edit(Request $request, Portfolio $portfolio): View
    {
        $this->owned($request, $portfolio);
        abort_unless($portfolio->isCode(), 404);

        return view('studio.code', [
            'portfolio' => $portfolio,
            'limits' => TemplateRegistry::LIMITS,
            'maxBytes' => [
                'html' => TemplateRegistry::LIMITS['html'],
                'css' => TemplateRegistry::LIMITS['css'],
                'js' => TemplateRegistry::LIMITS['js'],
            ],
        ]);
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse|JsonResponse
    {
        $this->owned($request, $portfolio);
        abort_unless($portfolio->isCode(), 404);

        $data = $request->validate([
            'html' => ['present', 'string'],
            'css' => ['present', 'string'],
            'js' => ['present', 'string'],
        ]);

        $limits = [
            'html' => TemplateRegistry::LIMITS['html'],
            'css' => TemplateRegistry::LIMITS['css'],
            'js' => TemplateRegistry::LIMITS['js'],
        ];

        foreach ($limits as $part => $limit) {
            if (strlen($data[$part]) > $limit) {
                throw ValidationException::withMessages([
                    $part => strtoupper($part).' is over the '.round($limit / 1000).' KB limit. Trim it a little.',
                ]);
            }
        }

        $portfolio->forceFill([
            'html' => $data['html'],
            'css' => $data['css'],
            'js' => $data['js'],
        ])->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'saved_at' => now()->format('H:i:s'),
            ]);
        }

        return redirect()->route('studio.portfolios.code.edit', $portfolio)
            ->with('status', 'Saved. The live page is up to date.');
    }

    public function export(Request $request, Portfolio $portfolio, \App\Services\PortfolioRenderer $renderer)
    {
        $this->owned($request, $portfolio);
        abort_unless($portfolio->isCode(), 404);

        return response($renderer->codeDocument($portfolio), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$portfolio->slug.'.html"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function owned(Request $request, Portfolio $portfolio): void
    {
        abort_unless($portfolio->user_id === $request->user()->id, 404);
    }
}
