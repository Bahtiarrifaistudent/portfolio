import { ref } from 'vue';

// Global state for the "View CV" modal (rendered once in SiteLayout).
const file = ref(null);

export function useCvViewer() {
    function open(url) {
        if (!url) return;
        // Phones usually can't show a PDF inside a page, so open it in a new tab there
        if (window.matchMedia('(max-width: 767px)').matches) {
            window.open(url, '_blank', 'noopener');
            return;
        }
        file.value = url;
    }

    function close() {
        file.value = null;
    }

    return { file, open, close };
}
