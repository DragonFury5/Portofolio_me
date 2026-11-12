<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Services\CodeStarter;
use App\Services\TemplateRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function create(Request $request, TemplateRegistry $templates, CodeStarter $starters): View|RedirectResponse
    {
        $user = $request->user();
        $count = $user->portfolios()->count();

        if ($count >= TemplateRegistry::MAX_PAGES) {
            return redirect()->route('dashboard')
                ->with('error', 'You have all '.TemplateRegistry::MAX_PAGES.' pages. Delete one before making another.');
        }

        return view('studio.create', [
            'templates' => $templates->all(),
            'starters' => $starters->all(),
            'pageCount' => $count,
            'maxPages' => TemplateRegistry::MAX_PAGES,
            'selectedTemplate' => $templates->exists($request->query('template')) ? $request->query('template') : 'ledger',
            'selectedStarter' => $starters->exists($request->query('starter')) ? $request->query('starter') : 'blank',
        ]);
    }

    public function store(Request $request, TemplateRegistry $templates, CodeStarter $starters): RedirectResponse
    {
        $user = $request->user();

        if ($user->portfolios()->count() >= TemplateRegistry::MAX_PAGES) {
            return redirect()->route('dashboard')
                ->with('error', 'You have all '.TemplateRegistry::MAX_PAGES.' pages. Delete one before making another.');
        }

        $request->validate([
            'mode' => ['required', Rule::in([Portfolio::MODE_TEMPLATE, Portfolio::MODE_CODE])],
            'template' => ['required_if:mode,template', 'nullable', Rule::in($templates->keys())],
            'starter' => ['required_if:mode,code', 'nullable', Rule::in(array_keys($starters->all()))],
        ]);

        $mode = $request->string('mode')->toString();
        $slug = $this->uniqueSlug($user->username);
        $title = $templates->titleFor($user->name);

        if ($mode === Portfolio::MODE_CODE) {
            $starter = $starters->find($request->string('starter')->toString());

            $portfolio = Portfolio::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'title' => $title,
                'description' => '',
                'mode' => Portfolio::MODE_CODE,
                'template' => null,
                'accent' => 'blue',
                'content' => [],
                'html' => $starter['html'],
                'css' => $starter['css'],
                'js' => $starter['js'],
                'is_published' => false,
            ]);

            return redirect()->route('studio.portfolios.code.edit', $portfolio)
                ->with('status', 'Code page created. The preview is live beside the editor.');
        }

        $template = $templates->find($request->string('template')->toString());

        $portfolio = Portfolio::create([
            'user_id' => $user->id,
            'slug' => $slug,
            'title' => $title,
            'description' => '',
            'mode' => Portfolio::MODE_TEMPLATE,
            'template' => $template['key'],
            'accent' => $template['accent'],
            'content' => $templates->starterContent($template['key'], $user->name),
            'is_published' => false,
        ]);

        return redirect()->route('studio.portfolios.content.edit', $portfolio)
            ->with('status', 'Page created. Replace the sample words and make it yours.');
    }

    private function uniqueSlug(string $seed): string
    {
        $base = slugify_name($seed);
        $candidate = $base;
        $i = 2;

        while (Portfolio::where('slug', $candidate)->exists() || ! valid_portfolio_slug($candidate)) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }
}
