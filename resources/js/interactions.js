// Global hover interactions:
// 1. Every .card gets the cursor position (--mx, --my) for the glow effect.
// 2. v-tilt directive: the element tilts in 3D following the cursor (used on photos).

const canHover = () => window.matchMedia('(hover: hover) and (prefers-reduced-motion: no-preference)').matches;

export function initCardSpotlight() {
    document.addEventListener(
        'pointermove',
        (e) => {
            const card = e.target instanceof Element ? e.target.closest('.card') : null;
            if (!card) return;
            const r = card.getBoundingClientRect();
            card.style.setProperty('--mx', `${e.clientX - r.left}px`);
            card.style.setProperty('--my', `${e.clientY - r.top}px`);
        },
        { passive: true },
    );
}

export const tilt = {
    mounted(el, binding) {
        const max = binding.value ?? 8; // maximum degrees
        el.classList.add('tilt');

        const move = (e) => {
            if (!canHover()) return;
            const r = el.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            el.classList.add('is-tilting');
            el.style.setProperty('--ry', `${(px - 0.5) * max * 2}deg`);
            el.style.setProperty('--rx', `${(0.5 - py) * max * 2}deg`);
            el.style.setProperty('--mx', `${px * 100}%`);
            el.style.setProperty('--my', `${py * 100}%`);
        };
        const leave = () => {
            el.classList.remove('is-tilting');
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
        };

        el.addEventListener('pointermove', move);
        el.addEventListener('pointerleave', leave);
        el._tilt = { move, leave };
    },
    unmounted(el) {
        el.removeEventListener('pointermove', el._tilt.move);
        el.removeEventListener('pointerleave', el._tilt.leave);
    },
};
