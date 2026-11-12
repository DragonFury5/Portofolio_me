<?php
declare(strict_types=1);

/** Generates the abstract demo artwork used by seeded portfolios. */

$dir = dirname(__DIR__) . '/assets/img/seed';
if (!is_dir($dir)) {
    mkdir($dir, 0775, true);
}

$grain = <<<'SVG'
<filter id="grain"><feTurbulence type="fractalNoise" baseFrequency="0.86" numOctaves="2" stitchTiles="stitch"/><feColorMatrix type="saturate" values="0"/></filter>
<rect width="1200" height="900" filter="url(#grain)" opacity="0.055" style="mix-blend-mode:multiply"/>
SVG;

function art(string $name, string $body, string $grain): void
{
    global $dir;
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 900" width="1200" height="900">'
        . '<defs><filter id="grain"><feTurbulence type="fractalNoise" baseFrequency="0.86" numOctaves="2" stitchTiles="stitch"/><feColorMatrix type="saturate" values="0"/></filter></defs>'
        . $body
        . '<rect width="1200" height="900" filter="url(#grain)" opacity="0.05"/>'
        . '</svg>';
    file_put_contents($dir . '/' . $name . '.svg', $svg);
    echo "wrote {$name}.svg\n";
}

$label = 'font-family="Segoe UI, Arial, sans-serif" font-size="15" letter-spacing="4"';

$files = [];

$files['harbor-mark'] = <<<'SVG'
<rect width="1200" height="900" fill="#f7f4ec"/>
<g clip-path="url(#clip)">
  <circle cx="610" cy="425" r="255" fill="#1b365d"/>
  <path d="M300 520c150-80 300 55 470-20s220-40 300 10v390H300z" fill="#60c1e5"/>
  <circle cx="610" cy="330" r="86" fill="#f7f4ec"/>
  <circle cx="610" cy="330" r="22" fill="#1b365d"/>
</g>
<defs><clipPath id="clip"><circle cx="610" cy="425" r="255"/></clipPath></defs>
<circle cx="610" cy="425" r="255" fill="none" stroke="#1b365d" stroke-width="1.5"/>
<circle cx="862" cy="238" r="34" fill="#a0e0ef"/>
<path d="M935 180v420M985 180v420M1035 180v420" stroke="#1b365d" stroke-width="1" opacity=".35"/>
<text x="80" y="105" %%LABEL%% fill="#005a9c">HARBOR / IDENTITY</text>
<text x="80" y="140" font-family="Georgia, serif" font-size="44" fill="#1b365d">Ferry co-op, 2025</text>
<path d="M80 760h1040" stroke="#1b365d" stroke-width="1" opacity=".35"/>
<text x="80" y="808" %%LABEL%% fill="#1b365d">WAYFINDING · TICKETS · MARK</text>
<text x="1040" y="808" %%LABEL%% fill="#1b365d" text-anchor="end">01 / 04</text>
SVG;

$files['silt-specimen'] = <<<'SVG'
<rect width="1200" height="900" fill="#efe9dc"/>
<g stroke="#1b365d" stroke-width="1" opacity=".18">
  <path d="M300 0v900M600 0v900M900 0v900M0 300h1200M0 600h1200"/>
</g>
<rect x="80" y="560" width="1040" height="14" fill="#60c1e5"/>
<text x="150" y="700" font-family="Georgia, 'Times New Roman', serif" font-size="540" letter-spacing="-40" fill="#1b365d">Aa</text>
<text x="80" y="110" %%LABEL%% fill="#005a9c">SILT / TYPE SPECIMEN</text>
<text x="80" y="155" font-family="Georgia, serif" font-size="40" fill="#1b365d">A workhorse serif</text>
<path d="M80 235h180" stroke="#1b365d" stroke-width="1"/>
<text x="80" y="280" %%LABEL%% fill="#1b365d">4 WEIGHTS</text>
<text x="80" y="310" %%LABEL%% fill="#1b365d">1 STUBBORN ITALIC</text>
<text x="1120" y="820" font-family="Georgia, serif" font-size="26" font-style="italic" fill="#1b365d" text-anchor="end">drawn slowly, 2024</text>
SVG;

$files['civic-type'] = <<<'SVG'
<rect width="1200" height="900" fill="#f7f4ec"/>
<rect x="90" y="95" width="500" height="710" fill="#fffdf8" stroke="#d8d2c2"/>
<rect x="610" y="95" width="500" height="710" fill="#fffdf8" stroke="#d8d2c2"/>
<rect x="90" y="95" width="500" height="170" fill="#1b365d"/>
<text x="122" y="160" font-family="Georgia, serif" font-size="38" fill="#f7f4ec">Annual report</text>
<text x="122" y="200" font-family="Segoe UI, Arial, sans-serif" font-size="13" letter-spacing="3" fill="#a0e0ef">PUBLIC LIBRARY / 96 PP</text>
<g fill="#1b365d">
  <rect x="122" y="320" width="430" height="10"/>
  <rect x="122" y="350" width="360" height="10"/>
  <rect x="122" y="410" width="430" height="5" opacity=".55"/>
  <rect x="122" y="432" width="410" height="5" opacity=".55"/>
  <rect x="122" y="454" width="430" height="5" opacity=".55"/>
  <rect x="122" y="476" width="300" height="5" opacity=".55"/>
</g>
<rect x="122" y="540" width="430" height="180" fill="#eaf6f8"/>
<text x="336" y="650" font-family="Georgia, serif" font-size="88" fill="#005a9c" text-anchor="middle">96</text>
<circle cx="860" cy="360" r="165" fill="#60c1e5"/>
<circle cx="860" cy="360" r="165" fill="none" stroke="#1b365d" stroke-width="1"/>
<circle cx="860" cy="360" r="60" fill="#f7f4ec"/>
<g fill="#1b365d" opacity=".8">
  <rect x="660" y="580" width="400" height="5"/>
  <rect x="660" y="602" width="370" height="5"/>
  <rect x="660" y="624" width="400" height="5"/>
  <rect x="660" y="646" width="260" height="5"/>
  <rect x="660" y="690" width="120" height="5" opacity=".5"/>
  <rect x="660" y="712" width="90" height="5" opacity=".5"/>
</g>
<path d="M600 95v710" stroke="#d8d2c2" stroke-width="2"/>
<text x="90" y="855" %%LABEL%% fill="#1b365d">CIVIC TYPE / EDITORIAL</text>
<text x="1110" y="855" %%LABEL%% fill="#1b365d" text-anchor="end">03</text>
SVG;

$files['northbound'] = <<<'SVG'
<rect width="1200" height="900" fill="#1b365d"/>
<polygon points="0,900 1200,180 1200,900" fill="#005a9c"/>
<polygon points="0,900 1200,470 1200,900" fill="#60c1e5" opacity=".85"/>
<text x="780" y="780" font-family="Georgia, 'Times New Roman', serif" font-size="760" fill="#f7f4ec">N</text>
<text x="70" y="112" %%LABEL%% fill="#a0e0ef">NORTHBOUND / POSTERS</text>
<text x="70" y="170" font-family="Georgia, serif" font-size="54" fill="#f7f4ec">Six crossings</text>
<path d="M70 220h260" stroke="#f7f4ec" stroke-width="1"/>
<text x="70" y="262" %%LABEL%% fill="#f7f4ec">TWO COLOURS, ONE PAPER</text>
<text x="70" y="820" font-family="Georgia, serif" font-size="24" font-style="italic" fill="#f7f4ec">after midnight</text>
<text x="1130" y="820" %%LABEL%% fill="#f7f4ec" text-anchor="end">04 / 06</text>
SVG;

$files['night-ferry'] = <<<'SVG'
<rect width="1200" height="900" fill="#10243d"/>
<circle cx="880" cy="250" r="92" fill="#a0e0ef"/>
<circle cx="914" cy="228" r="92" fill="#10243d"/>
<g fill="#1b365d">
  <rect y="470" width="1200" height="430"/>
</g>
<g fill="#005a9c">
  <rect y="560" width="1200" height="18"/>
  <rect y="620" width="1200" height="8" opacity=".7"/>
  <rect y="690" width="1200" height="5" opacity=".5"/>
  <rect y="760" width="1200" height="3" opacity=".4"/>
</g>
<g fill="#f7f4ec">
  <path d="M310 560h490l-40 80H350z"/>
  <rect x="400" y="490" width="300" height="70"/>
  <rect x="440" y="455" width="180" height="36"/>
</g>
<g fill="#60c1e5">
  <rect x="430" y="510" width="24" height="20"/>
  <rect x="480" y="510" width="24" height="20"/>
  <rect x="530" y="510" width="24" height="20"/>
  <rect x="580" y="510" width="24" height="20"/>
  <rect x="630" y="510" width="24" height="20"/>
  <circle cx="690" cy="470" r="6"/>
</g>
<path d="M800 450V380" stroke="#f7f4ec" stroke-width="2"/>
<circle cx="800" cy="376" r="5" fill="#a0e0ef"/>
<g stroke="#60c1e5" stroke-width="2" opacity=".45">
  <path d="M360 680h380M390 700h300M420 720h220"/>
</g>
<text x="70" y="110" %%LABEL%% fill="#a0e0ef">NIGHT FERRY / 2026</text>
<text x="70" y="170" font-family="Georgia, serif" font-size="50" fill="#f7f4ec">Twelve crossings</text>
<text x="1130" y="820" %%LABEL%% fill="#a0e0ef" text-anchor="end">PERSONAL SERIES</text>
SVG;

$files['low-tide'] = <<<'SVG'
<rect width="1200" height="900" fill="#e3f0f3"/>
<rect y="470" width="1200" height="140" fill="#f7f4ec"/>
<rect y="610" width="1200" height="290" fill="#a0e0ef"/>
<rect y="470" width="1200" height="8" fill="#60c1e5"/>
<path d="M0 610c140-40 240 30 380 0s250-45 400 0 280 35 420-10v300H0z" fill="#005a9c" opacity=".55"/>
<path d="M0 700c180-30 260 25 420 0s270-35 420 10 220 15 360-5v195H0z" fill="#1b365d"/>
<ellipse cx="330" cy="645" rx="160" ry="18" fill="#f7f4ec" opacity=".8"/>
<ellipse cx="850" cy="670" rx="120" ry="12" fill="#f7f4ec" opacity=".55"/>
<polygon points="120,610 190,470 280,610" fill="#16283f"/>
<polygon points="220,610 300,500 390,610" fill="#1b365d"/>
<g stroke="#1b365d" stroke-width="1.5" opacity=".5">
  <path d="M0 755h1200M0 790h1200M0 825h1200"/>
</g>
<text x="70" y="110" %%LABEL%% fill="#005a9c">LOW TIDE, 6PM / 2025</text>
<text x="70" y="170" font-family="Georgia, serif" font-size="50" fill="#1b365d">One week in November</text>
<text x="1130" y="110" %%LABEL%% fill="#005a9c" text-anchor="end">02 / 04</text>
SVG;

$files['folded-coast'] = <<<'SVG'
<rect width="1200" height="900" fill="#f7f4ec"/>
<polygon points="0,900 0,380 340,190 620,420 900,260 1200,470 1200,900" fill="#a0e0ef"/>
<polygon points="0,900 0,560 300,430 560,620 860,470 1200,650 1200,900" fill="#60c1e5"/>
<polygon points="0,900 0,720 260,640 520,760 800,660 1200,790 1200,900" fill="#005a9c"/>
<polygon points="620,420 900,260 1200,470 1200,900 860,470 560,620" fill="#1b365d" opacity=".92"/>
<g stroke="#f7f4ec" stroke-width="1.5" opacity=".5">
  <path d="M0 560 300 430 560 620 860 470 1200 650"/>
  <path d="M0 720 260 640 520 760 800 660 1200 790"/>
</g>
<text x="70" y="110" %%LABEL%% fill="#005a9c">FOLDED COAST / 2025</text>
<text x="70" y="170" font-family="Georgia, serif" font-size="50" fill="#1b365d">Erosion, in print</text>
<text x="1130" y="830" %%LABEL%% fill="#f7f4ec" text-anchor="end">ART DIRECTION</text>
SVG;

$files['long-wave'] = <<<'SVG'
<rect width="1200" height="900" fill="#1b365d"/>
<g fill="none" stroke-linecap="round">
  <path d="M-80 760C220 660 360 420 640 430s420 240 660 140" stroke="#60c1e5" stroke-width="2"/>
  <path d="M-80 720C220 620 360 380 640 390s420 240 660 140" stroke="#a0e0ef" stroke-width="1.5" opacity=".8"/>
  <path d="M-80 680C220 580 360 340 640 350s420 240 660 140" stroke="#f7f4ec" stroke-width="1.2" opacity=".6"/>
  <path d="M-80 640C220 540 360 300 640 310s420 240 660 140" stroke="#60c1e5" stroke-width="1" opacity=".55"/>
  <path d="M-80 600C220 500 360 260 640 270s420 240 660 140" stroke="#a0e0ef" stroke-width="1" opacity=".4"/>
  <path d="M-80 560C220 460 360 220 640 230s420 240 660 140" stroke="#f7f4ec" stroke-width="1" opacity=".3"/>
</g>
<circle cx="640" cy="390" r="7" fill="#60c1e5"/>
<text x="70" y="110" %%LABEL%% fill="#a0e0ef">THE LONG WAVE / 2024</text>
<text x="1120" y="820" %%LABEL%% fill="#a0e0ef" text-anchor="end">FORTY MINUTES, TWENTY FRAMES</text>
SVG;

$files['quay-study'] = <<<'SVG'
<rect width="1200" height="900" fill="#f2ecdf"/>
<g stroke="#1b365d" stroke-width="1" opacity=".22">
  <path d="M0 300h1200M0 600h1200M300 0v900M600 0v900M900 0v900"/>
</g>
<g>
  <polygon points="300,610 520,540 740,610 520,690" fill="#a0e0ef"/>
  <polygon points="300,610 520,690 520,830 300,745" fill="#1b365d"/>
  <polygon points="520,690 740,610 740,750 520,830" fill="#005a9c"/>
</g>
<g>
  <polygon points="640,410 800,355 960,410 800,465" fill="#60c1e5"/>
  <polygon points="640,410 800,465 800,570 640,510" fill="#1b365d"/>
  <polygon points="800,465 960,410 960,515 800,570" fill="#005a9c"/>
</g>
<g>
  <polygon points="220,360 300,332 380,360 300,388" fill="#f7f4ec" stroke="#1b365d"/>
  <polygon points="220,360 300,388 300,450 220,422" fill="#d8d2c2"/>
  <polygon points="300,388 380,360 380,422 300,450" fill="#eae4d7"/>
</g>
<g stroke="#005a9c" stroke-width="1">
  <path d="M170 610h120M170 590v40M740 610h120M860 590v40"/>
</g>
<text x="170" y="575" %%LABEL%% fill="#005a9c">1:20</text>
<text x="70" y="110" %%LABEL%% fill="#005a9c">QUAY STUDY / 2026</text>
<text x="70" y="170" font-family="Georgia, serif" font-size="50" fill="#1b365d">A harbour wall, in clay</text>
<text x="1130" y="820" %%LABEL%% fill="#005a9c" text-anchor="end">EDITORIAL</text>
SVG;

$files['paper-no3'] = <<<'SVG'
<rect width="1200" height="900" fill="#fffdf8"/>
<g stroke="#1b365d" stroke-width=".8" opacity=".12">
  <path d="M0 100h1200M0 200h1200M0 300h1200M0 400h1200M0 500h1200M0 600h1200M0 700h1200M0 800h1200"/>
  <path d="M100 0v900M200 0v900M300 0v900M400 0v900M500 0v900M600 0v900M700 0v900M800 0v900M900 0v900M1000 0v900M1100 0v900"/>
</g>
<circle cx="760" cy="330" r="230" fill="#005a9c"/>
<rect x="250" y="470" width="520" height="300" fill="#a0e0ef" transform="rotate(-7 510 620)"/>
<circle cx="760" cy="330" r="88" fill="#60c1e5"/>
<text x="70" y="110" %%LABEL%% fill="#005a9c">PAPER N°3 / 2025</text>
<text x="70" y="180" font-family="Georgia, serif" font-size="54" fill="#1b365d">Wrapping, uncoated</text>
<text x="70" y="830" %%LABEL%% fill="#1b365d">INK ON PAPER · 3 THINGS ONLY</text>
<text x="1120" y="830" %%LABEL%% fill="#1b365d" text-anchor="end">PACKAGING</text>
SVG;

$files['tidal-index'] = <<<'SVG'
<rect width="1200" height="900" fill="#f7f4ec"/>
<rect x="55" y="55" width="1090" height="790" fill="none" stroke="#1b365d" stroke-width="1.5"/>
<text x="100" y="145" font-family="Georgia, serif" font-size="58" fill="#1b365d">Tidal index</text>
<text x="100" y="185" %%LABEL%% fill="#005a9c">TWELVE PLATES · 2026</text>
<g stroke="#1b365d" stroke-width="1" opacity=".25">
  <path d="M100 260h1000M100 360h1000M100 460h1000M100 560h1000M100 660h1000"/>
</g>
<path d="M100 600C220 520 300 620 420 540s180 90 300 10 200 60 380-20" fill="none" stroke="#005a9c" stroke-width="5"/>
<path d="M100 600C220 520 300 620 420 540s180 90 300 10 200 60 380-20v120H100z" fill="#60c1e5" opacity=".55"/>
<g fill="#1b365d">
  <circle cx="100" cy="600" r="7"/><circle cx="420" cy="540" r="7"/><circle cx="720" cy="550" r="7"/><circle cx="1100" cy="530" r="7"/>
</g>
<g %%LABEL%% fill="#1b365d">
  <text x="90" y="720">06:00</text><text x="390" y="720">12:00</text><text x="690" y="720">18:00</text><text x="1050" y="720">00:00</text>
</g>
<text x="100" y="800" font-family="Georgia, serif" font-size="24" font-style="italic" fill="#1b365d">a set of twelve plates for a small restaurant</text>
SVG;

$files['signal-04'] = <<<'SVG'
<rect width="1200" height="900" fill="#0e2036"/>
<g stroke="#60c1e5" stroke-width="8" stroke-linecap="round">
  <path d="M120 520v-80M170 520v-210M220 520v-120M270 520v-330M320 520v-160M370 520v-260M420 520v-90M470 520v-200M520 520v-300M570 520v-70M620 520v-150M670 520v-240M720 520v-100M770 520v-320M820 520v-180M870 520v-60M920 520v-230M970 520v-140M1020 520v-280M1070 520v-100"/>
</g>
<path d="M100 520h1000" stroke="#a0e0ef" stroke-width="1.5"/>
<text x="70" y="110" %%LABEL%% fill="#a0e0ef">SIGNAL 04 / 2026</text>
<text x="70" y="170" font-family="Georgia, serif" font-size="50" fill="#f7f4ec">A study in noise</text>
<text x="1130" y="820" %%LABEL%% fill="#a0e0ef" text-anchor="end">GENERATIVE SERIES</text>
SVG;

foreach ($files as $name => $body) {
    $body = str_replace('%%LABEL%%', $label, $body);
    art($name, $body, $grain);
}

/* Avatars */
$avatars = [
    'demo' => ['DM', '#1b365d', '#a0e0ef'],
    'mara' => ['ME', '#005a9c', '#f7f4ec'],
    'kaito' => ['KM', '#1b365d', '#60c1e5'],
    'priya' => ['PN', '#60c1e5', '#1b365d'],
    'tomas' => ['TF', '#a0e0ef', '#1b365d'],
    'jules' => ['JP', '#16283f', '#a0e0ef'],
    'nora' => ['NO', '#005a9c', '#a0e0ef'],
    'amara' => ['AB', '#1b365d', '#f7f4ec'],
    'ines' => ['ID', '#a0e0ef', '#005a9c'],
];

foreach ($avatars as $name => [$initials, $bg, $fg]) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200">'
        . '<circle cx="100" cy="100" r="100" fill="' . $bg . '"/>'
        . '<circle cx="100" cy="100" r="86" fill="none" stroke="' . $fg . '" stroke-width="1" opacity=".35"/>'
        . '<path d="M150 52a70 70 0 0 1 14 68" fill="none" stroke="' . $fg . '" stroke-width="2" opacity=".5" stroke-linecap="round"/>'
        . '<text x="100" y="122" text-anchor="middle" font-family="Georgia, serif" font-size="66" letter-spacing="-2" fill="' . $fg . '">' . $initials . '</text>'
        . '</svg>';
    file_put_contents($dir . '/avatar-' . $name . '.svg', $svg);
    echo "wrote avatar-{$name}.svg\n";
}

/* Favicon */
$favicon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">'
    . '<rect width="64" height="64" rx="13" fill="#1b365d"/>'
    . '<path d="M17 49V15h20a11 11 0 0 1 0 22H29" stroke="#f7f4ec" stroke-width="5.5" fill="none" stroke-linecap="square"/>'
    . '<path d="M14 55c9-3 16 3 24 .5 5-1.8 9-1 13 .5" stroke="#60c1e5" stroke-width="4.5" fill="none" stroke-linecap="round"/>'
    . '</svg>';
file_put_contents(dirname(__DIR__) . '/assets/img/favicon.svg', $favicon);
echo "wrote favicon.svg\n";
