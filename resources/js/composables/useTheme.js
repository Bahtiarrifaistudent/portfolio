import { ref } from 'vue';

const isDark = ref(
    typeof document !== 'undefined' ? document.documentElement.classList.contains('dark') : true,
);

export function useTheme() {
    function toggle() {
        isDark.value = !isDark.value;
        document.documentElement.classList.toggle('dark', isDark.value);
        try {
            localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
        } catch (e) {
            // localStorage tidak tersedia, abaikan
        }
    }

    return { isDark, toggle };
}
