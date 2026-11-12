<?php
declare(strict_types=1);

/** Starting points for custom-code sites. Every line is meant to be edited. */

function code_starters(): array
{
    static $starters = null;
    if ($starters !== null) {
        return $starters;
    }

    $blankHtml = <<<'HTML'
<main class="page">
  <p class="label">Your name · Your city</p>

  <h1>Say what you do, in <em>one clear line</em>.</h1>
  <p class="lede">
    Then say a little more. Who do you work with, what do you make,
    and what should someone do next? Keep it short enough to read twice.
  </p>

  <section class="work" aria-labelledby="work-title">
    <h2 id="work-title" class="section-title">Selected work</h2>

    <a class="project" href="#">
      <span class="project__no">01</span>
      <span class="project__title">Project name</span>
      <span class="project__meta">2026 · Identity</span>
    </a>
    <a class="project" href="#">
      <span class="project__no">02</span>
      <span class="project__title">Another project</span>
      <span class="project__meta">2025 · Print</span>
    </a>
    <a class="project" href="#">
      <span class="project__no">03</span>
      <span class="project__title">One more thing</span>
      <span class="project__meta">2024 · Web</span>
    </a>
  </section>

  <footer class="footer">
    <p>Open for work in 2026.</p>
    <a href="mailto:hello@example.com">hello@example.com</a>
  </footer>
</main>
HTML;

    $blankCss = <<<'CSS'
/* Everything here is yours. Change the variables first. */
:root {
  --paper: #f7f4ec;
  --ink: #1b365d;
  --line: #d8d2c2;
  --accent: #005a9c;
  --body: "Public Sans", "Segoe UI", sans-serif;
  --display: "Fraunces", Georgia, serif;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  background: var(--paper);
  color: var(--ink);
  font-family: var(--body);
  -webkit-font-smoothing: antialiased;
}

.page {
  width: min(100% - 48px, 980px);
  margin: 0 auto;
  padding: 72px 0 96px;
}

.label {
  margin: 0;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--accent);
}

h1 {
  max-width: 15ch;
  margin: 28px 0 24px;
  font-family: var(--display);
  font-size: clamp(42px, 7vw, 82px);
  font-weight: 600;
  line-height: 1.02;
  letter-spacing: -0.03em;
}

h1 em {
  font-style: italic;
  text-decoration: underline;
  text-decoration-color: #60c1e5;
  text-decoration-thickness: 4px;
  text-underline-offset: 8px;
}

.lede {
  max-width: 52ch;
  margin: 0;
  font-size: 18px;
  line-height: 1.6;
  color: #43536a;
}

.work { margin-top: 88px; }

.section-title {
  margin: 0 0 8px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.project {
  display: grid;
  grid-template-columns: 40px 1fr auto;
  gap: 16px;
  align-items: baseline;
  padding: 22px 0;
  border-bottom: 1px solid var(--line);
  color: inherit;
  text-decoration: none;
  transition: padding-left 180ms ease, color 180ms ease;
}

.project:first-of-type { border-top: 1px solid var(--line); margin-top: 16px; }
.project:hover { padding-left: 8px; color: var(--accent); }

.project__no,
.project__meta {
  font-size: 12px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #6b7686;
}

.project__title {
  font-family: var(--display);
  font-size: clamp(22px, 3vw, 30px);
  letter-spacing: -0.02em;
}

.footer {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 12px;
  margin-top: 88px;
  padding-top: 24px;
  border-top: 1px solid var(--line);
  font-size: 14px;
}

.footer p { margin: 0; color: #6b7686; }
.footer a { color: var(--accent); text-underline-offset: 3px; }

@media (max-width: 620px) {
  .project { grid-template-columns: 32px 1fr; }
  .project__meta { grid-column: 2; }
  .page { padding-top: 48px; }
}
CSS;

    $editorialHtml = <<<'HTML'
<header class="masthead">
  <a class="wordmark" href="#top">Harbour<br>Notes</a>
  <nav class="nav" aria-label="Sections">
    <a href="#work">Work</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
  </nav>
  <p class="issue">Vol. 03<br>2026</p>
</header>

<main id="top">
  <section class="intro">
    <p class="kicker">A one-person studio for printed matter</p>
    <h1>Pages that<br>ask you to <em>slow down.</em></h1>
    <p class="standfirst">
      I design books, identities and the occasional very stubborn poster.
      This is a starter page — replace the words, keep the bones.
    </p>
    <div class="scroll-cue"><span></span>Scroll</div>
  </section>

  <section class="work" id="work" aria-label="Selected work">
    <article class="piece" data-reveal>
      <div class="piece__index">01</div>
      <div class="piece__body">
        <h2>The Long Ferry</h2>
        <p class="piece__meta">Identity · 2026</p>
        <p>A quiet visual system for a route that has run for ninety years. Wayfinding, tickets and a mark drawn from the tide line.</p>
      </div>
      <div class="piece__plate plate--a" role="img" aria-label="Abstract poster study in deep blue and pale cyan"></div>
    </article>

    <article class="piece piece--flip" data-reveal>
      <div class="piece__index">02</div>
      <div class="piece__body">
        <h2>Silt Specimen</h2>
        <p class="piece__meta">Type design · 2025</p>
        <p>A workhorse serif with a soft shoulder and a suspicious amount of patience. Six styles, one very slow drawing process.</p>
      </div>
      <div class="piece__plate plate--b" role="img" aria-label="Type specimen layout with large letterforms"></div>
    </article>

    <article class="piece" data-reveal>
      <div class="piece__index">03</div>
      <div class="piece__body">
        <h2>Reading Rooms</h2>
        <p class="piece__meta">Editorial · 2025</p>
        <p>A 96-page annual for a public library. Designed to be read on a bus, not admired from across a room.</p>
      </div>
      <div class="piece__plate plate--c" role="img" aria-label="Editorial spread with ruled columns and a cyan circle"></div>
    </article>
  </section>

  <section class="about" id="about">
    <h2>About the practice</h2>
    <div class="about__copy">
      <p>I work alone, mostly with small organisations who care about the thing they are making. Projects usually start with a long conversation and end with a file you can actually open in ten years.</p>
      <p>Available for books, identities and editorial commissions from spring 2026.</p>
    </div>
  </section>

  <section class="contact" id="contact">
    <p class="kicker">Start a conversation</p>
    <a class="contact__link" href="mailto:hello@example.com">hello@example.com</a>
    <p class="contact__note">Replies within two working days. Usually with questions.</p>
  </section>
</main>

<footer class="colophon">
  <span>Set in Fraunces &amp; Public Sans</span>
  <span>Built by hand, hosted on Portside</span>
</footer>
HTML;

    $editorialCss = <<<'CSS'
:root {
  --paper: #f7f4ec;
  --ink: #16283f;
  --blue: #005a9c;
  --cyan: #60c1e5;
  --pale: #a0e0ef;
  --rule: #cfc8b8;
  --display: "Fraunces", Georgia, serif;
  --body: "Public Sans", "Segoe UI", sans-serif;
}

* { box-sizing: border-box; }
html { scroll-behavior: smooth; }

body {
  margin: 0;
  background: var(--paper);
  color: var(--ink);
  font-family: var(--body);
  -webkit-font-smoothing: antialiased;
}

a { color: inherit; }

.masthead {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: start;
  gap: 24px;
  padding: 28px clamp(20px, 4vw, 56px);
  border-bottom: 1px solid var(--rule);
}

.wordmark {
  font-family: var(--display);
  font-size: 20px;
  font-weight: 600;
  line-height: 0.95;
  letter-spacing: -0.02em;
  text-decoration: none;
}

.nav { display: flex; gap: 28px; font-size: 14px; }
.nav a { text-decoration: none; padding-bottom: 2px; border-bottom: 1px solid transparent; }
.nav a:hover { border-color: var(--cyan); }

.issue {
  margin: 0;
  justify-self: end;
  font-size: 11px;
  line-height: 1.5;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #6c7786;
  text-align: right;
}

main { padding: 0 clamp(20px, 4vw, 56px); }

.intro {
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 78vh;
  padding: 96px 0 64px;
  max-width: 1180px;
}

.kicker {
  margin: 0 0 22px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.13em;
  text-transform: uppercase;
  color: var(--blue);
}

h1 {
  margin: 0;
  font-family: var(--display);
  font-size: clamp(56px, 10vw, 148px);
  font-weight: 500;
  line-height: 0.92;
  letter-spacing: -0.04em;
}

h1 em {
  font-style: italic;
  color: var(--blue);
}

.standfirst {
  max-width: 46ch;
  margin: 40px 0 0 auto;
  font-size: clamp(17px, 2vw, 21px);
  line-height: 1.55;
  color: #40526a;
}

.scroll-cue {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 64px;
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #6c7786;
}

.scroll-cue span {
  display: block;
  width: 48px;
  height: 1px;
  background: var(--ink);
}

.work {
  display: grid;
  gap: 0 48px;
  padding: 40px 0 96px;
  border-top: 1px solid var(--rule);
}

.piece {
  display: grid;
  grid-template-columns: 64px 1fr 1.1fr;
  gap: 32px;
  align-items: start;
  padding: 48px 0;
  border-bottom: 1px solid var(--rule);
}

.piece--flip .piece__plate { order: -1; }

.piece__index {
  font-family: var(--display);
  font-size: 14px;
  font-style: italic;
  color: var(--blue);
}

.piece h2 {
  margin: 0 0 6px;
  font-family: var(--display);
  font-size: clamp(28px, 3.4vw, 44px);
  font-weight: 500;
  letter-spacing: -0.025em;
}

.piece__meta {
  margin: 0 0 18px;
  font-size: 11px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: #6c7786;
}

.piece p:last-child {
  max-width: 42ch;
  margin: 0;
  font-size: 15px;
  line-height: 1.7;
  color: #40526a;
}

.piece__plate {
  min-height: 300px;
  border: 1px solid var(--rule);
  background: #ede7d8;
  position: relative;
  overflow: hidden;
}

.plate--a { background: #1b365d; }
.plate--a::before {
  content: "";
  position: absolute;
  width: 70%;
  aspect-ratio: 1;
  border: 18px solid var(--cyan);
  border-radius: 50%;
  left: 15%;
  top: 12%;
}
.plate--a::after {
  content: "90 / 26";
  position: absolute;
  left: 20px;
  bottom: 16px;
  color: var(--pale);
  font-size: 11px;
  letter-spacing: 0.2em;
}

.plate--b { background: #dfeef2; }
.plate--b::before {
  content: "Aa";
  position: absolute;
  inset: 0;
  display: grid;
  place-items: center;
  font-family: var(--display);
  font-size: 140px;
  color: var(--blue);
}
.plate--b::after {
  content: "";
  position: absolute;
  left: 12%;
  right: 12%;
  top: 50%;
  border-top: 1px solid rgba(27, 54, 93, 0.3);
}

.plate--c { background: #f1ecdf; }
.plate--c::before {
  content: "";
  position: absolute;
  left: 12%;
  top: 12%;
  bottom: 12%;
  width: 42%;
  background: repeating-linear-gradient(180deg, #9aa6b3 0 1px, transparent 1px 12px);
}
.plate--c::after {
  content: "";
  position: absolute;
  right: 8%;
  top: 18%;
  width: 45%;
  aspect-ratio: 1;
  background: var(--cyan);
  border-radius: 50%;
}

.about {
  display: grid;
  grid-template-columns: 1fr 1.6fr;
  gap: 48px;
  padding: 96px 0;
  border-top: 1px solid var(--rule);
}

.about h2 {
  margin: 0;
  font-family: var(--display);
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

.about__copy p {
  max-width: 52ch;
  margin: 0 0 20px;
  font-family: var(--display);
  font-size: clamp(22px, 2.6vw, 32px);
  line-height: 1.35;
  letter-spacing: -0.015em;
}

.about__copy p:last-child {
  font-family: var(--body);
  font-size: 15px;
  line-height: 1.7;
  color: #526075;
}

.contact {
  padding: 96px 0 72px;
  border-top: 1px solid var(--rule);
}

.contact__link {
  display: inline-block;
  margin: 8px 0 16px;
  font-family: var(--display);
  font-size: clamp(32px, 6vw, 76px);
  letter-spacing: -0.035em;
  text-decoration: none;
  border-bottom: 3px solid var(--cyan);
}

.contact__note { color: #6c7786; font-size: 14px; }

.colophon {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 12px;
  padding: 24px clamp(20px, 4vw, 56px);
  border-top: 1px solid var(--rule);
  font-size: 11px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #6c7786;
}

.reveal { opacity: 0; transform: translateY(16px); transition: opacity 480ms ease-out, transform 480ms ease-out; }
.reveal.is-in { opacity: 1; transform: none; }

@media (max-width: 820px) {
  .masthead { grid-template-columns: 1fr auto; }
  .issue { display: none; }
  .intro { min-height: auto; padding: 72px 0 48px; }
  .standfirst { margin-left: 0; }
  .piece { grid-template-columns: 32px 1fr; gap: 20px; }
  .piece__plate { grid-column: 2; min-height: 220px; order: 0; }
  .piece--flip .piece__plate { order: 0; }
  .about { grid-template-columns: 1fr; gap: 20px; padding: 64px 0; }
}

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  .reveal { opacity: 1; transform: none; transition: none; }
}
CSS;

    $editorialJs = <<<'JS'
// Reveal project rows when they come into view. That is the whole script.
const rows = document.querySelectorAll('[data-reveal]');
const canAnimate = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (canAnimate && 'IntersectionObserver' in window) {
  rows.forEach((row) => row.classList.add('reveal'));
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-in');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  rows.forEach((row) => observer.observe(row));
}
JS;

    $kineticHtml = <<<'HTML'
<div class="grain" aria-hidden="true"></div>

<header class="top">
  <span class="top__mark">Jules Park</span>
  <span class="top__meta">Motion &amp; type<br>Seoul / everywhere</span>
</header>

<main>
  <section class="stage">
    <p class="eyebrow">A small studio for moving letters</p>

    <div class="marquee" aria-label="Motion, type, and a little chaos">
      <div class="marquee__track">
        <span>MOTION</span><span class="outline">TYPE</span><span>MOTION</span><span class="outline">TYPE</span>
      </div>
    </div>

    <div class="orb" aria-hidden="true"></div>

    <p class="intro">
      I make title sequences, identities and interfaces that move
      like they mean it. This page is a starter — the orb follows your cursor,
      the type refuses to sit still. Edit anything.
    </p>

    <div class="scroll-note"><span class="dot"></span>Drag your pointer around</div>
  </section>

  <section class="projects" aria-labelledby="projects-title">
    <h2 id="projects-title">Selected</h2>

    <a class="row" href="#">
      <span class="row__year">2026</span>
      <span class="row__name">Night Shift <em>— title sequence</em></span>
      <span class="row__tag">Film</span>
    </a>
    <a class="row" href="#">
      <span class="row__year">2025</span>
      <span class="row__name">The Blue Hour <em>— brand in motion</em></span>
      <span class="row__tag">Identity</span>
    </a>
    <a class="row" href="#">
      <span class="row__year">2025</span>
      <span class="row__name">Soft Machine <em>— web experiment</em></span>
      <span class="row__tag">Web</span>
    </a>
  </section>

  <section class="contact">
    <p class="eyebrow">Commissions &amp; strange ideas</p>
    <a class="contact__link" href="mailto:hello@example.com">hello@example.com</a>
  </section>
</main>

<footer class="foot">
  <span>Built with HTML, CSS and a little JS. No page builder.</span>
  <span class="foot__year">© 2026</span>
</footer>
HTML;

    $kineticCss = <<<'CSS'
:root {
  --navy: #10243d;
  --ink: #16283f;
  --paper: #f2eee4;
  --cyan: #60c1e5;
  --pale: #a0e0ef;
  --blue: #005a9c;
  --display: "Fraunces", Georgia, serif;
  --body: "Public Sans", "Segoe UI", sans-serif;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  background: var(--navy);
  color: var(--paper);
  font-family: var(--body);
  overflow-x: hidden;
}

a { color: inherit; }

.grain {
  position: fixed;
  inset: 0;
  z-index: 10;
  pointer-events: none;
  opacity: 0.11;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
  mix-blend-mode: soft-light;
}

.top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 28px clamp(20px, 4vw, 56px);
  border-bottom: 1px solid rgba(242, 238, 228, 0.16);
  font-size: 12px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

.top__mark { font-weight: 600; }
.top__meta { text-align: right; color: rgba(242, 238, 228, 0.62); line-height: 1.6; }

main { padding: 0 clamp(20px, 4vw, 56px); }

.stage {
  position: relative;
  padding: clamp(64px, 12vh, 150px) 0 96px;
  min-height: 88vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.eyebrow {
  margin: 0 0 28px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--cyan);
}

.marquee {
  overflow: hidden;
  margin: 0 -6vw;
  border-top: 1px solid rgba(160, 224, 239, 0.24);
  border-bottom: 1px solid rgba(160, 224, 239, 0.24);
  padding: 10px 0;
}

.marquee__track {
  display: flex;
  gap: 0.22em;
  width: max-content;
  animation: slide 24s linear infinite;
  font-family: var(--display);
  font-size: clamp(72px, 17vw, 230px);
  line-height: 0.9;
  letter-spacing: -0.05em;
  white-space: nowrap;
}

.marquee__track span { padding-right: 0.12em; }
.marquee__track .outline {
  color: transparent;
  -webkit-text-stroke: 2px var(--cyan);
  font-style: italic;
}

@keyframes slide {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

.intro {
  max-width: 50ch;
  margin: 56px 0 0 auto;
  font-size: clamp(16px, 1.8vw, 19px);
  line-height: 1.65;
  color: rgba(242, 238, 228, 0.78);
}

.orb {
  position: absolute;
  width: 310px;
  height: 310px;
  left: 44%;
  top: 34%;
  border-radius: 50%;
  background: var(--cyan);
  filter: blur(1px);
  opacity: 0.9;
  mix-blend-mode: multiply;
  transform: translate3d(-50%, -50%, 0);
  transition: transform 400ms cubic-bezier(0.2, 0.7, 0.2, 1);
  pointer-events: none;
}

.scroll-note {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 56px;
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: rgba(242, 238, 228, 0.5);
}

.dot { width: 6px; height: 6px; border-radius: 50%; background: var(--pale); }

.projects {
  padding: 48px 0 96px;
  border-top: 1px solid rgba(242, 238, 228, 0.16);
}

.projects h2 {
  margin: 0 0 40px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--cyan);
}

.row {
  display: grid;
  grid-template-columns: 80px 1fr auto;
  gap: 24px;
  align-items: baseline;
  padding: 26px 0;
  border-top: 1px solid rgba(242, 238, 228, 0.12);
  text-decoration: none;
  transition: color 180ms ease, padding-left 180ms ease;
}

.row:hover { color: var(--pale); padding-left: 10px; }

.row__year,
.row__tag {
  font-size: 11px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(242, 238, 228, 0.5);
}

.row__name {
  font-family: var(--display);
  font-size: clamp(28px, 4.5vw, 58px);
  line-height: 1;
  letter-spacing: -0.03em;
}

.row__name em {
  display: block;
  margin-top: 8px;
  font-family: var(--body);
  font-size: 13px;
  font-style: normal;
  letter-spacing: 0.02em;
  color: rgba(242, 238, 228, 0.55);
}

.contact {
  padding: 88px 0 72px;
  border-top: 1px solid rgba(242, 238, 228, 0.16);
}

.contact__link {
  font-family: var(--display);
  font-size: clamp(30px, 6vw, 72px);
  letter-spacing: -0.035em;
  text-decoration: none;
  border-bottom: 3px solid var(--cyan);
}

.foot {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 12px;
  padding: 24px clamp(20px, 4vw, 56px);
  border-top: 1px solid rgba(242, 238, 228, 0.16);
  font-size: 11px;
  letter-spacing: 0.07em;
  color: rgba(242, 238, 228, 0.5);
}

@media (max-width: 720px) {
  .orb { width: 190px; height: 190px; left: 68%; top: 30%; opacity: 0.75; }
  .intro { margin-left: 0; }
  .row { grid-template-columns: 52px 1fr; }
  .row__tag { grid-column: 2; }
  .stage { min-height: auto; }
}

@media (prefers-reduced-motion: reduce) {
  .marquee__track { animation: none; }
  .orb { transition: none; }
}
CSS;

    $kineticJs = <<<'JS'
// A soft orb that follows the pointer. Respects reduced motion.
const orb = document.querySelector('.orb');
const canMove = window.matchMedia('(prefers-reduced-motion: no-preference)').matches;

if (orb && canMove) {
  let targetX = 0;
  let targetY = 0;
  let currentX = 0;
  let currentY = 0;

  window.addEventListener('pointermove', (event) => {
    targetX = (event.clientX / window.innerWidth - 0.5) * 140;
    targetY = (event.clientY / window.innerHeight - 0.5) * 100;
  });

  function tick() {
    currentX += (targetX - currentX) * 0.06;
    currentY += (targetY - currentY) * 0.06;
    orb.style.transform = `translate3d(calc(-50% + ${currentX}px), calc(-50% + ${currentY}px), 0)`;
    requestAnimationFrame(tick);
  }
  tick();
}
JS;

    $starters = [
        'blank' => [
            'key' => 'blank',
            'name' => 'Blank canvas',
            'tagline' => 'A clean page with three project rows. Nothing to delete.',
            'level' => 'Start here',
            'html' => $blankHtml,
            'css' => $blankCss,
            'js' => '',
        ],
        'editorial' => [
            'key' => 'editorial',
            'name' => 'Editorial',
            'tagline' => 'A serif one-pager with scroll reveals and large plates.',
            'level' => 'A little structure',
            'html' => $editorialHtml,
            'css' => $editorialCss,
            'js' => $editorialJs,
        ],
        'kinetic' => [
            'key' => 'kinetic',
            'name' => 'Kinetic type',
            'tagline' => 'A moving masthead, a pointer-following orb, a project ledger.',
            'level' => 'Full motion',
            'html' => $kineticHtml,
            'css' => $kineticCss,
            'js' => $kineticJs,
        ],
    ];

    return $starters;
}

function code_starter(string $key): array
{
    $starters = code_starters();
    if (isset($starters[$key])) {
        return $starters[$key];
    }
    return $starters['blank'];
}
