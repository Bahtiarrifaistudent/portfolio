import { ref } from 'vue';

// Global state: shared by every "Download CV" button and the countdown modal in the layout.
const state = ref('idle'); // idle | counting | done
const count = ref(3);
const file = ref(null);
let timer = null;

function stop() {
    clearInterval(timer);
    timer = null;
}

function triggerDownload(url) {
    const a = document.createElement('a');
    a.href = url;
    a.download = url.split('/').pop();
    document.body.appendChild(a);
    a.click();
    a.remove();
}

export function useCvDownload() {
    function start(url) {
        if (!url || state.value === 'counting') return;
        file.value = url;
        count.value = 3;
        state.value = 'counting';
        stop();
        timer = setInterval(() => {
            if (count.value > 1) {
                count.value--;
                return;
            }
            stop();
            state.value = 'done';
            triggerDownload(file.value);
        }, 1000);
    }

    function close() {
        stop();
        state.value = 'idle';
    }

    return { state, count, file, start, close };
}
