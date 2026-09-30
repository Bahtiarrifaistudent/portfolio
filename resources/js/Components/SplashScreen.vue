<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

// Splash screen: shown on every full page load (opening the site / refresh) on any page.
// Navigating through the menu does not show it again, because the layout stays mounted.
const props = defineProps({
    profile: { type: Object, required: true },
    // Minimum progress animation duration (ms)
    duration: { type: Number, default: 1100 },
});

const visible = ref(true);
const progress = ref(0);

// Messages that change with progress. Edit the text here.
const steps = [
    { at: 0, text: 'Booting server...' },
    { at: 20, text: 'php artisan serve' },
    { at: 45, text: 'Loading Vue components...' },
    { at: 70, text: 'Preparing projects & tech stack...' },
    { at: 95, text: 'Ready. Welcome!' },
];
const message = computed(() => [...steps].reverse().find((s) => progress.value >= s.at).text);
const initials = computed(() => props.profile.initials);

let frame;
let startTime;

// Easing for a natural feel: fast at the start, slower at the end
const ease = (t) => 1 - Math.pow(1 - t, 3);

function run(now) {
    if (!startTime) startTime = now;
    const t = Math.min((now - startTime) / props.duration, 1);
    progress.value = Math.round(ease(t) * 100);
    if (t < 1) frame = requestAnimationFrame(run);
    else setTimeout(finish, 150);
}

function finish() {
    cancelAnimationFrame(frame);
    visible.value = false;
    document.documentElement.style.overflow = '';
}

onMounted(() => {
    document.documentElement.style.overflow = 'hidden';
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        progress.value = 100;
        setTimeout(finish, 300);
        return;
    }
    frame = requestAnimationFrame(run);
});

onBeforeUnmount(() => {
    cancelAnimationFrame(frame);
    document.documentElement.style.overflow = '';
});
</script>

<template>
    <transition leave-active-class="splash-leave" leave-to-class="splash-leave-to">
        <div
            v-if="visible"
            class="fixed inset-0 z-[100] flex flex-col items-center justify-center overflow-hidden bg-bg px-6"
            role="status"
            aria-live="polite"
            @click="finish()"
        >
            <!-- Background -->
            <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black_20%,transparent_70%)]" />
            <div class="splash-orb pointer-events-none absolute top-1/2 left-1/2 size-[36rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-accent-2/25 blur-[120px]" />

            <!-- Photo with spinning ring -->
            <div class="splash-pop relative size-40 sm:size-48">
                <div class="splash-ring absolute -inset-3 rounded-full" />
                <div class="absolute -inset-3 rounded-full bg-bg/0" />
                <div class="relative size-full overflow-hidden rounded-full border-4 border-bg bg-gradient-to-br from-accent-2 via-accent to-cyan shadow-2xl">
                    <span class="grid size-full place-items-center font-display text-6xl font-bold text-white">{{ initials }}</span>
                </div>
            </div>

            <!-- Name & role -->
            <h1 class="splash-rise mt-10 text-center font-display text-4xl font-bold tracking-tight text-ink sm:text-5xl" style="animation-delay: 0.05s">
                {{ profile.name.split(' ')[0] }}{{ ' ' }}<span class="text-gradient">{{ profile.name.split(' ').slice(1).join(' ') }}</span>
            </h1>
            <p class="splash-rise mt-3 font-mono text-sm text-muted" style="animation-delay: 0.1s">
                {{ profile.role }} · {{ profile.focus }}
            </p>

            <!-- Progress -->
            <div class="splash-rise mt-10 w-full max-w-sm" style="animation-delay: 0.15s">
                <div class="mb-2 flex items-center justify-between font-mono text-xs">
                    <span class="truncate text-muted"><span class="text-accent">&gt;</span> {{ message }}</span>
                    <span class="ml-3 text-ink tabular-nums">{{ progress }}%</span>
                </div>
                <div class="h-1.5 overflow-hidden rounded-full bg-surface-2">
                    <div
                        class="relative h-full rounded-full bg-gradient-to-r from-accent via-accent-2 to-cyan"
                        :style="{ width: progress + '%' }"
                    >
                        <span class="absolute top-1/2 right-0 size-3 translate-x-1/2 -translate-y-1/2 rounded-full bg-white shadow-[0_0_14px_var(--accent)]" />
                    </div>
                </div>
            </div>

            <p class="absolute bottom-8 font-mono text-[11px] text-muted/70">click anywhere to skip</p>
        </div>
    </transition>
</template>
