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