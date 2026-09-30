// v-reveal: memunculkan elemen dengan animasi saat masuk viewport.
// Pakai v-reveal="120" untuk menambahkan delay (ms).
const observer =
    typeof window !== 'undefined' && 'IntersectionObserver' in window
        ? new IntersectionObserver(
              (entries) => {
                  entries.forEach((entry) => {
                      if (entry.isIntersecting) {
                          entry.target.classList.add('is-visible');
                          observer.unobserve(entry.target);
                      }
                  });
              },
              { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
          )
        : null;

export const reveal = {
    mounted(el, binding) {
        el.classList.add('reveal');
        if (binding.value) el.style.transitionDelay = `${binding.value}ms`;
        if (observer) observer.observe(el);
        else el.classList.add('is-visible');
    },
    unmounted(el) {
        if (observer) observer.unobserve(el);
    },
};
