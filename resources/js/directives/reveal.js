// v-reveal: animates an element in when it enters the viewport.
// Use v-reveal="120" to add a delay (ms).
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
