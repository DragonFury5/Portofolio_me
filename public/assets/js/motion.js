(function () {
    'use strict';

    const root = document.documentElement;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const $ = (selector, scope = document) => scope.querySelector(selector);

    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));
    const smoothstep = (edge0, edge1, value) => {
        const x = clamp((value - edge0) / (edge1 - edge0), 0, 1);
        return x * x * (3 - 2 * x);
    };

    /* ----------------------------------- Progress -------------------------------- */

    const progressBar = $('[data-scroll-progress] span');
    let progressFrame = 0;

    function paintProgress() {
        progressFrame = 0;
        if (!progressBar) return;
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const value = max > 0 ? clamp(window.scrollY / max, 0, 1) : 0;
        progressBar.style.transform = `scaleX(${value.toFixed(4)})`;
    }

    function queueProgress() {
        if (progressFrame) return;
        progressFrame = window.requestAnimationFrame(paintProgress);
    }

    if (progressBar) {
        paintProgress();
        window.addEventListener('scroll', queueProgress, { passive: true });
        window.addEventListener('resize', queueProgress);
    }

    if (reduceMotion.matches) {
        return;
    }

    /* ------------------------------- Hero suction ------------------------------- */

    const cinema = $('[data-hero-cinema]');
    const effectMedia = window.matchMedia('(min-width: 1000px)');

    if (cinema) {
        const hero = $('.hero', cinema);
        const rift = $('[data-hero-rift]', cinema);
        const cue = $('[data-hero-cue]', cinema);
        const suckers = Array.from(cinema.querySelectorAll('[data-suck]'));
        let targets = [];
        let enabled = false;
        let frame = 0;
        let resizeTimer = 0;

        function travelDistance() {
            return clamp(window.innerHeight * 0.62, 340, 640);
        }

        function measure() {
            if (!enabled || !hero) return;

            // Remove inline motion so bounding boxes describe the resting layout.
            suckers.forEach((element) => {
                element.style.setProperty('--suck-x', '0px');
                element.style.setProperty('--suck-y', '0px');
                element.style.setProperty('--suck-scale', '1');
                element.style.setProperty('--suck-rotate', '0deg');
                element.style.opacity = '1';
                element.style.filter = 'none';
            });

            void cinema.offsetHeight;

            const scrollY = window.scrollY;
            const rects = suckers.map((element) => element.getBoundingClientRect());

            targets = suckers.map((element, index) => {
                const rect = rects[index];

                return {
                    element,
                    baseX: rect.left + rect.width / 2,
                    docY: rect.top + rect.height / 2 + scrollY,
                    spin: (index % 2 === 0 ? -1 : 1) * (3.5 + (index % 3) * 1.2),
                    delay: Math.min(0.15, index * 0.018),
                };
            });
        }

        function updateHero() {
            frame = 0;
            if (!enabled) return;

            const header = $('.site-head');
            const headerHeight = header ? header.offsetHeight : 72;
            const targetX = window.innerWidth / 2;
            const targetY = headerHeight + 25;
            const travel = travelDistance();
            const raw = clamp(window.scrollY / travel, 0, 1);
            const progress = raw * raw * (3 - 2 * raw);

            targets.forEach((target) => {
                const local = clamp((progress - target.delay) / (1 - target.delay), 0, 1);
                const eased = local * local * (3 - 2 * local);
                const opacity = 1 - smoothstep(0.26, 0.88, local);
                const scale = 1 - eased * 0.86;
                const baseViewportY = target.docY - window.scrollY;
                const dx = (targetX - target.baseX) * eased;
                const dy = (targetY - baseViewportY) * eased;

                target.element.style.setProperty('--suck-x', `${dx.toFixed(2)}px`);
                target.element.style.setProperty('--suck-y', `${dy.toFixed(2)}px`);
                target.element.style.setProperty('--suck-scale', scale.toFixed(4));
                target.element.style.setProperty('--suck-rotate', `${(target.spin * eased).toFixed(2)}deg`);
                target.element.style.opacity = opacity.toFixed(4);
                target.element.style.filter = `blur(${(eased * 4).toFixed(2)}px)`;
            });

            cinema.classList.toggle('is-collapsing', raw > 0.015 && raw < 0.99);

            if (cue) {
                cue.style.opacity = String(clamp(1 - progress * 8, 0, 1));
            }

            if (rift) {
                rift.classList.toggle('is-open', raw > 0.08 && raw < 0.96);
            }
        }

        function queueHero() {
            if (!enabled || frame) return;
            frame = window.requestAnimationFrame(updateHero);
        }

        function enableHero() {
            if (enabled || !effectMedia.matches || reduceMotion.matches) return;
            enabled = true;
            root.classList.add('cinema-enabled');
            window.addEventListener('scroll', queueHero, { passive: true });
            window.addEventListener('resize', onResize);

            window.requestAnimationFrame(() => {
                measure();
                updateHero();
            });
        }

        function disableHero() {
            if (!enabled) return;
            enabled = false;
            window.removeEventListener('scroll', queueHero);
            window.removeEventListener('resize', onResize);
            window.cancelAnimationFrame(frame);
            frame = 0;
            root.classList.remove('cinema-enabled');
            cinema.classList.remove('is-collapsing');
            if (rift) rift.classList.remove('is-open');
            if (cue) cue.style.opacity = '';

            suckers.forEach((element) => {
                element.style.removeProperty('--suck-x');
                element.style.removeProperty('--suck-y');
                element.style.removeProperty('--suck-scale');
                element.style.removeProperty('--suck-rotate');
                element.style.opacity = '';
                element.style.filter = '';
            });
        }

        function syncHeroMode() {
            if (effectMedia.matches && !reduceMotion.matches) {
                enableHero();
            } else {
                disableHero();
            }
        }

        function onResize() {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(() => {
                if (!enabled) return;
                measure();
                updateHero();
            }, 140);
        }

        effectMedia.addEventListener('change', syncHeroMode);
        reduceMotion.addEventListener('change', syncHeroMode);
        window.addEventListener('load', () => {
            if (!enabled) return;
            measure();
            updateHero();
        }, { once: true });

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => {
                if (!enabled) return;
                measure();
                updateHero();
            }).catch(() => {});
        }

        syncHeroMode();
    }

    /* --------------------------------- Spew reveals ------------------------------ */

    if (!('IntersectionObserver' in window)) {
        return;
    }

    const selectors = [
        '.hero-strip',
        '.section-head > div',
        '.section-head > .section-head__note',
        '.route-card',
        '.editor-showcase__copy > .eyebrow',
        '.editor-showcase__copy > h2',
        '.editor-showcase__copy > p',
        '.editor-showcase__copy > .feature-list',
        '.editor-showcase__copy > .link-arrow',
        '.editor-mock',
        '.tpl-card',
        '.work-card',
        '.principle',
        '.beta-band',
        '.faq-item',
        '.closing__grid > *',
        '.tpl-feature',
        '.explore-card',
        '.maker-site',
        '.docs-body .prose > h2',
        '.docs-body .prose > ul',
        '.docs-body .prose > .code-block',
        '.docs-body .prose > .notice',
        '.docs-body > .notice',
        '.code-block'
    ];

    const fullSpew = [
        '.hero-strip',
        '.section-head > div',
        '.section-head > .section-head__note',
        '.route-card',
        '.closing__grid > *',
        '.beta-band'
    ].join(',');

    const seen = new Set();
    const candidates = [];

    selectors.forEach((selector) => {
        document.querySelectorAll(selector).forEach((element) => {
            if (seen.has(element)) return;
            seen.add(element);

            if (element.closest('.hero-cinema, [data-no-reveal]')) return;

            const rect = element.getBoundingClientRect();
            if (!rect.height || !rect.width) return;
            if (rect.top < window.innerHeight * 0.93) return;

            const isFull = element.matches(fullSpew);
            const parent = element.parentElement;
            const index = parent ? Array.from(parent.children).indexOf(element) : 0;
            const delay = Math.min(260, Math.max(0, index) * 65);

            element.classList.add('scroll-reveal');
            element.style.setProperty('--reveal-delay', `${delay}ms`);
            candidates.push({ element, isFull });
        });
    });

    if (!candidates.length) return;

    const origin = $('[data-spew-origin]');
    let originTimer = 0;
    let lastPulse = 0;

    function pulseOrigin() {
        if (!origin) return;

        const now = performance.now();
        if (now - lastPulse < 260) return;
        lastPulse = now;

        origin.classList.remove('is-live');
        void origin.offsetWidth;
        origin.classList.add('is-live');

        window.clearTimeout(originTimer);
        originTimer = window.setTimeout(() => {
            origin.classList.remove('is-live');
        }, 980);
    }

    function reveal({ element, isFull }) {
        if (element.classList.contains('is-revealed')) return;

        const rect = element.getBoundingClientRect();
        const viewportCenter = window.innerWidth / 2;
        const centerX = rect.left + rect.width / 2;
        const spread = isFull ? 0.52 : 0.14;
        const dx = clamp((viewportCenter - centerX) * spread, -360, 360);
        const dy = isFull
            ? Math.min(240, window.innerHeight * 0.16)
            : Math.min(90, window.innerHeight * 0.07);

        element.style.setProperty('--reveal-x', `${dx.toFixed(1)}px`);
        element.style.setProperty('--reveal-y', `${dy.toFixed(1)}px`);
        element.style.setProperty('--reveal-rotate', `${(isFull ? 1.2 : 0.5) * (centerX < viewportCenter ? -1 : 1)}deg`);
        element.style.setProperty('--reveal-scale', isFull ? '0.8' : '0.94');
        element.classList.add('is-armed');

        void element.offsetWidth;

        window.requestAnimationFrame(() => {
            element.classList.add('is-revealed');
            if (isFull) pulseOrigin();

            element.addEventListener('transitionend', (event) => {
                if (event.propertyName !== 'transform') return;
                element.classList.remove('is-armed');
            });
        });
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            observer.unobserve(entry.target);

            const candidate = candidates.find((item) => item.element === entry.target);
            if (candidate) reveal(candidate);
        });
    }, {
        threshold: 0.08,
        rootMargin: '0px 0px -6% 0px',
    });

    candidates.forEach(({ element }) => observer.observe(element));

    window.addEventListener('resize', () => {
        // Once an item has been revealed its inline transform sets the destination.
        // Nothing to recalculate; this listener simply keeps the progress line true.
        queueProgress();
    }, { passive: true });
})();
