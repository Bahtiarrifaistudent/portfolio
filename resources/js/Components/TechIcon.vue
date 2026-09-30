<script setup>
import { computed } from 'vue';
import {
    siComposer,
    siGit,
    siGithub,
    siGmail,
    siHtml5,
    siInertia,
    siJavascript,
    siJupyter,
    siKalilinux,
    siLaravel,
    siMysql,
    siNpm,
    siOpenapiinitiative,
    siOwasp,
    siPhp,
    siPostman,
    siPython,
    siTailwindcss,
    siVite,
    siVuedotjs,
    siWebrtc,
} from 'simple-icons';

// LinkedIn is no longer in simple-icons, so its path is written by hand.
const linkedin = {
    title: 'LinkedIn',
    hex: '0A66C2',
    path: 'M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z',
};

const icons = {
    composer: siComposer,
    git: siGit,
    github: siGithub,
    gmail: siGmail,
    html5: siHtml5,
    inertia: siInertia,
    javascript: siJavascript,
    jupyter: siJupyter,
    kalilinux: siKalilinux,
    laravel: siLaravel,
    linkedin,
    mysql: siMysql,
    npm: siNpm,
    openapiinitiative: siOpenapiinitiative,
    owasp: siOwasp,
    php: siPhp,
    postman: siPostman,
    python: siPython,
    tailwindcss: siTailwindcss,
    vite: siVite,
    vuedotjs: siVuedotjs,
    webrtc: siWebrtc,
};

const props = defineProps({
    name: { type: String, required: true },
    // true = brand color, false = follow the text color (currentColor)
    brand: { type: Boolean, default: false },
});

const icon = computed(() => icons[props.name] ?? siLaravel);

// Some brands are black/dark and would be invisible in dark mode.
const darkBrands = ['000000', '181717', '1E1E1E', '222222'];
const fill = computed(() => {
    if (!props.brand) return 'currentColor';
    return darkBrands.includes(icon.value.hex.toUpperCase()) ? 'currentColor' : `#${icon.value.hex}`;
});
</script>

<template>
    <svg role="img" viewBox="0 0 24 24" :fill="fill" aria-hidden="true">
        <path :d="icon.path" />
    </svg>
</template>
