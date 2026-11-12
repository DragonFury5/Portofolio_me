<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\PortfolioView;
use App\Models\User;
use App\Services\CodeStarter;
use App\Services\TemplateRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $sharedPassword = Hash::make(str()->random(32));

        $users = [
            'demo' => [
                'name' => 'Demo Maker',
                'email' => 'demo@portside.test',
                'bio' => 'A demo account for looking around. Log in with demo@portside.test and portside-demo.',
                'location' => 'The harbour',
                'avatar' => 'assets/img/seed/avatar-demo.svg',
                'password' => Hash::make('portside-demo'),
                'is_admin' => false,
            ],
            'mara' => [
                'name' => 'Mara Ellison',
                'email' => 'mara@portside.test',
                'bio' => 'Graphic designer working on books, identities and printed matter. Rotterdam. One person, a plotter, and a growing shelf of paper samples.',
                'location' => 'Rotterdam, NL',
                'avatar' => 'assets/img/seed/avatar-mara.svg',
            ],
            'kaito' => [
                'name' => 'Kaito Mori',
                'email' => 'kaito@portside.test',
                'bio' => 'Photographer and art director in Osaka. Coasts, ferries, and the hour before a storm. Mostly on film.',
                'location' => 'Osaka, JP',
                'avatar' => 'assets/img/seed/avatar-kaito.svg',
            ],
            'priya' => [
                'name' => 'Priya Nair',
                'email' => 'priya@portside.test',
                'bio' => 'Front-end engineer and design-systems person. Bengaluru. Small components, honest performance budgets, long walks.',
                'location' => 'Bengaluru, IN',
                'avatar' => 'assets/img/seed/avatar-priya.svg',
            ],
            'tomas' => [
                'name' => 'Tomás Ferreira',
                'email' => 'tomas@portside.test',
                'bio' => 'Illustrator in Lisbon. Editorial pictures, packaging, and drawings with a little room around the edges.',
                'location' => 'Lisbon, PT',
                'avatar' => 'assets/img/seed/avatar-tomas.svg',
            ],
            'jules' => [
                'name' => 'Jules Park',
                'email' => 'jules@portside.test',
                'bio' => 'Motion designer and type person. Title sequences, identities, and interfaces that move like they mean it.',
                'location' => 'Seoul, KR',
                'avatar' => 'assets/img/seed/avatar-jules.svg',
            ],
            'nora' => [
                'name' => 'Nora Okafor',
                'email' => 'nora@portside.test',
                'bio' => 'Book designer and art director in Lagos. Long documents, short sentences, good paper.',
                'location' => 'Lagos, NG',
                'avatar' => 'assets/img/seed/avatar-nora.svg',
            ],
            'amara' => [
                'name' => 'Amara Bello',
                'email' => 'amara@portside.test',
                'bio' => 'Product designer working on services for people who are usually designed around. Lagos and London.',
                'location' => 'Lagos, NG',
                'avatar' => 'assets/img/seed/avatar-amara.svg',
            ],
            'ines' => [
                'name' => 'Inés Duarte',
                'email' => 'ines@portside.test',
                'bio' => 'Ceramicist in Porto. Tableware, small sculptures, and glazes that behave most of the time.',
                'location' => 'Porto, PT',
                'avatar' => 'assets/img/seed/avatar-ines.svg',
            ],
            'admin' => [
                'name' => 'Harbour Admin',
                'email' => 'admin@portside.test',
                'bio' => 'The moderation desk. Use this account to look at reports and feature pages.',
                'location' => 'The harbour',
                'avatar' => 'assets/img/seed/avatar-demo.svg',
                'password' => Hash::make('portside-admin'),
                'is_admin' => true,
            ],
        ];

        $userIds = [];
        $day = 80;

        foreach ($users as $username => $data) {
            $user = User::firstOrNew(['username' => $username]);
            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? $sharedPassword,
                'bio' => $data['bio'],
                'avatar' => $data['avatar'],
                'location' => $data['location'],
                'is_admin' => $data['is_admin'] ?? false,
                'email_verified_at' => $now,
                'created_at' => $now->copy()->subDays($day),
                'updated_at' => $now->copy()->subDays($day),
            ])->save();
            $userIds[$username] = $user->id;
            $day -= 4;
        }

        $projects = static fn (array $items): array => array_map(
            static fn (array $item): array => [
                'title' => $item[0],
                'year' => $item[1],
                'role' => $item[2],
                'summary' => $item[3],
                'image' => $item[4] ?? '',
                'url' => $item[5] ?? '',
            ],
            $items,
        );

        $starter = app(TemplateRegistry::class);
        $code = app(CodeStarter::class);
        $kinetic = $code->find('kinetic');
        $editorial = $code->find('editorial');

        $pages = [
            [
                'user' => 'mara', 'slug' => 'mara-ellison', 'template' => 'ledger', 'accent' => 'cyan',
                'title' => 'Mara Ellison — Graphic design',
                'description' => 'Independent graphic designer working on books, identities and printed matter. Rotterdam.',
                'featured' => true, 'published' => true, 'created' => 58, 'updated' => 2,
                'content' => [
                    'eyebrow' => 'Independent graphic designer · Rotterdam',
                    'headline' => 'Printed matter for people who still *read*.',
                    'intro' => 'I design books, identities and the occasional very stubborn poster for small organisations with long memories.',
                    'about' => 'My practice is one person, a plotter, and a growing shelf of paper samples. I work slowly on purpose: a good grid takes a week, and the second week is for taking things out. Clients include a ferry co-op, a public library, and two presses that still set type by hand.',
                    'location' => 'Rotterdam, NL',
                    'email' => 'hello@maraellison.test',
                    'availability' => 'Taking on books for autumn 2026',
                    'links' => [
                        ['label' => 'Instagram', 'url' => 'https://www.instagram.com'],
                        ['label' => 'Are.na', 'url' => 'https://www.are.na'],
                    ],
                    'projects' => $projects([
                        ['Harbor identity', '2025', 'Identity, print', 'A visual system for a small ferry co-op: wayfinding, tickets, and a mark that survives salt water.', 'assets/img/seed/harbor-mark.svg'],
                        ['Silt, a type specimen', '2024', 'Type design', 'A workhorse serif drawn for long reports and short captions. Four weights, one stubborn italic.', 'assets/img/seed/silt-specimen.svg'],
                        ['Civic type', '2024', 'Editorial', 'A 96-page annual report for a public library, designed to be read rather than admired from a distance.', 'assets/img/seed/civic-type.svg'],
                        ['Northbound', '2023', 'Poster series', 'Six posters for a night ferry route. Two colours, one paper, no photography.', 'assets/img/seed/northbound.svg'],
                    ]),
                ],
            ],
            [
                'user' => 'kaito', 'slug' => 'kaito-mori', 'template' => 'atlas', 'accent' => 'cyan',
                'title' => 'Kaito Mori — Photography',
                'description' => 'Photographer and art director in Osaka. Coasts, ferries, and the hour before a storm.',
                'featured' => true, 'published' => true, 'created' => 52, 'updated' => 4,
                'content' => [
                    'eyebrow' => 'Photographer · Osaka',
                    'headline' => 'Light, weather, and the occasional *accident*.',
                    'intro' => 'Photography and art direction for places that are easier to feel than describe.',
                    'about' => 'I photograph the working edge of cities: ports, ferries, the hour before a storm. Mostly on film, sometimes not. Commissions and personal series, printed as often as possible. The sea is not a backdrop; it is a client with strong opinions.',
                    'location' => 'Osaka, JP',
                    'email' => 'studio@kaitomori.test',
                    'availability' => 'Booking autumn commissions',
                    'links' => [
                        ['label' => 'Instagram', 'url' => 'https://www.instagram.com'],
                        ['label' => 'Print shop', 'url' => 'https://example.com/prints'],
                    ],
                    'projects' => $projects([
                        ['Night ferry', '2026', 'Personal series', 'Twelve crossings after midnight. Available as a newsprint zine in the autumn.', 'assets/img/seed/night-ferry.svg'],
                        ['Low tide, 6pm', '2025', 'Editorial', 'Commissioned by a coastal science quarterly. Shot over one week in November.', 'assets/img/seed/low-tide.svg'],
                        ['Folded coast', '2025', 'Art direction', 'A campaign about erosion, printed on paper that refuses to lie flat.', 'assets/img/seed/folded-coast.svg'],
                        ['The long wave', '2024', 'Personal series', 'A single wave photographed for forty minutes. Twenty frames survived.', 'assets/img/seed/long-wave.svg'],
                    ]),
                ],
            ],
            [
                'user' => 'priya', 'slug' => 'priya-nair', 'template' => 'signal', 'accent' => 'blue',
                'title' => 'Priya Nair — Front-end engineer',
                'description' => 'Front-end engineer working on design systems, performance, and the unglamorous parts of shipping.',
                'featured' => false, 'published' => true, 'created' => 47, 'updated' => 3,
                'content' => [
                    'eyebrow' => 'Front-end engineer · Bengaluru',
                    'headline' => 'I build interfaces that *hold up* under real use.',
                    'intro' => 'Front-end, design systems, and the unglamorous parts of shipping software that people depend on.',
                    'about' => 'Eight years between design and engineering. I like small components, honest performance budgets, and documentation a new teammate can follow without a meeting. Currently contracting from Bengaluru, usually with teams of five to fifty.',
                    'location' => 'Bengaluru, IN · UTC+5:30',
                    'email' => 'hi@priyanair.test',
                    'availability' => 'Open to contract work from January',
                    'links' => [
                        ['label' => 'GitHub', 'url' => 'https://github.com'],
                        ['label' => 'Read.cv', 'url' => 'https://read.cv'],
                    ],
                    'projects' => $projects([
                        ['Atlas design system', '2026', 'Lead engineer', 'A 40-component system used by four product teams. Documented, tested, and smaller than the last one.'],
                        ['Tidepool CLI', '2025', 'Author, open source', 'A small tool for replaying failed network requests locally. 1.2k stars and no analytics.'],
                        ['Reading room', '2024', 'Performance', 'Cut a media archive’s largest page from 4.1 MB to 380 KB without losing a single photograph.'],
                    ]),
                ],
            ],
            [
                'user' => 'tomas', 'slug' => 'tomas-ferreira', 'template' => 'tide', 'accent' => 'blue',
                'title' => 'Tomás Ferreira — Illustration',
                'description' => 'Illustrator in Lisbon. Editorial pictures, packaging, and drawings with room around the edges.',
                'featured' => false, 'published' => true, 'created' => 41, 'updated' => 5,
                'content' => [
                    'eyebrow' => 'Illustrator · Lisbon',
                    'headline' => 'Pictures for slow mornings and loud *ideas*.',
                    'intro' => 'Editorial illustration, packaging, and drawings that leave a little room around the edges.',
                    'about' => 'I work in gouache, pencil and a scanner that has seen better days. Clients include small magazines, food labels, and the occasional stubborn book cover. The kettle is always on; the radio is usually off.',
                    'location' => 'Lisbon, PT',
                    'email' => 'ola@tomasferreira.test',
                    'availability' => 'Two slots open this month',
                    'links' => [
                        ['label' => 'Instagram', 'url' => 'https://www.instagram.com'],
                        ['label' => 'Shop', 'url' => 'https://example.com/shop'],
                    ],
                    'projects' => $projects([
                        ['Quay study', '2026', 'Editorial', 'A series about the working edge of a harbour, drawn from the same bench over three seasons.', 'assets/img/seed/quay-study.svg'],
                        ['Paper no. 3', '2025', 'Packaging', 'Wrapping paper for a stationery shop that only sells three things. Ink on uncoated stock.', 'assets/img/seed/paper-no3.svg'],
                        ['Long wave', '2025', 'Book cover', 'A cover for a novel about a swimmer. The wave had to look like it was already moving.', 'assets/img/seed/long-wave.svg'],
                    ]),
                ],
            ],
            [
                'user' => 'amara', 'slug' => 'amara-bello', 'template' => 'signal', 'accent' => 'cyan',
                'title' => 'Amara Bello — Product design',
                'description' => 'Product designer working on services for people who are usually designed around. Lagos and London.',
                'featured' => false, 'published' => true, 'created' => 36, 'updated' => 6,
                'content' => [
                    'eyebrow' => 'Product designer · Lagos / London',
                    'headline' => 'Designing quiet tools for *loud* problems.',
                    'intro' => 'Service design, research, and interfaces for people who have better things to do than learn your product.',
                    'about' => 'I work with public-interest teams on payments, health and civic services. Most of the job is listening, then removing three steps from a form. I care about the person at the back of the queue with a low battery.',
                    'location' => 'Lagos, NG · London, UK',
                    'email' => 'amara@amarabello.test',
                    'availability' => 'One project from March',
                    'links' => [
                        ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com'],
                        ['label' => 'Writing', 'url' => 'https://example.com/notes'],
                    ],
                    'projects' => $projects([
                        ['Market payments', '2025', 'Service design', 'A payments flow for traders who do not trust forms. Tested in three markets, then cut in half.'],
                        ['The clinic queue', '2024', 'Research & UI', 'A check-in system for a public clinic. Fewer screens, more chairs, one less argument at the desk.'],
                        ['Field notes', '2023', 'Writing', 'A small collection of things I got wrong while designing for other people.'],
                    ]),
                ],
            ],
            [
                'user' => 'ines', 'slug' => 'ines-duarte', 'template' => 'tide', 'accent' => 'navy',
                'title' => 'Inés Duarte — Ceramics',
                'description' => 'Ceramicist in Porto. Tableware, small sculptures, and glazes that behave most of the time.',
                'featured' => false, 'published' => true, 'created' => 33, 'updated' => 8,
                'content' => [
                    'eyebrow' => 'Ceramicist · Porto',
                    'headline' => 'Objects for the table, made *slowly*.',
                    'intro' => 'Stoneware, slip, and glazes mixed in a small studio by the river.',
                    'about' => 'I throw on a wheel that is older than me and fire in a kiln shared with two neighbours. Every piece is a little different; that is the point. Commissions for restaurants and people who like their mugs to have a history.',
                    'location' => 'Porto, PT',
                    'email' => 'ines@inesduarte.test',
                    'availability' => 'Studio visits by appointment',
                    'links' => [
                        ['label' => 'Instagram', 'url' => 'https://www.instagram.com'],
                    ],
                    'projects' => $projects([
                        ['Tidal index', '2026', 'Tableware', 'A set of twelve plates whose rims follow a tide chart. Made for a small restaurant in Matosinhos.', 'assets/img/seed/tidal-index.svg'],
                        ['Paper no. 3', '2025', 'Vases', 'Three vessels glazed with a pale blue that only appears when the kiln cools slowly.', 'assets/img/seed/paper-no3.svg'],
                        ['Quay study', '2024', 'Sculpture', 'A clay study of the harbour wall, fired in one piece and slightly warped on purpose.', 'assets/img/seed/quay-study.svg'],
                    ]),
                ],
            ],
            [
                'user' => 'jules', 'slug' => 'jules-park', 'mode' => 'code',
                'title' => 'Jules Park — Motion & type',
                'description' => 'Motion designer and type person. Title sequences, identities and interfaces that move like they mean it.',
                'featured' => true, 'published' => true, 'created' => 29, 'updated' => 1,
                'html' => str_replace('hello@example.com', 'hello@julespark.test', $kinetic['html']),
                'css' => $kinetic['css'],
                'js' => $kinetic['js'],
            ],
            [
                'user' => 'nora', 'slug' => 'nora-okafor', 'mode' => 'code',
                'title' => 'Nora Okafor — Books & identities',
                'description' => 'Book designer and art director in Lagos. Long documents, short sentences, good paper.',
                'featured' => false, 'published' => true, 'created' => 24, 'updated' => 7,
                'html' => str_replace(
                    ['Harbour<br>Notes', 'hello@example.com'],
                    ['Nora<br>Okafor', 'hello@noraokafor.test'],
                    $editorial['html']
                ),
                'css' => $editorial['css'],
                'js' => $editorial['js'],
            ],
            [
                'user' => 'demo', 'slug' => 'demo-maker', 'template' => 'ledger', 'accent' => 'blue',
                'title' => 'Demo Maker — Starter portfolio',
                'description' => 'A demo page you can pull apart. Open the editor, change anything.',
                'featured' => false, 'published' => true, 'created' => 14, 'updated' => 1,
                'content' => [
                    'eyebrow' => 'Demo account · Portside',
                    'headline' => 'A demo page you can *pull apart*.',
                    'intro' => 'Log in with the demo account, open the editor, change anything. This page is here to be broken.',
                    'about' => 'Everything here is editable: the words, the projects, the template, even the code. Nothing you do in the demo account touches the other makers’ pages. Break it, refresh, start again.',
                    'location' => 'The harbour',
                    'email' => 'demo@portside.test',
                    'availability' => 'Open to experiments',
                    'links' => [
                        ['label' => 'Code guide', 'url' => '/docs/code'],
                        ['label' => 'Explore', 'url' => '/explore'],
                    ],
                    'projects' => $projects([
                        ['Harbor identity', '2025', 'Identity, print', 'Sample project one. Replace it with something you made.', 'assets/img/seed/harbor-mark.svg'],
                        ['Silt, a type specimen', '2024', 'Type design', 'Sample project two. The image upload lives in the editor.', 'assets/img/seed/silt-specimen.svg'],
                        ['Civic type', '2024', 'Editorial', 'Sample project three. Delete it and the layout keeps working.', 'assets/img/seed/civic-type.svg'],
                    ]),
                ],
            ],
            [
                'user' => 'demo', 'slug' => 'demo-draft', 'template' => 'atlas', 'accent' => 'cyan',
                'title' => 'A second page, still a draft',
                'description' => 'A draft page. Only the demo account can see it until it is published.',
                'featured' => false, 'published' => false, 'created' => 6, 'updated' => 3,
                'content' => $starter->starterContent('atlas', 'Demo Maker'),
            ],
        ];

        foreach ($pages as $index => $page) {
            $created = $now->copy()->subDays($page['created']);
            $updated = $now->copy()->subDays($page['updated']);
            $isCode = ($page['mode'] ?? 'template') === 'code';
            $publishedAt = $page['published'] ? $created->copy()->addDays(2) : null;

            $portfolio = Portfolio::firstOrNew(['slug' => $page['slug']]);
            $portfolio->forceFill([
                'user_id' => $userIds[$page['user']],
                'title' => $page['title'],
                'description' => $page['description'],
                'mode' => $isCode ? Portfolio::MODE_CODE : Portfolio::MODE_TEMPLATE,
                'template' => $isCode ? null : $page['template'],
                'accent' => $isCode ? 'blue' : $page['accent'],
                'content' => $isCode ? [] : $page['content'],
                'html' => $isCode ? $page['html'] : '',
                'css' => $isCode ? $page['css'] : '',
                'js' => $isCode ? $page['js'] : '',
                'is_published' => (bool) $page['published'],
                'is_featured' => (bool) $page['featured'],
                'published_at' => $publishedAt,
                'created_at' => $created,
                'updated_at' => $updated,
            ])->save();

            if (! $page['published']) {
                continue;
            }

            $total = 0;

            for ($d = 13; $d >= 0; $d--) {
                $count = (($index * 7 + $d * 5 + $portfolio->id * 3) % 17) + 1;
                if ($isCode) {
                    $count += 4;
                }
                PortfolioView::updateOrCreate(
                    ['portfolio_id' => $portfolio->id, 'day' => $now->copy()->subDays($d)->toDateString()],
                    ['count' => $count],
                );
                $total += $count;
            }

            $portfolio->forceFill(['views' => $total])->save();
        }
    }
}
