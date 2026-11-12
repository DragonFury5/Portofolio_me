<?php

namespace App\Services;

use Illuminate\Support\Str;

class TemplateRegistry
{
    public const MAX_PAGES = 4;

    public const LIMITS = [
        'html' => 300_000,
        'css' => 200_000,
        'js' => 200_000,
        'image_kb' => 5120,
        'projects' => 12,
        'links' => 8,
    ];

    public const ACCENTS = [
        'blue' => 'Harbour blue',
        'cyan' => 'Signal cyan',
        'pale' => 'Sea glass',
        'navy' => 'Deep navy',
    ];

    /**
     * @return array<string, array<string, string>>
     */
    public function all(): array
    {
        return [
            'ledger' => [
                'key' => 'ledger',
                'name' => 'Ledger',
                'kicker' => 'Editorial index',
                'description' => 'A quiet, print-shaped index for designers, writers and studios.',
                'long' => 'Numbered project rows, a generous serif and room to explain the thinking. Built for work that needs context more than spectacle.',
                'best' => 'Graphic design · Type · Writing · Research',
                'accent' => 'cyan',
            ],
            'atlas' => [
                'key' => 'atlas',
                'name' => 'Atlas',
                'kicker' => 'Image first',
                'description' => 'A dark, image-led book for photographers and art directors.',
                'long' => 'Full-bleed colour, asymmetric plates and captions that stay out of the way. Your images do the talking; the grid keeps them in line.',
                'best' => 'Photography · Art direction · Film',
                'accent' => 'cyan',
            ],
            'signal' => [
                'key' => 'signal',
                'name' => 'Signal',
                'kicker' => 'Engineer’s page',
                'description' => 'A two-column, get-to-the-point page for people who build software.',
                'long' => 'A fixed profile rail, a dense project ledger and monospace metadata. Made for repos, systems, and work you can point at.',
                'best' => 'Engineering · Product · Data',
                'accent' => 'blue',
            ],
            'tide' => [
                'key' => 'tide',
                'name' => 'Tide',
                'kicker' => 'Horizontal rail',
                'description' => 'A soft, roomy page with a sideways project rail for illustrators and makers.',
                'long' => 'Oversized type, a scrolling shelf of work and a portrait mark that feels drawn rather than photographed. Good for work that should be browsed.',
                'best' => 'Illustration · Ceramics · Product',
                'accent' => 'blue',
            ],
        ];
    }

    public function keys(): array
    {
        return array_keys($this->all());
    }

    public function exists(?string $key): bool
    {
        return is_string($key) && array_key_exists($key, $this->all());
    }

    /**
     * @return array<string, string>
     */
    public function find(?string $key): array
    {
        $all = $this->all();

        if (! is_string($key) || ! isset($all[$key])) {
            return $all['ledger'];
        }

        return $all[$key];
    }

    public function normalizeAccent(?string $accent): string
    {
        return is_string($accent) && array_key_exists($accent, self::ACCENTS)
            ? $accent
            : 'blue';
    }

    /**
     * @return array<string, mixed>
     */
    public function starterContent(string $template, string $name = 'Your name'): array
    {
        $projects = [
            'ledger' => [
                $this->project('Harbor identity', '2025', 'Identity, print', 'A visual system for a small ferry co-op: wayfinding, tickets, and a mark that survives salt water.', 'assets/img/seed/harbor-mark.svg'),
                $this->project('Silt, a type specimen', '2024', 'Type design', 'A workhorse serif drawn for long reports and short captions. Four weights, one stubborn italic.', 'assets/img/seed/silt-specimen.svg'),
                $this->project('Civic type', '2024', 'Editorial', 'A 96-page annual report for a public library, designed to be read rather than admired from a distance.', 'assets/img/seed/civic-type.svg'),
            ],
            'atlas' => [
                $this->project('Night ferry', '2026', 'Personal series', 'Twelve crossings after midnight. Available as a newsprint zine in the autumn.', 'assets/img/seed/night-ferry.svg'),
                $this->project('Low tide, 6pm', '2025', 'Editorial', 'Commissioned by a coastal science quarterly. Shot over one week in November.', 'assets/img/seed/low-tide.svg'),
                $this->project('Folded coast', '2025', 'Art direction', 'A campaign about erosion, printed on paper that refuses to lie flat.', 'assets/img/seed/folded-coast.svg'),
            ],
            'signal' => [
                $this->project('Atlas design system', '2026', 'Lead engineer', 'A 40-component system used by four product teams. Documented, tested, and smaller than the last one.'),
                $this->project('Tidepool CLI', '2025', 'Author, open source', 'A small tool for replaying failed network requests locally. 1.2k stars and no analytics.'),
                $this->project('Reading room', '2024', 'Performance', 'Cut a media archive’s largest page from 4.1 MB to 380 KB without losing a single photograph.'),
            ],
            'tide' => [
                $this->project('Quay study', '2026', 'Editorial', 'A series about the working edge of a harbour, drawn from the same bench over three seasons.', 'assets/img/seed/quay-study.svg'),
                $this->project('Paper no. 3', '2025', 'Packaging', 'Wrapping paper for a stationery shop that only sells three things. Ink on uncoated stock.', 'assets/img/seed/paper-no3.svg'),
                $this->project('Long wave', '2025', 'Book cover', 'A cover for a novel about a swimmer. The wave had to look like it was already moving.', 'assets/img/seed/long-wave.svg'),
            ],
        ];

        $copy = [
            'ledger' => [
                'eyebrow' => 'Independent designer · Your city',
                'headline' => 'I make *considered* things for screens and paper.',
                'intro' => 'A short line about what you do and who you do it for. Replace this with your own.',
                'about' => 'Two or three sentences about your practice, how you work, and what you care about. This starter copy is here so the page never looks empty.',
                'availability' => 'Available for select projects',
            ],
            'atlas' => [
                'eyebrow' => 'Photographer · Your city',
                'headline' => 'Light, weather, and the occasional *accident*.',
                'intro' => 'Photography and art direction for places that are easier to feel than describe.',
                'about' => 'I photograph coasts, ferries and the hour before a storm. Commissions and editorial work, mostly on film, sometimes not.',
                'availability' => 'Booking autumn commissions',
            ],
            'signal' => [
                'eyebrow' => 'Front-end engineer · Your city',
                'headline' => 'I build interfaces that *hold up* under real use.',
                'intro' => 'Front-end, design systems, and the unglamorous parts of shipping software that people depend on.',
                'about' => 'Eight years between design and engineering. I like small components, honest performance budgets, and documentation that a new teammate can actually follow.',
                'availability' => 'Open to contract work',
            ],
            'tide' => [
                'eyebrow' => 'Illustrator · Your city',
                'headline' => 'Pictures for slow mornings and loud *ideas*.',
                'intro' => 'Editorial illustration, packaging, and drawings that leave a little room around the edges.',
                'about' => 'I work in gouache, pencil and a scanner that has seen better days. Clients include small magazines, food labels, and the occasional stubborn book cover.',
                'availability' => 'Two slots open this month',
            ],
        ];

        $template = $this->exists($template) ? $template : 'ledger';

        return [
            'eyebrow' => $copy[$template]['eyebrow'],
            'headline' => $copy[$template]['headline'],
            'intro' => $copy[$template]['intro'],
            'about' => $copy[$template]['about'],
            'location' => 'Your city',
            'email' => '',
            'availability' => $copy[$template]['availability'],
            'links' => [],
            'projects' => $projects[$template],
            '_starter' => true,
            '_name' => $name,
        ];
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    public function normalizeContent(array $content, ?string $template = 'ledger', ?string $accent = 'blue'): array
    {
        $template = $this->exists($template) ? $template : 'ledger';
        $defaults = $this->starterContent($template);
        unset($defaults['_starter'], $defaults['_name'], $defaults['projects']);

        $content = array_merge($defaults, $content);

        foreach (['eyebrow', 'headline', 'intro', 'about', 'location', 'email', 'availability'] as $field) {
            $content[$field] = trim((string) ($content[$field] ?? ''));
        }

        $content['headline'] = mb_substr($content['headline'], 0, 220);
        $content['eyebrow'] = mb_substr($content['eyebrow'], 0, 120);
        $content['intro'] = mb_substr($content['intro'], 0, 500);
        $content['about'] = mb_substr($content['about'], 0, 1800);
        $content['location'] = mb_substr($content['location'], 0, 100);
        $content['email'] = mb_substr($content['email'], 0, 190);
        $content['availability'] = mb_substr($content['availability'], 0, 120);

        $links = [];

        foreach (array_slice((array) ($content['links'] ?? []), 0, self::LIMITS['links']) as $link) {
            if (! is_array($link)) {
                continue;
            }

            $label = trim((string) ($link['label'] ?? ''));
            $url = trim((string) ($link['url'] ?? ''));

            if ($label === '' || $url === '') {
                continue;
            }

            $links[] = [
                'label' => mb_substr($label, 0, 40),
                'url' => mb_substr($url, 0, 500),
            ];
        }

        $content['links'] = $links;
        $rawProjects = (array) ($content['projects'] ?? []);
        $content['projects'] = [];

        foreach (array_slice($rawProjects, 0, self::LIMITS['projects']) as $project) {
            if (! is_array($project)) {
                continue;
            }

            $title = trim((string) ($project['title'] ?? ''));

            if ($title === '') {
                continue;
            }

            $content['projects'][] = [
                'title' => mb_substr($title, 0, 100),
                'year' => mb_substr(trim((string) ($project['year'] ?? '')), 0, 20),
                'role' => mb_substr(trim((string) ($project['role'] ?? '')), 0, 80),
                'summary' => mb_substr(trim((string) ($project['summary'] ?? '')), 0, 500),
                'image' => mb_substr(trim((string) ($project['image'] ?? '')), 0, 500),
                'url' => mb_substr(trim((string) ($project['url'] ?? '')), 0, 500),
            ];
        }

        $content['accent'] = $this->normalizeAccent($accent);
        unset($content['_starter'], $content['_name']);

        return $content;
    }

    /** @return array<string, string> */
    private function project(
        string $title,
        string $year,
        string $role,
        string $summary,
        string $image = '',
        string $url = ''
    ): array {
        return compact('title', 'year', 'role', 'summary', 'image', 'url');
    }

    public function titleFor(string $displayName): string
    {
        return Str::limit($displayName, 78, '').' — Portfolio';
    }
}
