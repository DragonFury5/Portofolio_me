<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Services\TemplateRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request, Portfolio $portfolio, TemplateRegistry $templates): View
    {
        $this->owned($request, $portfolio);

        return view('studio.settings', [
            'portfolio' => $portfolio,
            'templates' => $templates->all(),
            'accents' => TemplateRegistry::ACCENTS,
            'template' => $portfolio->templateMeta(),
            'pageCount' => $request->user()->portfolios()->count(),
            'maxPages' => TemplateRegistry::MAX_PAGES,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio, TemplateRegistry $templates): RedirectResponse
    {
        $this->owned($request, $portfolio);

        $request->merge(['slug' => strtolower(trim((string) $request->input('slug')))]);

        $rules = [
            'title' => ['required', 'string', 'min:2', 'max:80'],
            'slug' => [
                'required',
                'string',
                'regex:/^[a-z0-9](?:[a-z0-9-]{1,38})[a-z0-9]$/',
                Rule::notIn(reserved_slugs()),
                Rule::unique('portfolios', 'slug')->ignore($portfolio->id),
            ],
            'description' => ['nullable', 'string', 'max:200'],
            'accent' => ['required', Rule::in(array_keys(TemplateRegistry::ACCENTS))],
        ];

        if ($portfolio->isTemplate()) {
            $rules['template'] = ['required', Rule::in($templates->keys())];
        }

        $data = $request->validate($rules, [
            'slug.regex' => 'Use 3–40 lowercase letters, numbers or dashes. It cannot start or end with a dash.',
            'slug.not_in' => 'That address is reserved. Pick another.',
            'slug.unique' => 'Another page already uses that address.',
        ]);

        if ($portfolio->isTemplate()) {
            $template = $templates->find($data['template']);
            $portfolio->template = $template['key'];
            $portfolio->content = $templates->normalizeContent(
                is_array($portfolio->content) ? $portfolio->content : [],
                $template['key'],
                $data['accent'],
            );
        }

        $portfolio->fill([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? '',
            'accent' => $data['accent'],
        ])->save();

        return redirect()->route('studio.portfolios.settings.edit', $portfolio)
            ->with('status', 'Page settings saved.');
    }

    public function publish(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $this->owned($request, $portfolio);

        $data = $request->validate([
            'published' => ['required', 'boolean'],
        ]);

        $published = (bool) $data['published'];

        $portfolio->forceFill([
            'is_published' => $published,
            'published_at' => $published ? ($portfolio->published_at ?? now()) : $portfolio->published_at,
        ])->save();

        return back()->with('status', $published
            ? 'Your page is live at /p/'.$portfolio->slug.'.'
            : 'Your page is back in draft. Only you can open it.');
    }

    public function destroy(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $this->owned($request, $portfolio);

        if (! hash_equals($portfolio->slug, (string) $request->input('confirm'))) {
            return back()->withErrors(['confirm' => 'Type the page address exactly to confirm deletion.']);
        }

        $portfolio->delete();

        return redirect()->route('dashboard')
            ->with('status', 'The page was deleted. The harbour is yours to refill.');
    }

    private function owned(Request $request, Portfolio $portfolio): void
    {
        abort_unless($portfolio->user_id === $request->user()->id, 404);
    }
}
