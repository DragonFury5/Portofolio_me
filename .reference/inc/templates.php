<?php
declare(strict_types=1);

/** Template catalogue, starter content, and public renderers. */

function portfolio_templates(): array
{
    return [
        'ledger' => [
            'name' => 'Ledger',
            'kicker' => 'Editorial index',
            'description' => 'A quiet, print-shaped index for designers, writers and studios.',
            'long' => 'Numbered project rows, a generous serif and room to explain the thinking. Built for work that needs context more than spectacle.',
            'best' => 'Graphic design · Type · Writing · Research',
            'accent' => 'cyan',
            'preview' => 'ledger',
        ],
        'atlas' => [
            'name' => 'Atlas',
            'kicker' => 'Image first',
            'description' => 'A dark, image-led book for photographers and art directors.',
            'long' => 'Full-bleed colour, asymmetric plates and captions that stay out of the way. Your images do the talking; the grid keeps them in line.',
            'best' => 'Photography · Art direction · Film',
            'accent' => 'cyan',
            'preview' => 'atlas',
        ],
        'signal' => [
            'name' => 'Signal',
            'kicker' => 'Engineer’s page',
            'description' => 'A two-column, get-to-the-point page for people who build software.',
            'long' => 'A fixed profile rail, a dense project ledger and monospace metadata. Made for repos, systems, and work you can point at.',
            'best' => 'Engineering · Product · Data',
            'accent' => 'blue',
            'preview' => 'signal',
        ],
        'tide' => [
            'name' => 'Tide',
            'kicker' => 'Horizontal rail',
            'description' => 'A soft, roomy page with a sideways project rail for illustrators and makers.',
            'long' => 'Oversized type, a scrolling shelf of work and a portrait mark that feels drawn rather than photographed. Good for work that should be browsed.',
            'best' => 'Illustration · Ceramics · Product',
            'accent' => 'blue',
            'preview' => 'tide',
        ],
    ];
}

function template_or_default(string $key): array
{
    $templates = portfolio_templates();
    return $templates[$key] ?? $templates['ledger'];
}

function starter_content(string $template, string $name = 'Your name'): array
{
    $common = [
        'eyebrow' => 'Independent designer · Your city',
        'headline' => 'I make *considered* things for screens and paper.',
        'intro' => 'A short line about what you do and who you do it for. Replace this with your own.',
        'about' => 'Two or three sentences about your practice, how you work, and what you care about. This starter copy is here so the page never looks empty.',
        'location' => 'Your city',
        'email' => '',
        'availability' => 'Available for select projects',
        'links' => [],
        'projects' => [],
        'accent' => 'cyan',
        '_starter' => true,
    ];

    $projects = [
        [
            'title' => 'Harbor identity',
            'year' => '2025',
            'role' => 'Identity, print',
            'summary' => 'A visual system for a small ferry co-op. Wayfinding, tickets, and a mark that survives salt water.',
            'image' => 'assets/img/seed/harbor-mark.svg',
            'url' => '',
        ],
        [
            'title' => 'Silt, a type specimen',
            'year' => '2024',
            'role' => 'Type design',
            'summary' => 'A workhorse serif drawn for long reports and short captions. Four weights, one stubborn italic.',
            'image' => 'assets/img/seed/silt-specimen.svg',
            'url' => '',
        ],
        [
            'title' => 'Civic type',
            'year' => '2024',
            'role' => 'Editorial',
            'summary' => 'A 96-page annual report for a public library, designed to be read rather than admired from a distance.',
            'image' => 'assets/img/seed/civic-type.svg',
            'url' => '',
        ],
    ];

    return match ($template) {
        'atlas' => array_merge($common, [
            'eyebrow' => 'Photographer · Your city',
            'headline' => 'Light, weather, and the occasional *accident*.',
            'intro' => 'Photography and art direction for places that are easier to feel than describe.',
            'about' => 'I photograph coasts, ferries and the hour before a storm. Commissions and editorial work, mostly on film, sometimes not.',
            'availability' => 'Booking autumn commissions',
            'accent' => 'cyan',
            'projects' => [
                [
                    'title' => 'Night ferry',
                    'year' => '2026',
                    'role' => 'Personal series',
                    'summary' => 'Twelve crossings after midnight. Available as a newsprint zine.',
                    'image' => 'assets/img/seed/night-ferry.svg',
                    'url' => '',
                ],
                [
                    'title' => 'Low tide, 6pm',
                    'year' => '2025',
                    'role' => 'Editorial',
                    'summary' => 'Commissioned by a coastal science quarterly. Shot over one week in November.',
                    'image' => 'assets/img/seed/low-tide.svg',
                    'url' => '',
                ],
                [
                    'title' => 'Folded coast',
                    'year' => '2025',
                    'role' => 'Art direction',
                    'summary' => 'A campaign about erosion, printed on paper that refuses to lie flat.',
                    'image' => 'assets/img/seed/folded-coast.svg',
                    'url' => '',
                ],
            ],
        ]),
        'signal' => array_merge($common, [
            'eyebrow' => 'Front-end engineer · Your city',
            'headline' => 'I build interfaces that *hold up* under real use.',
            'intro' => 'Front-end, design systems, and the unglamorous parts of shipping software that people depend on.',
            'about' => 'Eight years between design and engineering. I like small components, honest performance budgets, and documentation that a new teammate can actually follow.',
            'location' => 'Remote · UTC+7',
            'availability' => 'Open to contract work',
            'accent' => 'blue',
            'projects' => [
                [
                    'title' => 'Atlas design system',
                    'year' => '2026',
                    'role' => 'Lead engineer',
                    'summary' => 'A 40-component system used by four product teams. Documented, tested, and smaller than the last one.',
                    'image' => '',
                    'url' => '',
                ],
                [
                    'title' => 'Tidepool CLI',
                    'year' => '2025',
                    'role' => 'Author, open source',
                    'summary' => 'A small tool for replaying failed network requests locally. 1.2k stars and no analytics.',
                    'image' => '',
                    'url' => '',
                ],
                [
                    'title' => 'Reading room',
                    'year' => '2024',
                    'role' => 'Performance',
                    'summary' => 'Cut a media archive’s largest page from 4.1 MB to 380 KB without losing a single photograph.',
                    'image' => '',
                    'url' => '',
                ],
            ],
        ]),
        'tide' => array_merge($common, [
            'eyebrow' => 'Illustrator · Your city',
            'headline' => 'Pictures for slow mornings and loud *ideas*.',
            'intro' => 'Editorial illustration, packaging, and drawings that leave a little room around the edges.',
            'about' => 'I work in gouache, pencil and a scanner that has seen better days. Clients include small magazines, food labels, and the occasional stubborn book cover.',
            'availability' => 'Two slots open this month',
            'accent' => 'blue',
            'projects' => [
                [
                    'title' => 'Quay study',
                    'year' => '2026',
                    'role' => 'Editorial',
                    'summary' => 'A series about the working edge of a harbour, drawn from the same bench over three seasons.',
                    'image' => 'assets/img/seed/quay-study.svg',
                    'url' => '',
                ],
                [
                    'title' => 'Paper no. 3',
                    'year' => '2025',
                    'role' => 'Packaging',
                    'summary' => 'Wrapping paper for a stationery shop that only sells three things. Ink on uncoated stock.',
                    'image' => 'assets/img/seed/paper-no3.svg',
                    'url' => '',
                ],
                [
                    'title' => 'Long wave',
                    'year' => '2025',
                    'role' => 'Book cover',
                    'summary' => 'A cover for a novel about a swimmer. The wave had to look like it was already moving.',
                    'image' => 'assets/img/seed/long-wave.svg',
                    'url' => '',
                ],
            ],
        ]),
        default => array_merge($common, ['projects' => $projects]),
    };
}

/** Small CSS-drawn preview used in marketing cards and pickers. */
function mini_template_markup(string $key): string
{
    switch ($key) {
        case 'ledger':
            return <<<'HTML'
<div class="mini-tpl mini-tpl--ledger">
  <div class="mini-tpl__top"><span>Mara Ellison</span><span>Work / About</span></div>
  <div class="mini-tpl__title">Printed matter<br>for people who<br><em>still read.</em></div>
  <div class="mini-tpl__row"><i>01</i><b>Harbor identity</b><span>2025</span></div>
  <div class="mini-tpl__row"><i>02</i><b>Silt specimen</b><span>2024</span></div>
  <div class="mini-tpl__row"><i>03</i><b>Civic type</b><span>2024</span></div>
</div>
HTML;
        case 'atlas':
            return <<<'HTML'
<div class="mini-tpl mini-tpl--atlas">
  <div class="mini-tpl__top"><span>Kaito Mori</span><span>Photography · Osaka</span></div>
  <div class="mini-tpl__hero">Light,<br>mostly.</div>
  <div class="mini-tpl__plates"><div></div><div></div></div>
</div>
HTML;
        case 'signal':
            return <<<'HTML'
<div class="mini-tpl mini-tpl--signal">
  <div class="mini-tpl__rail"><b>Priya Nair</b>Front-end engineer<br><br>Work<br><br>About<br><br>Contact</div>
  <div class="mini-tpl__main">
    <h4>Interfaces that hold up.</h4>
    <span>Atlas design system</span>
    <span>Tidepool CLI</span>
    <span>Reading room</span>
  </div>
</div>
HTML;
        default:
            return <<<'HTML'
<div class="mini-tpl mini-tpl--tide">
  <div class="mini-tpl__eyebrow">Illustration · Lisbon</div>
  <div class="mini-tpl__title">Pictures for<br>slow mornings.</div>
  <div class="mini-tpl__rail">
    <div class="mini-tpl__card"><div></div><span>Quay study</span></div>
    <div class="mini-tpl__card"><div></div><span>Paper no. 3</span></div>
  </div>
</div>
HTML;
    }
}

/** Fill every field so templates never have to guess. */
function normalize_content(array $content, string $template = 'ledger'): array
{
    $defaults = starter_content($template);
    unset($defaults['_starter']);

    $content = array_merge($defaults, $content);
    $content['eyebrow'] = trim((string) ($content['eyebrow'] ?? ''));
    $content['headline'] = trim((string) ($content['headline'] ?? ''));
    $content['intro'] = trim((string) ($content['intro'] ?? ''));
    $content['about'] = trim((string) ($content['about'] ?? ''));
    $content['location'] = trim((string) ($content['location'] ?? ''));
    $content['email'] = trim((string) ($content['email'] ?? ''));
    $content['availability'] = trim((string) ($content['availability'] ?? ''));
    $content['accent'] = in_array($content['accent'] ?? '', ['blue', 'cyan', 'pale', 'navy'], true)
        ? $content['accent'] : 'blue';

    $links = [];
    foreach ((array) ($content['links'] ?? []) as $link) {
        if (!is_array($link)) {
            continue;
        }
        $label = trim((string) ($link['label'] ?? ''));
        $href = trim((string) ($link['url'] ?? ''));
        if ($label !== '' && $href !== '') {
            $links[] = ['label' => $label, 'url' => $href];
        }
    }
    $content['links'] = $links;

    $projects = [];
    foreach ((array) ($content['projects'] ?? []) as $project) {
        if (!is_array($project)) {
            continue;
        }
        $title = trim((string) ($project['title'] ?? ''));
        if ($title === '') {
            continue;
        }
        $projects[] = [
            'title' => $title,
            'year' => trim((string) ($project['year'] ?? '')),
            'role' => trim((string) ($project['role'] ?? '')),
            'summary' => trim((string) ($project['summary'] ?? '')),
            'image' => trim((string) ($project['image'] ?? '')),
            'url' => trim((string) ($project['url'] ?? '')),
        ];
    }
    $content['projects'] = $projects;

    return $content;
}

function site_initial_mark(array $site): string
{
    return mb_strtoupper(mb_substr((string) ($site['display_name'] ?? $site['title'] ?? 'P'), 0, 1));
}

/** Full HTML document for a template-mode site. */
function render_template_site(array $site, bool $embed = false, bool $preview = false): string
{
    $template = (string) $site['template'];
    if (!array_key_exists($template, portfolio_templates())) {
        $template = 'ledger';
    }
    $content = normalize_content(site_content($site), $template);
    $pageTitle = $site['title'];
    $description = trim((string) $site['description']);
    if ($description === '') {
        $description = excerpt($content['intro'] ?: $content['about'], 160);
    }
    $bodyClass = 'tpl tpl-' . $template . ' accent-' . $content['accent'];
    $canonical = absolute_url(site_path($site));

    ob_start();
    require APP_ROOT . '/views/sites/' . $template . '.php';
    return (string) ob_get_clean();
}

/** Full HTML document for a custom-coded site, with CSS/JS injected where they belong. */
function render_code_site(array $site): string
{
    $html = (string) $site['html'];
    $css = (string) $site['css'];
    $js = (string) $site['js'];
    $title = e($site['title']);
    $description = e((string) $site['description'] ?: 'A portfolio made with Portside.');

    $styleTag = $css !== '' ? "<style id=\"portside-user-css\">\n{$css}\n</style>" : '';
    $scriptTag = $js !== '' ? "<script id=\"portside-user-js\">\n{$js}\n</script>" : '';

    if (preg_match('/<html[\s>]/i', $html)) {
        $doc = $html;
        if (stripos($doc, '</head>') !== false) {
            $doc = preg_replace('/<\/head>/i', $styleTag . "\n</head>", $doc, 1) ?? $doc;
        } elseif (preg_match('/<body[\s>]/i', $doc)) {
            $doc = preg_replace('/<body([\s>])/i', "<head>{$styleTag}</head><body$1", $doc, 1) ?? $doc;
        } else {
            $doc = "<head>{$styleTag}</head>" . $doc;
        }
        if ($scriptTag !== '') {
            if (stripos($doc, '</body>') !== false) {
                $doc = preg_replace('/<\/body>/i', $scriptTag . "\n</body>", $doc, 1) ?? $doc;
            } else {
                $doc .= $scriptTag;
            }
        }
        return $doc;
    }

    return "<!doctype html>\n<html lang=\"en\">\n<head>\n<meta charset=\"utf-8\">\n"
        . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n"
        . "<title>{$title}</title>\n<meta name=\"description\" content=\"{$description}\">\n"
        . "<meta property=\"og:title\" content=\"{$title}\">\n"
        . "<meta property=\"og:description\" content=\"{$description}\">\n"
        . "<meta property=\"og:type\" content=\"website\">\n"
        . "<meta name=\"theme-color\" content=\"#1B365D\">\n"
        . $styleTag . "\n</head>\n<body>\n" . $html . "\n" . $scriptTag . "\n</body>\n</html>";
}

/** Shell used by the code preview iframe and the code guide. */
function code_preview_document(string $html, string $css, string $js): string
{
    return render_code_site([
        'html' => $html,
        'css' => $css,
        'js' => $js,
        'title' => 'Preview',
        'description' => '',
    ]);
}
