<?php
page_start();
$htmlKb = round(CODE_LIMITS['html'] / 1000);
$cssKb = round(CODE_LIMITS['css'] / 1000);
$jsKb = round(CODE_LIMITS['js'] / 1000);
?>
<div class="wrap">
    <header class="page-hero">
        <p class="eyebrow">Documentation</p>
        <h1>The code guide.</h1>
        <p class="page-hero__lede">
            Everything you can write, everything the sandbox stops, and the few limits
            worth knowing before you start. Short version: it is a static page, and it is yours.
        </p>
        <div class="page-hero__meta">
            <span><?= icon('code', 14) ?> HTML, CSS, JS</span>
            <span><?= icon('shield', 14) ?> Sandboxed</span>
            <span><?= icon('download', 14) ?> One-file export</span>
        </div>
    </header>

    <div class="docs-layout">
        <aside class="docs-toc">
            <p class="docs-toc__label">On this page</p>
            <a href="#panes">The three panes</a>
            <a href="#sandbox">The sandbox</a>
            <a href="#libraries">Libraries &amp; fonts</a>
            <a href="#assets">Images &amp; uploads</a>
            <a href="#limits">Limits</a>
            <a href="#publish">Publishing &amp; export</a>
            <a href="#trouble">When it breaks</a>
            <a href="#example">A working example</a>
            <p class="docs-toc__note">Still stuck? The demo account is a real page you can open, edit and break.</p>
        </aside>

        <div class="docs-body">
            <article class="prose">
                <h2 id="panes">The three panes</h2>
                <p>
                    A code page is three files living in one record: <code>index.html</code>,
                    <code>styles.css</code> and <code>script.js</code>. Switch between them with the
                    tabs above the editor. A live preview sits on the right and refreshes a moment
                    after you stop typing.
                </p>
                <p>
                    If your HTML already contains a full document — a doctype, <code>&lt;html&gt;</code>,
                    <code>&lt;head&gt;</code> — Portside leaves it alone and injects your CSS before
                    <code>&lt;/head&gt;</code> and your JS before <code>&lt;/body&gt;</code>. If you write a
                    fragment, it is wrapped in a minimal document with your page title and
                    description as metadata. Either way works; fragments are less typing.
                </p>
                <div class="notice">
                    <?= icon('check', 18) ?>
                    <div><strong>A fragment is fine.</strong> Start with <code>&lt;main&gt;…&lt;/main&gt;</code> and let the platform provide the skeleton. Use a full document when you need control over <code>&lt;head&gt;</code>, meta tags or a custom <code>&lt;base&gt;</code>.</div>
                </div>

                <h2 id="sandbox">The sandbox</h2>
                <p>
                    Custom pages are served with a restrictive <code>Content-Security-Policy</code>
                    sandbox. The page gets a unique, opaque origin: it cannot read Portside cookies,
                    call platform APIs with your session, or touch another page’s storage. Scripts,
                    forms, modals, downloads and user-initiated navigation are allowed; the page is
                    still a normal page for your visitors.
                </p>
                <p>The practical consequences:</p>
                <ul>
                    <li><strong>No <code>localStorage</code>, <code>sessionStorage</code> or cookies.</strong> They throw or come back empty. Keep state in memory for the visit.</li>
                    <li><strong>No same-origin requests.</strong> <code>fetch('/api/…')</code> will fail. Requests to CORS-enabled external endpoints still work.</li>
                    <li><strong>No service workers, camera, microphone or geolocation.</strong> Those permissions are disabled by the sandbox and the platform headers.</li>
                    <li><strong>No server-side code.</strong> There is no PHP, Python or database behind your page. Contact forms need an external form endpoint or a <code>mailto:</code> link.</li>
                    <li><strong>Navigation works.</strong> Links and <code>target="_blank"</code> behave normally after a click. A script cannot redirect the visitor without interaction.</li>
                </ul>
                <div class="notice notice--blue">
                    <?= icon('shield', 18) ?>
                    <div><strong>Why so strict?</strong> Because one platform hosts many people’s code. The sandbox is what lets us offer real JavaScript without asking visitors to trust every maker. Your page is still yours; it just cannot reach into the neighbours’ houses.</div>
                </div>

                <h2 id="libraries">Libraries &amp; fonts</h2>
                <p>
                    Use any library that works in a browser and is served over HTTPS. Drop a script
                    tag into your HTML (or import it from your JS) exactly as you would on any static
                    site. The editor does not bundle, compile or transform anything.
                </p>
                <div class="code-block">
                    <div class="code-block__bar"><span>index.html</span><span>head or end of body</span></div>
                    <pre><code>&lt;script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"&gt;&lt;/script&gt;
&lt;script src="https://cdn.jsdelivr.net/npm/lenis@1/dist/lenis.min.js"&gt;&lt;/script&gt;

&lt;link rel="preconnect" href="https://fonts.googleapis.com"&gt;
&lt;link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400..700&amp;display=swap" rel="stylesheet"&gt;</code></pre>
                </div>
                <p>
                    Google Fonts and other CDNs are allowed. If a library is blocked by its own CORS
                    policy, that is the library’s decision, not the sandbox’s. Self-host anything
                    precious: a CDN is a dependency, and dependencies move.
                </p>

                <h2 id="assets">Images &amp; uploads</h2>
                <p>
                    Use the image button in the editor to upload a file. Portside re-encodes every
                    upload through GD, strips metadata, caps the long edge at 1600 px and returns a
                    path you can paste into <code>src</code> or <code>background-image</code>. Images
                    are served as normal static files; SVG uploads are not accepted.
                </p>
                <div class="code-block">
                    <div class="code-block__bar"><span>index.html</span><span>uploaded image</span></div>
                    <pre><code>&lt;img src="/uploads/u12/9f3a1c2b7d.jpg"
     alt="Silt specimen, cover"
     width="1600" height="1200" loading="lazy"&gt;</code></pre>
                </div>
                <p>
                    Uploads are public. Do not put anything private in them, and do not link to
                    images you do not have permission to use. The same goes for fonts, typefaces and
                    client work.
                </p>

                <h2 id="limits">Limits</h2>
                <ul>
                    <li><strong>HTML:</strong> up to <?= $htmlKb ?> KB. <strong>CSS:</strong> up to <?= $cssKb ?> KB. <strong>JS:</strong> up to <?= $jsKb ?> KB.</li>
                    <li><strong>Images:</strong> 5 MB per file, resized to 1600 px. JPG, PNG and WebP only.</li>
                    <li><strong>Pages:</strong> up to 4 per account, published or draft.</li>
                    <li><strong>No build step:</strong> no Sass, TypeScript or JSX. Compile locally and paste the output, or use a browser-native approach.</li>
                </ul>
                <p>
                    These are beta numbers, chosen so the hosting bill stays small. They are measured
                    in bytes of source, not minified or gzipped size. If you hit a limit, the save
                    fails with a message and your previous version stays live.
                </p>

                <h2 id="publish">Publishing &amp; export</h2>
                <p>
                    Saving stores a working version. Publishing makes <code>/p/your-address</code>
                    visible to the world and counts it in Explore. Unpublishing takes it back to a
                    private draft; the URL stops resolving for everyone but you. Saving while
                    published updates the live page immediately.
                </p>
                <p>
                    The <strong>Export</strong> button downloads one HTML file with your markup, CSS
                    and JS inlined. Upload it to Netlify, GitHub Pages, Vercel, or drop it on any
                    static host. It will not include images hosted under <code>/uploads</code>; download
                    those separately or reference external URLs if you want the export to be portable.
                </p>

                <h2 id="trouble">When it breaks</h2>
                <h3>Nothing renders</h3>
                <p>
                    Open the browser console inside the preview iframe. A JavaScript syntax error
                    stops the rest of your script; a missing closing tag can swallow the page. The
                    editor does not validate HTML — browsers are forgiving, but not that forgiving.
                </p>
                <h3>My styles do not apply</h3>
                <p>
                    Check that you are editing the CSS pane and not a <code>&lt;style&gt;</code> block
                    that is being overwritten. Specificity and load order still work exactly as they
                    do elsewhere; there is no extra reset or framework.
                </p>
                <h3>LocalStorage is unavailable</h3>
                <p>
                    That is the sandbox. Store temporary state in a variable, or use
                    <code>sessionStorage</code>… which is also unavailable. Use the URL hash if the
                    state needs to survive a reload.
                </p>
                <h3>The image is broken</h3>
                <p>
                    Copy the URL from the upload result, not the file name on your computer. In a
                    sub-folder installation, include the app’s base path. If you paste an external
                    image, make sure it is served over HTTPS and permits hotlinking.
                </p>

                <h2 id="example">A working example</h2>
                <p>
                    This is a complete code page: a small editorial hero, one CSS rule that makes
                    the type behave, and a scroll reveal that respects reduced motion. Paste it into
                    a blank code page and change the words.
                </p>
                <div class="code-block">
                    <div class="code-block__bar"><span>index.html</span><span>fragment</span></div>
                    <pre><code>&lt;main class="page"&gt;
  &lt;p class="kicker"&gt;Illustrator · Lisbon&lt;/p&gt;
  &lt;h1&gt;Pictures for &lt;em&gt;slow mornings.&lt;/em&gt;&lt;/h1&gt;
  &lt;p class="lede"&gt;Editorial work, packaging, and drawings with room around the edges.&lt;/p&gt;
  &lt;a class="contact" href="mailto:hello@example.com"&gt;hello@example.com&lt;/a&gt;
&lt;/main&gt;

&lt;script&gt;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduce) {
    document.querySelectorAll('main &gt; *').forEach((el, i) =&gt; {
      el.animate(
        [{ opacity: 0, transform: 'translateY(12px)' }, { opacity: 1, transform: 'none' }],
        { duration: 480, delay: i * 60, easing: 'cubic-bezier(.2,.65,.25,1)', fill: 'both' }
      );
    });
  }
&lt;/script&gt;</code></pre>
                </div>
                <div class="code-block">
                    <div class="code-block__bar"><span>styles.css</span><span>complete</span></div>
                    <pre><code>:root { --paper: #f7f4ec; --ink: #16283f; --blue: #005a9c; }
* { box-sizing: border-box; }
body {
  margin: 0; background: var(--paper); color: var(--ink);
  font-family: "Public Sans", "Segoe UI", sans-serif;
}
.page { max-width: 760px; margin: 0 auto; padding: 12vh 24px; }
.kicker {
  margin: 0 0 18px; font-size: 11px; letter-spacing: .14em;
  text-transform: uppercase; color: var(--blue);
}
h1 {
  margin: 0; font-family: Georgia, serif; font-size: clamp(44px, 9vw, 96px);
  line-height: .95; letter-spacing: -.04em;
}
h1 em { color: var(--blue); }
.lede { max-width: 44ch; margin: 26px 0 40px; color: #586475; line-height: 1.7; }
.contact { color: var(--blue); text-underline-offset: 4px; }</code></pre>
                </div>
                <p>
                    That is the whole trick: ordinary web code, in a place that will keep it online.
                    If you want a head start, use one of the three starters in the editor — blank,
                    editorial or kinetic — and pull it apart.
                </p>
            </article>

            <div class="notice" style="margin-top: 40px;">
                <?= icon('arrow-right', 18) ?>
                <div><strong>Ready to try it?</strong> Create a page, choose a code starter, and open the editor. <a href="<?= is_logged_in() ? url('/dashboard/new') : url('/signup') ?>" style="color: var(--brand-secondary); text-decoration: underline;">Start a portfolio</a>.</div>
            </div>
        </div>
    </div>
</div>
<?php
page_end([
    'title' => 'Code guide · ' . APP_NAME,
    'description' => 'How custom HTML, CSS and JavaScript work on Portside: the editor, the sandbox, libraries, image uploads, limits and export.',
    'canonical' => absolute_url('/docs/code'),
    'bodyClass' => 'docs-page',
]);
