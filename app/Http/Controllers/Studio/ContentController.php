<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Services\TemplateRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function edit(Request $request, Portfolio $portfolio): View
    {
        $this->owned($request, $portfolio);
        abort_unless($portfolio->isTemplate(), 404);

        $content = $portfolio->normalizedContent();

        return view('studio.content', [
            'portfolio' => $portfolio,
            'content' => $content,
            'template' => $portfolio->templateMeta(),
            'templates' => app(TemplateRegistry::class)->all(),
        ]);
    }

    public function update(Request $request, Portfolio $portfolio, TemplateRegistry $templates): RedirectResponse|JsonResponse
    {
        $this->owned($request, $portfolio);
        abort_unless($portfolio->isTemplate(), 404);

        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:220'],
            'intro' => ['nullable', 'string', 'max:500'],
            'about' => ['nullable', 'string', 'max:1800'],
            'location' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:190'],
            'availability' => ['nullable', 'string', 'max:120'],
            'links' => ['nullable', 'array', 'max:8'],
            'links.*.label' => ['nullable', 'string', 'max:40'],
            'links.*.url' => ['nullable', 'string', 'max:500'],
            'projects' => ['nullable', 'array', 'max:12'],
            'projects.*.title' => ['nullable', 'string', 'max:100'],
            'projects.*.year' => ['nullable', 'string', 'max:20'],
            'projects.*.role' => ['nullable', 'string', 'max:80'],
            'projects.*.summary' => ['nullable', 'string', 'max:500'],
            'projects.*.image' => ['nullable', 'string', 'max:500'],
            'projects.*.url' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $templates->normalizeContent($data, $portfolio->template, $portfolio->accent);

        $portfolio->content = $content;
        $portfolio->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'saved_at' => now()->format('H:i:s'),
                'preview_url' => route('studio.portfolios.preview', $portfolio).'?v='.now()->timestamp,
            ]);
        }

        return redirect()->route('studio.portfolios.content.edit', $portfolio)
            ->with('status', 'Saved. Your live page updates straight away.');
    }

    private function owned(Request $request, Portfolio $portfolio): void
    {
        abort_unless($portfolio->user_id === $request->user()->id, 404);
    }
}
