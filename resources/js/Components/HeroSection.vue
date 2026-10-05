<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import TechIcon from './TechIcon.vue';
import { useCvViewer } from '../composables/useCvViewer';

const { open: viewCv } = useCvViewer();

const props = defineProps({
    profile: { type: Object, required: true },
    stats: { type: Array, required: true },
});

/* ---------- Typing effect for roles ---------- */
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

onMounted(tick);
onBeforeUnmount(() => clearTimeout(typeTimer));
</script>

<template>
    <section class="relative overflow-hidden pt-28 pb-8 sm:pt-36 sm:pb-12">
        <!-- Background -->
        <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black_30%,transparent_75%)]" />
        <div class="pointer-events-none absolute -top-40 left-1/2 h-[28rem] w-[46rem] -translate-x-1/2 rounded-full bg-accent-2/25 blur-[120px]" />
        <div class="pointer-events-none absolute top-40 -right-20 h-72 w-72 rounded-full bg-accent/20 blur-[100px]" />

        <div class="container-page relative grid items-center gap-12 lg:grid-cols-[1.15fr_1fr] lg:gap-10">
            <!-- Left -->
            <div>
                <p v-reveal class="chip gap-2 !text-xs">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-400" />
                    </span>
                    {{ profile.available ? 'Open to work & collaboration' : 'Currently busy' }}
                </p>

                <h1 v-reveal="80" class="mt-6 font-display text-5xl leading-[1.02] font-bold tracking-tight text-ink sm:text-6xl lg:text-7xl">
                    Hi, I'm<br />
                    <span class="text-gradient">{{ profile.name }}</span>
                </h1>

                <p v-reveal="160" class="mt-5 flex min-h-8 items-center font-mono text-lg text-ink sm:text-xl">
                    <span class="mr-2 text-accent">&gt;</span>
                    <span>{{ typed }}</span>
                    <span class="cursor-blink ml-0.5 inline-block h-6 w-2.5 bg-accent" />
                </p>

                <p v-reveal="240" class="mt-5 max-w-xl text-base leading-relaxed text-muted sm:text-lg">
                    {{ profile.tagline }} Building
                    <span class="font-semibold text-laravel">fullstack</span> apps for web, mobile, and desktop, with a strong focus on
                    <span class="font-semibold text-emerald-500">AI &amp; LLM engineering</span>.
                </p>

                <div v-reveal="320" class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link href="/projects" class="btn-primary">
                        View Projects
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                    </Link>
                    <button v-if="profile.cv" type="button" class="btn-ghost" @click="viewCv(profile.cv)">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
                        View CV
                    </button>
                    <Link v-else href="/contact" class="btn-ghost">Contact Me</Link>
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

            <!-- Right: monogram card (the profile photo is only shown on the About page) -->
            <div v-reveal="200" class="relative mx-auto w-full max-w-[22rem] lg:mr-0">
                <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-accent/40 via-accent-2/20 to-cyan/30 blur-3xl" />
                <div class="absolute inset-0 translate-x-4 translate-y-4 rounded-[2rem] border-2 border-dashed border-accent/40" />
                <div v-tilt class="relative aspect-[4/5] overflow-hidden rounded-[2rem] border border-line bg-gradient-to-br from-accent-2 via-accent to-cyan shadow-2xl shadow-black/30">
                    <div class="bg-grid absolute inset-0 opacity-40 mix-blend-overlay" />
                    <span
                        class="absolute inset-0 grid place-items-center font-display text-[8rem] font-bold tracking-tighter text-white/90"
                    >
                        {{ profile.initials }}
                    </span>
                    <div class="tilt-shine pointer-events-none absolute inset-0" />
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="container-page relative mt-16 sm:mt-20">
            <dl class="grid grid-cols-3 divide-x divide-line rounded-2xl border border-line bg-surface/60 backdrop-blur">
                <div v-for="(s, i) in stats" :key="s.label" v-reveal="i * 80" class="group flex flex-col px-3 py-5 text-center transition duration-300 first:rounded-l-2xl last:rounded-r-2xl hover:bg-accent/5 sm:px-6 sm:py-6">
                    <dt class="order-2 mt-1 text-[11px] text-muted sm:text-sm">{{ s.label }}</dt>
                    <dd class="order-1 font-display text-2xl font-bold text-ink transition duration-300 group-hover:scale-110 group-hover:text-accent sm:text-4xl">{{ s.value }}</dd>
                </div>
            </dl>
        </div>
    </section>
</template>
