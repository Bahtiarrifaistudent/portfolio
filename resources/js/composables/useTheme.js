import { nextTick, ref } from 'vue';

const isDark = ref(
    typeof document !== 'undefined' ? document.documentElement.classList.contains('dark') : true,
);

function applyTheme(dark) {
    isDark.value = dark;
    document.documentElement.classList.toggle('dark', dark);
    try {
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    } catch (e) {
        // localStorage unavailable, ignore
    }
}

export function useTheme() {
    /**
     * Switch themes with a circle that grows from the toggle button.
     * Uses the View Transitions API; unsupported browsers switch instantly.
     */
    async function toggle(event) {
        const next = !isDark.value;
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!document.startViewTransition || reduceMotion) {
            applyTheme(next);
            return;
        }

        // Circle origin = center of the clicked button
        const rect = event?.currentTarget?.getBoundingClientRect?.();
        const x = rect ? rect.left + rect.width / 2 : window.innerWidth / 2;
        const y = rect ? rect.top + rect.height / 2 : 0;
        // Radius reaches the farthest screen corner
        const radius = Math.hypot(Math.max(x, window.innerWidth - x), Math.max(y, window.innerHeight - y));

        const root = document.documentElement;
        root.classList.add('theme-switching');

        const transition = document.startViewTransition(async () => {
            applyTheme(next);
            await nextTick();
        });

        try {
            await transition.ready;
            root.animate(
                { clipPath: [`circle(0px at ${x}px ${y}px)`, `circle(${radius}px at ${x}px ${y}px)`] },
                { duration: 650, easing: 'cubic-bezier(0.65, 0, 0.35, 1)', pseudoElement: '::view-transition-new(root)' },
            );
            await transition.finished;
        } finally {
            root.classList.remove('theme-switching');
        }
    }

    return { isDark, toggle };
}
