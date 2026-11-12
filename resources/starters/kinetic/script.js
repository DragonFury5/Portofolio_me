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