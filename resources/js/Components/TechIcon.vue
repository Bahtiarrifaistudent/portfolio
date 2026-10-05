<script setup>
import { computed } from 'vue';
import {
    siComposer,
    siDart,
    siDjango,
    siDocker,
    siElectron,
    siExpress,
    siFigma,
    siFirebase,
    siFlutter,
    siGit,
    siGithub,
    siGithubactions,
    siGmail,
    siGo,
    siGooglecolab,
    siHtml5,
    siHuggingface,
    siInertia,
    siJavascript,
    siJupyter,
    siKalilinux,
    siLangchain,
    siLanggraph,
    siLaravel,
    siLinux,
    siMysql,
    siNextdotjs,
    siNodedotjs,
    siNpm,
    siOllama,
    siOpenapiinitiative,
    siOpenjdk,
    siOwasp,
    siPhp,
    siPnpm,
    siPostgresql,
    siPostman,
    siPython,
    siPytorch,
    siReact,
    siRedis,
    siSocketdotio,
    siSqlite,
    siSupabase,
    siTailwindcss,
    siTensorflow,
    siTypescript,
    siVite,
    siVllm,
    siVscodium,
    siVuedotjs,
    siWebrtc,
} from 'simple-icons';

// LinkedIn is no longer in simple-icons, so its path is written by hand.
const linkedin = {
    title: 'LinkedIn',
    hex: '0A66C2',
    path: 'M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z',
};

// Icon names come from simple-icons (https://simpleicons.org) without the "si" prefix.
// To add one: import it above and add it to this map.
const icons = {
    composer: siComposer,
    dart: siDart,
    django: siDjango,
    docker: siDocker,
    electron: siElectron,
    express: siExpress,
    figma: siFigma,
    firebase: siFirebase,
    flutter: siFlutter,
    git: siGit,
    github: siGithub,
    githubactions: siGithubactions,
    gmail: siGmail,
    go: siGo,
    googlecolab: siGooglecolab,
    html5: siHtml5,
    huggingface: siHuggingface,
    inertia: siInertia,
    javascript: siJavascript,
    jupyter: siJupyter,
    kalilinux: siKalilinux,
    langchain: siLangchain,
    langgraph: siLanggraph,
    laravel: siLaravel,
    linux: siLinux,
    mysql: siMysql,
    nextdotjs: siNextdotjs,
    nodedotjs: siNodedotjs,
    npm: siNpm,
    ollama: siOllama,
    openapiinitiative: siOpenapiinitiative,
    openjdk: siOpenjdk,
    owasp: siOwasp,
    php: siPhp,
    pnpm: siPnpm,
    postgresql: siPostgresql,
    postman: siPostman,
    python: siPython,
    pytorch: siPytorch,
    react: siReact,
    redis: siRedis,
    socketdotio: siSocketdotio,
    sqlite: siSqlite,
    supabase: siSupabase,
    tailwindcss: siTailwindcss,
    tensorflow: siTensorflow,
    typescript: siTypescript,
    vite: siVite,
    vllm: siVllm,
    vscodium: siVscodium,
    vuedotjs: siVuedotjs,
    webrtc: siWebrtc,
    linkedin,
};

const props = defineProps({
    name: { type: String, default: '' },
    // true = brand color, false = follow the text color (currentColor)
    brand: { type: Boolean, default: false },
    // Shown as a monogram when there is no logo for this name (e.g. "Canva", "RAG")
    label: { type: String, default: '' },
});

const icon = computed(() => icons[props.name] ?? null);

// Monogram for technologies without a logo: 1-3 letters from the label or name
const monogram = computed(() => {
    const text = (props.label || props.name).replace(/[^A-Za-z0-9 /+.-]/g, '');
    const words = text.split(/[\s/+-]+/).filter(Boolean);
    if (words.length > 1) return words.slice(0, 2).map((w) => w[0]).join('').toUpperCase();
    const w = words[0] ?? '?';
    return (w.length <= 4 && w === w.toUpperCase() ? w.slice(0, 3) : w.slice(0, 2)).replace(/^./, (c) => c.toUpperCase());
});

// Some brands are black/dark and would be invisible in dark mode.
const darkBrands = ['000000', '181717', '1E1E1E', '222222'];
const fill = computed(() => {
    if (!props.brand || !icon.value) return 'currentColor';
    return darkBrands.includes(icon.value.hex.toUpperCase()) ? 'currentColor' : `#${icon.value.hex}`;
});
</script>

<template>
    <svg v-if="icon" role="img" viewBox="0 0 24 24" :fill="fill" aria-hidden="true">
        <path :d="icon.path" />
    </svg>
    <svg v-else viewBox="0 0 24 24" aria-hidden="true" :class="brand ? 'text-accent' : ''">
        <rect x="1" y="1" width="22" height="22" rx="6" fill="currentColor" opacity="0.14" />
        <text
            x="12"
            y="12.5"
            text-anchor="middle"
            dominant-baseline="middle"
            fill="currentColor"
            font-family="JetBrains Mono, monospace"
            font-weight="700"
            :font-size="monogram.length > 2 ? 7.5 : 10"
        >{{ monogram }}</text>
    </svg>
</template>
