<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import TechIcon from './TechIcon.vue';

const props = defineProps({
    profile: { type: Object, required: true },
    stats: { type: Array, required: true },
});

/* ---------- Efek mengetik untuk peran ---------- */
const typed = ref('');
let roleIndex = 0;
let charIndex = 0;
let deleting = false;
let typeTimer;

function tick() {
    const roles = props.profile.typed_roles;
    const word = roles[roleIndex % roles.length];

    if (!deleting) {
        typed.value = word.slice(0, ++charIndex);
        if (charIndex === word.length) {
            deleting = true;
            typeTimer = setTimeout(tick, 1600);
            return;
        }
    } else {
        typed.value = word.slice(0, --charIndex);
        if (charIndex === 0) {
            deleting = false;
            roleIndex++;
        }
    }
    typeTimer = setTimeout(tick, deleting ? 40 : 75);
}

/* ---------- Terminal "php artisan about" ---------- */
const terminalLines = computed(() => [
    { k: 'Name', v: props.profile.name },
    { k: 'Role', v: props.profile.role },
    { k: 'Stack', v: 'Laravel · Inertia · Vue' },
    { k: 'Realtime', v: 'Reverb + WebRTC' },
    { k: 'Campus', v: props.profile.campus },
    { k: 'Status', v: props.profile.available ? 'OPEN TO WORK' : 'BUSY', ok: props.profile.available },
]);
const shown = ref(0);
let lineTimer;

onMounted(() => {
    tick();
    lineTimer = setInterval(() => {
        if (shown.value < terminalLines.value.length) shown.value++;
        else clearInterval(lineTimer);
    }, 260);
});

onBeforeUnmount(() => {
    clearTimeout(typeTimer);
    clearInterval(lineTimer);
});
</script>

<template>
    <section class="relative overflow-hidden pt-28 pb-8 sm:pt-36 sm:pb-12">
        <!-- Latar -->
        <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black_30%,transparent_75%)]" />
        <div class="pointer-events-none absolute -top-40 left-1/2 h-[28rem] w-[46rem] -translate-x-1/2 rounded-full bg-accent-2/25 blur-[120px]" />
        <div class="pointer-events-none absolute top-40 -right-20 h-72 w-72 rounded-full bg-accent/20 blur-[100px]" />

        <div class="container-page relative grid items-center gap-12 lg:grid-cols-[1.15fr_1fr] lg:gap-10">
            <!-- Kiri -->
            <div>
                <p v-reveal class="chip gap-2 !text-xs">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-400" />
                    </span>
                    {{ profile.available ? 'Terbuka untuk magang & kolaborasi' : 'Sedang sibuk' }}
                </p>

                <h1 v-reveal="80" class="mt-6 font-display text-5xl leading-[1.02] font-bold tracking-tight text-ink sm:text-6xl lg:text-7xl">
                    Halo, saya<br />
                    <span class="text-gradient">{{ profile.name }}</span>
                </h1>

                <p v-reveal="160" class="mt-5 flex min-h-8 items-center font-mono text-lg text-ink sm:text-xl">
                    <span class="mr-2 text-accent">&gt;</span>
                    <span>{{ typed }}</span>
                    <span class="cursor-blink ml-0.5 inline-block h-6 w-2.5 bg-accent" />
                </p>

                <p v-reveal="240" class="mt-5 max-w-xl text-base leading-relaxed text-muted sm:text-lg">
                    {{ profile.tagline }} Fokus di backend
                    <span class="font-semibold text-laravel">Laravel</span>, tampil di depan dengan
                    <span class="font-semibold text-emerald-500">Vue.js</span>.
                </p>

                <div v-reveal="320" class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link href="/project" class="btn-primary">
                        Lihat Project
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                    </Link>
                    <a v-if="profile.cv" :href="profile.cv" class="btn-ghost" download>Unduh CV</a>
                    <Link v-else href="/kontak" class="btn-ghost">Hubungi Saya</Link>
                </div>

                <div v-reveal="400" class="mt-8 flex items-center gap-2">
                    <a
                        v-for="s in profile.socials"
                        :key="s.label"
                        :href="s.url"
                        :aria-label="s.label"
                        target="_blank"
                        rel="noopener"
                        class="grid size-10 place-items-center rounded-xl border border-line bg-surface text-muted transition hover:-translate-y-0.5 hover:border-accent hover:text-accent"
                    >
                        <TechIcon :name="s.icon" class="size-4" />
                    </a>
                </div>
            </div>

            <!-- Kanan: terminal -->
            <div v-reveal="200" class="relative">
                <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-accent/30 via-accent-2/10 to-cyan/20 blur-2xl" />
                <div class="card relative overflow-hidden shadow-2xl shadow-black/20">
                    <div class="flex items-center gap-2 border-b border-line bg-surface-2 px-4 py-3">
                        <span class="size-3 rounded-full bg-[#ff5f57]" />
                        <span class="size-3 rounded-full bg-[#febc2e]" />
                        <span class="size-3 rounded-full bg-[#28c840]" />
                        <span class="ml-3 truncate font-mono text-xs text-muted">~/bahtiar — zsh</span>
                    </div>

                    <div class="p-4 font-mono text-xs leading-relaxed sm:p-6 sm:text-sm">
                        <p class="text-muted">
                            <span class="text-emerald-500">➜</span>
                            <span class="text-cyan"> ~/bahtiar</span>
                            <span class="text-ink"> php artisan about</span>
                        </p>

                        <p class="mt-4 flex items-center gap-2 text-ink">
                            <span class="font-bold text-laravel">Environment</span>
                            <span class="h-px flex-1 border-t border-dashed border-line" />
                        </p>

                        <ul class="mt-2 space-y-1.5">
                            <li
                                v-for="(line, i) in terminalLines"
                                :key="line.k"
                                class="flex gap-3 transition-all duration-300"
                                :class="i < shown ? 'translate-x-0 opacity-100' : 'translate-x-2 opacity-0'"
                            >
                                <span class="w-[4.5rem] shrink-0 text-muted sm:w-24">{{ line.k }}</span>
                                <span class="hidden h-px flex-1 translate-y-2.5 border-t border-dotted border-line sm:block" />
                                <span
                                    class="ml-auto text-right"
                                    :class="line.ok === undefined ? 'text-ink' : line.ok ? 'font-bold text-emerald-500' : 'font-bold text-amber-500'"
                                >
                                    {{ line.v }}
                                </span>
                            </li>
                        </ul>

                        <p class="mt-5 text-muted">
                            <span class="text-emerald-500">➜</span>
                            <span class="text-cyan"> ~/bahtiar</span>
                            <span class="cursor-blink ml-1 inline-block h-4 w-2 translate-y-0.5 bg-ink" />
                        </p>
                    </div>
                </div>

                <!-- Label mengambang -->
                <div class="card absolute -bottom-5 -left-3 hidden items-center gap-2.5 px-3.5 py-2.5 shadow-xl sm:flex">
                    <TechIcon name="laravel" brand class="size-5" />
                    <div class="leading-tight">
                        <p class="text-xs font-semibold text-ink">Laravel 12</p>
                        <p class="font-mono text-[10px] text-muted">backend first</p>
                    </div>
                </div>
                <div class="card absolute -top-4 -right-3 hidden items-center gap-2.5 px-3.5 py-2.5 shadow-xl sm:flex">
                    <TechIcon name="vuedotjs" brand class="size-5" />
                    <div class="leading-tight">
                        <p class="text-xs font-semibold text-ink">Vue 3</p>
                        <p class="font-mono text-[10px] text-muted">&lt;script setup&gt;</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="container-page relative mt-16 sm:mt-20">
            <dl class="grid grid-cols-3 divide-x divide-line rounded-2xl border border-line bg-surface/60 backdrop-blur">
                <div v-for="(s, i) in stats" :key="s.label" v-reveal="i * 80" class="flex flex-col px-3 py-5 text-center sm:px-6 sm:py-6">
                    <dt class="order-2 mt-1 text-[11px] text-muted sm:text-sm">{{ s.label }}</dt>
                    <dd class="order-1 font-display text-2xl font-bold text-ink sm:text-4xl">{{ s.value }}</dd>
                </div>
            </dl>
        </div>
    </section>
</template>
