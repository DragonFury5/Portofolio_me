<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Contracts\View\View;

class PortfolioRenderer
{
    public function __construct(
        private readonly TemplateRegistry $templates,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function viewData(Portfolio $portfolio, bool $embed = false, bool $preview = false): array
    {
        $portfolio->loadMissing('user');

        $template = $this->templates->exists($portfolio->template) ? $portfolio->template : 'ledger';
        $accent = $this->templates->normalizeAccent($portfolio->accent);
        $content = $portfolio->normalizedContent();
        $user = $portfolio->user;

        $description = trim((string) $portfolio->description);

        if ($description === '') {
            $description = excerpt_text((string) $content['intro'], 160);
        }

        $ogImage = '';

        foreach ($content['projects'] as $project) {
            if (($project['image'] ?? '') !== '') {
                $ogImage = media_url($project['image']);
                break;
            }
        }

        return [
            'portfolio' => $portfolio,
            'content' => $content,
            'templateKey' => $template,
            'accent' => $accent,
            'bodyClass' => 'tpl tpl-'.$template.' accent-'.$accent,
            'pageTitle' => $portfolio->title ?: (($user?->name ?? 'A maker').' — Portfolio'),
            'metaDescription' => $description !== '' ? $description : 'A portfolio made with Portside.',
            'canonical' => $portfolio->publicUrl(),
            'ogImage' => $ogImage,
            'embed' => $embed,
            'preview' => $preview,
            'site' => [
                'display_name' => $user?->name ?? 'A maker',
                'username' => $user?->username ?? '',
                'avatar' => $user?->avatar ?? '',
            ],
        ];
    }

    public function templateView(Portfolio $portfolio, bool $embed = false, bool $preview = false): View
    {
        $data = $this->viewData($portfolio, $embed, $preview);

        return view('portfolios.templates.'.$data['templateKey'], $data);
    }

    public function samplePortfolio(string $template = 'ledger'): Portfolio
    {
        $template = $this->templates->exists($template) ? $template : 'ledger';

        $user = new User([
            'name' => 'Sample Maker',
            'username' => 'sample',
            'bio' => 'A sample of the Portside template, filled with work that does not exist.',
            'avatar' => 'assets/img/seed/avatar-demo.svg',
            'location' => 'The harbour',
        ]);

        $portfolio = new Portfolio([
            'slug' => 'sample-'.$template,
            'title' => $this->templates->find($template)['name'].' — sample portfolio',
            'description' => $this->templates->find($template)['description'],
            'mode' => Portfolio::MODE_TEMPLATE,
            'template' => $template,
            'accent' => $this->templates->find($template)['accent'],
            'is_published' => true,
            'content' => $this->templates->starterContent($template, 'Sample Maker'),
        ]);

        $portfolio->setRelation('user', $user);

        return $portfolio;
    }

    /**
     * Build a complete, standalone document for a code page.
     * User code is intentionally not sanitised: it runs only inside a
     * sandboxed, opaque-origin document.
     */
    public function codeDocument(Portfolio $portfolio): string
    {
        $html = (string) ($portfolio->html ?? '');
        $css = (string) ($portfolio->css ?? '');
        $js = (string) ($portfolio->js ?? '');
        $title = e($portfolio->title ?: 'A portfolio on Portside');
        $description = e($portfolio->description ?: 'A hand-coded portfolio made with Portside.');
        $fonts = '<link rel="stylesheet" href="'.e(asset('assets/css/code-fonts.css')).'">';
        $theme = '<meta name="theme-color" content="#1B365D">';
        $styleTag = $css !== '' ? "<style id=\"portside-user-css\">\n{$css}\n</style>" : '';
        $scriptTag = $js !== '' ? "<script id=\"portside-user-js\">\n{$js}\n</script>" : '';
        $platformHead = $fonts."\n".$theme;

        if (preg_match('/<html[\s>]/i', $html) === 1) {
            if (stripos($html, '</head>') !== false) {
                $html = preg_replace('/<\/head>/i', $platformHead.$styleTag."\n</head>", $html, 1) ?? $html;
            } elseif (preg_match('/<body[\s>]/i', $html) === 1) {
                $html = preg_replace('/<body([\s>])/i', '<head>'.$platformHead.$styleTag."</head><body$1", $html, 1) ?? $html;
            } else {
                $html = '<head>'.$platformHead.$styleTag.'</head>'.$html;
            }

            if ($scriptTag !== '') {
                if (stripos($html, '</body>') !== false) {
                    $html = preg_replace('/<\/body>/i', $scriptTag."\n</body>", $html, 1) ?? $html;
                } else {
                    $html .= $scriptTag;
                }
            }

            return $html;
        }

        return "<!doctype html>\n<html lang=\"en\">\n<head>\n"
            ."<meta charset=\"utf-8\">\n"
            ."<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
            ."<title>{$title}</title>\n"
            ."<meta name=\"description\" content=\"{$description}\">\n"
            ."<meta property=\"og:title\" content=\"{$title}\">\n"
            ."<meta property=\"og:description\" content=\"{$description}\">\n"
            ."<meta property=\"og:type\" content=\"website\">\n"
            .$platformHead."\n"
            .$styleTag."\n</head>\n<body>\n"
            .$html."\n".$scriptTag."\n</body>\n</html>";
    }

    /**
     * @return array<string, string>
     */
    public function sandboxHeaders(): array
    {
        return [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Security-Policy' => "sandbox allow-scripts allow-forms allow-popups allow-modals allow-downloads; frame-ancestors 'self'",
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=(), usb=()',
            'Referrer-Policy' => 'no-referrer',
            'X-Content-Type-Options' => 'nosniff',
        ];
    }
}
