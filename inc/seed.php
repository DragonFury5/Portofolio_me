<?php
declare(strict_types=1);

/**
 * First-run demo content. Fictional makers, fictional work, real layouts.
 * The demo account is the only one with a known password; the rest use random ones.
 */
function seed_database(PDO $pdo): void
{
    $now = time();
    $sharedHash = password_hash(bin2hex(random_bytes(18)), PASSWORD_DEFAULT);
    $demoHash = password_hash('portside-demo', PASSWORD_DEFAULT);
    $userIds = [];

    $users = [
        'demo' => [
            'email' => 'demo@portside.example',
            'name' => 'Demo Maker',
            'bio' => 'A demo account for looking around. Log in with demo@portside.example and portside-demo.',
            'location' => 'The harbour',
            'avatar' => 'assets/img/seed/avatar-demo.svg',
            'hash' => $demoHash,
        ],
        'mara' => [
            'email' => 'mara@portside.example',
            'name' => 'Mara Ellison',
            'bio' => 'Graphic designer working on books, identities and printed matter. Rotterdam. One person, a plotter, and a growing shelf of paper samples.',
            'location' => 'Rotterdam, NL',
            'avatar' => 'assets/img/seed/avatar-mara.svg',
            'hash' => $sharedHash,
        ],
        'kaito' => [
            'email' => 'kaito@portside.example',
            'name' => 'Kaito Mori',
            'bio' => 'Photographer and art director in Osaka. Coasts, ferries, and the hour before a storm. Mostly on film.',
            'location' => 'Osaka, JP',
            'avatar' => 'assets/img/seed/avatar-kaito.svg',
            'hash' => $sharedHash,
        ],
        'priya' => [
            'email' => 'priya@portside.example',
            'name' => 'Priya Nair',
            'bio' => 'Front-end engineer and design-systems person. Bengaluru. Small components, honest performance budgets, long walks.',
            'location' => 'Bengaluru, IN',
            'avatar' => 'assets/img/seed/avatar-priya.svg',
            'hash' => $sharedHash,
        ],
        'tomas' => [
            'email' => 'tomas@portside.example',
            'name' => 'Tomás Ferreira',
            'bio' => 'Illustrator in Lisbon. Editorial pictures, packaging, and drawings with a little room around the edges.',
            'location' => 'Lisbon, PT',
            'avatar' => 'assets/img/seed/avatar-tomas.svg',
            'hash' => $sharedHash,
        ],
        'jules' => [
            'email' => 'jules@portside.example',
            'name' => 'Jules Park',
            'bio' => 'Motion designer and type person. Title sequences, identities, and interfaces that move like they mean it.',
            'location' => 'Seoul, KR',
            'avatar' => 'assets/img/seed/avatar-jules.svg',
            'hash' => $sharedHash,
        ],
        'nora' => [
            'email' => 'nora@portside.example',
            'name' => 'Nora Okafor',
            'bio' => 'Book designer and art director in Lagos. Long documents, short sentences, good paper.',
            'location' => 'Lagos, NG',
            'avatar' => 'assets/img/seed/avatar-nora.svg',
            'hash' => $sharedHash,
        ],
        'amara' => [
            'email' => 'amara@portside.example',
            'name' => 'Amara Bello',
            'bio' => 'Product designer working on services for people who are usually designed around. Lagos and London.',
            'location' => 'Lagos, NG',
            'avatar' => 'assets/img/seed/avatar-amara.svg',
            'hash' => $sharedHash,
        ],
        'ines' => [
            'email' => 'ines@portside.example',
            'name' => 'Inés Duarte',
            'bio' => 'Ceramicist in Porto. Tableware, small sculptures, and glazes that behave most of the time.',
            'location' => 'Porto, PT',
            'avatar' => 'assets/img/seed/avatar-ines.svg',
            'hash' => $sharedHash,
        ],
    ];

    $insertUser = $pdo->prepare(
        'INSERT INTO users (username, email, password_hash, display_name, bio, avatar, location, is_admin, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?)'
    );

    $i = 0;
    foreach ($users as $username => $user) {
        $created = gmdate('Y-m-d H:i:s', $now - (86400 * (60 - $i * 3)));
        $insertUser->execute([
            $username,
            $user['email'],
            $user['hash'],
            $user['name'],
            $user['bio'],
            $user['avatar'],
            $user['location'],
            $created,
        ]);
        $userIds[$username] = (int) $pdo->lastInsertId();
        $i++;
    }

    $projects = static function (array $items): array {
        return array_map(static function (array $item): array {
            return [
                'title' => $item[0],
                'year' => $item[1],
                'role' => $item[2],
                'summary' => $item[3],
                'image' => $item[4] ?? '',
                'url' => $item[5] ?? '',
            ];
        }, $items);
    };

    $sites = [
        [
            'user' => 'mara',
            'slug' => 'mara-ellison',
            'title' => 'Mara Ellison — Graphic design',
            'description' => 'Independent graphic designer working on books, identities and printed matter. Rotterdam.',
            'mode' => 'template',
            'template' => 'ledger',
            'featured' => 1,
            'published' => 1,
            'created' => 58,
            'updated' => 2,
            'content' => [
                'eyebrow' => 'Independent graphic designer · Rotterdam',
                'headline' => 'Printed matter for people who still *read*.',
                'intro' => 'I design books, identities and the occasional very stubborn poster for small organisations with long memories.',
                'about' => 'My practice is one person, a plotter, and a growing shelf of paper samples. I work slowly on purpose: a good grid takes a week, and the second week is for taking things out. Clients include a ferry co-op, a public library, and two presses that still set type by hand.',
                'location' => 'Rotterdam, NL',
                'email' => 'hello@maraellison.example',
                'availability' => 'Taking on books for autumn 2026',
                'accent' => 'cyan',
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
            'user' => 'kaito',
            'slug' => 'kaito-mori',
            'title' => 'Kaito Mori — Photography',
            'description' => 'Photographer and art director in Osaka. Coasts, ferries, and the hour before a storm.',
            'mode' => 'template',
            'template' => 'atlas',
            'featured' => 1,
            'published' => 1,
            'created' => 52,
            'updated' => 4,
            'content' => [
                'eyebrow' => 'Photographer · Osaka',
                'headline' => 'Light, weather, and the occasional *accident*.',
                'intro' => 'Photography and art direction for places that are easier to feel than describe.',
                'about' => 'I photograph the working edge of cities: ports, ferries, the hour before a storm. Mostly on film, sometimes not. Commissions and personal series, printed as often as possible. The sea is not a backdrop; it is a client with strong opinions.',
                'location' => 'Osaka, JP',
                'email' => 'studio@kaitomori.example',
                'availability' => 'Booking autumn commissions',
                'accent' => 'cyan',
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
            'user' => 'priya',
            'slug' => 'priya-nair',
            'title' => 'Priya Nair — Front-end engineer',
            'description' => 'Front-end engineer working on design systems, performance, and the unglamorous parts of shipping.',
            'mode' => 'template',
            'template' => 'signal',
            'featured' => 0,
            'published' => 1,
            'created' => 47,
            'updated' => 3,
            'content' => [
                'eyebrow' => 'Front-end engineer · Bengaluru',
                'headline' => 'I build interfaces that *hold up* under real use.',
                'intro' => 'Front-end, design systems, and the unglamorous parts of shipping software that people depend on.',
                'about' => 'Eight years between design and engineering. I like small components, honest performance budgets, and documentation a new teammate can follow without a meeting. Currently contracting from Bengaluru, usually with teams of five to fifty.',
                'location' => 'Bengaluru, IN · UTC+5:30',
                'email' => 'hi@priyanair.example',
                'availability' => 'Open to contract work from January',
                'accent' => 'blue',
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
            'user' => 'tomas',
            'slug' => 'tomas-ferreira',
            'title' => 'Tomás Ferreira — Illustration',
            'description' => 'Illustrator in Lisbon. Editorial pictures, packaging, and drawings with room around the edges.',
            'mode' => 'template',
            'template' => 'tide',
            'featured' => 0,
            'published' => 1,
            'created' => 41,
            'updated' => 5,
            'content' => [
                'eyebrow' => 'Illustrator · Lisbon',
                'headline' => 'Pictures for slow mornings and loud *ideas*.',
                'intro' => 'Editorial illustration, packaging, and drawings that leave a little room around the edges.',
                'about' => 'I work in gouache, pencil and a scanner that has seen better days. Clients include small magazines, food labels, and the occasional stubborn book cover. The kettle is always on; the radio is usually off.',
                'location' => 'Lisbon, PT',
                'email' => 'ola@tomasferreira.example',
                'availability' => 'Two slots open this month',
                'accent' => 'blue',
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
            'user' => 'amara',
            'slug' => 'amara-bello',
            'title' => 'Amara Bello — Product design',
            'description' => 'Product designer working on services for people who are usually designed around. Lagos and London.',
            'mode' => 'template',
            'template' => 'signal',
            'featured' => 0,
            'published' => 1,
            'created' => 36,
            'updated' => 6,
            'content' => [
                'eyebrow' => 'Product designer · Lagos / London',
                'headline' => 'Designing quiet tools for *loud* problems.',
                'intro' => 'Service design, research, and interfaces for people who have better things to do than learn your product.',
                'about' => 'I work with public-interest teams on payments, health and civic services. Most of the job is listening, then removing three steps from a form. I care about the person at the back of the queue with a low battery.',
                'location' => 'Lagos, NG · London, UK',
                'email' => 'amara@amarabello.example',
                'availability' => 'One project from March',
                'accent' => 'cyan',
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
            'user' => 'ines',
            'slug' => 'ines-duarte',
            'title' => 'Inés Duarte — Ceramics',
            'description' => 'Ceramicist in Porto. Tableware, small sculptures, and glazes that behave most of the time.',
            'mode' => 'template',
            'template' => 'tide',
            'featured' => 0,
            'published' => 1,
            'created' => 33,
            'updated' => 8,
            'content' => [
                'eyebrow' => 'Ceramicist · Porto',
                'headline' => 'Objects for the table, made *slowly*.',
                'intro' => 'Stoneware, slip, and glazes mixed in a small studio by the river.',
                'about' => 'I throw on a wheel that is older than me and fire in a kiln shared with two neighbours. Every piece is a little different; that is the point. Commissions for restaurants and people who like their mugs to have a history.',
                'location' => 'Porto, PT',
                'email' => 'ines@inesduarte.example',
                'availability' => 'Studio visits by appointment',
                'accent' => 'navy',
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
            'user' => 'jules',
            'slug' => 'jules-park',
            'title' => 'Jules Park — Motion & type',
            'description' => 'Motion designer and type person. Title sequences, identities and interfaces that move like they mean it.',
            'mode' => 'code',
            'template' => 'code',
            'featured' => 1,
            'published' => 1,
            'created' => 29,
            'updated' => 1,
            'content' => [],
        ],
        [
            'user' => 'nora',
            'slug' => 'nora-okafor',
            'title' => 'Nora Okafor — Books & identities',
            'description' => 'Book designer and art director in Lagos. Long documents, short sentences, good paper.',
            'mode' => 'code',
            'template' => 'code',
            'featured' => 0,
            'published' => 1,
            'created' => 24,
            'updated' => 7,
            'content' => [],
        ],
        [
            'user' => 'demo',
            'slug' => 'demo-maker',
            'title' => 'Demo Maker — Starter portfolio',
            'description' => 'A demo page you can pull apart. Open the editor, change anything.',
            'mode' => 'template',
            'template' => 'ledger',
            'featured' => 0,
            'published' => 1,
            'created' => 14,
            'updated' => 1,
            'content' => [
                'eyebrow' => 'Demo account · Portside',
                'headline' => 'A demo page you can *pull apart*.',
                'intro' => 'Log in with the demo account, open the editor, change anything. This page is here to be broken.',
                'about' => 'Everything here is editable: the words, the projects, the template, even the code. Nothing you do in the demo account touches the other makers’ pages. Break it, refresh, start again.',
                'location' => 'The harbour',
                'email' => 'demo@portside.example',
                'availability' => 'Open to experiments',
                'accent' => 'blue',
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
            'user' => 'demo',
            'slug' => 'demo-draft',
            'title' => 'Untitled page',
            'description' => 'A second page, still in draft.',
            'mode' => 'template',
            'template' => 'atlas',
            'featured' => 0,
            'published' => 0,
            'created' => 6,
            'updated' => 3,
            'content' => starter_content('atlas', 'Demo Maker'),
        ],
    ];

    $insertSite = $pdo->prepare(
        'INSERT INTO sites (user_id, slug, title, description, mode, template, content_json, html, css, js,
            is_published, is_featured, views, published_at, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?)'
    );
    $insertDay = $pdo->prepare(
        'INSERT INTO site_views (site_id, day, count) VALUES (?, ?, ?)
         ON CONFLICT(site_id, day) DO UPDATE SET count = excluded.count'
    );

    $kinetic = code_starter('kinetic');
    $editorial = code_starter('editorial');
    $codePayload = [
        'jules' => [
            'html' => str_replace('hello@example.com', 'hello@julespark.example', $kinetic['html']),
            'css' => $kinetic['css'],
            'js' => $kinetic['js'],
        ],
        'nora' => [
            'html' => str_replace(
                ['Harbour<br>Notes', 'hello@example.com'],
                ['Nora<br>Okafor', 'hello@noraokafor.example'],
                $editorial['html']
            ),
            'css' => $editorial['css'],
            'js' => $editorial['js'],
        ],
    ];

    foreach ($sites as $index => $site) {
        $userId = $userIds[$site['user']];
        $createdTs = $now - (86400 * $site['created']);
        $updatedTs = $now - (86400 * $site['updated']);
        $publishedTs = $site['published'] ? $now - (86400 * max(1, $site['created'] - 2)) : null;
        $content = $site['content'];
        if ($site['mode'] === 'template' && $content === []) {
            $content = starter_content($site['template']);
        }

        $html = $css = $js = '';
        $contentJson = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($site['mode'] === 'code') {
            $payload = $codePayload[$site['user']];
            $html = $payload['html'];
            $css = $payload['css'];
            $js = $payload['js'];
            $contentJson = '{}';
        }

        $insertSite->execute([
            $userId,
            $site['slug'],
            $site['title'],
            $site['description'],
            $site['mode'],
            $site['template'],
            $contentJson,
            $html,
            $css,
            $js,
            $site['published'] ? 1 : 0,
            $site['featured'] ? 1 : 0,
            $publishedTs ? gmdate('Y-m-d H:i:s', $publishedTs) : null,
            gmdate('Y-m-d H:i:s', $createdTs),
            gmdate('Y-m-d H:i:s', $updatedTs),
        ]);
        $siteId = (int) $pdo->lastInsertId();

        if ($site['published']) {
            $total = 0;
            for ($d = 13; $d >= 0; $d--) {
                $count = (($index * 7 + $d * 5 + $siteId * 3) % 17) + 1;
                if ($site['mode'] === 'code') {
                    $count += 4;
                }
                $insertDay->execute([$siteId, gmdate('Y-m-d', $now - 86400 * $d), $count]);
                $total += $count;
            }
            $pdo->prepare('UPDATE sites SET views = ? WHERE id = ?')->execute([$total, $siteId]);
        }
    }

}
