<script setup>
import { computed, onBeforeUnmount, onMounted, watch } from 'vue';
import { useCvDownload } from '../composables/useCvDownload';

const { state, count, file, close } = useCvDownload();

// Progress ring circumference (r = 52)
const circumference = 2 * Math.PI * 52;
// The ring shrinks every second: 3 -> full, 1 -> one third, done -> empty
const offset = computed(() => (state.value === 'done' ? 0 : circumference * (1 - count.value / 3)));

function onKey(e) {
    if (e.key === 'Escape' && state.value !== 'idle') close();
}
onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => window.removeEventListener('keydown', onKey));

// Close automatically a few seconds after the download starts
let autoClose;
watch(state, (s) => {
    clearTimeout(autoClose);
    if (s === 'done') autoClose = setTimeout(close, 3500);
});
</script>

<template>
    <transition
        enter-from-class="opacity-0"
        enter-active-class="transition duration-300"
        leave-to-class="opacity-0"
        leave-active-class="transition duration-200"
    >
        <div
            v-if="state !== 'idle'"
            class="fixed inset-0 z-[90] grid place-items-center bg-bg/70 p-4 backdrop-blur-md"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cv-title"
            @click.self="close"
        >
            <div class="card card-static relative w-full max-w-sm overflow-hidden p-8 text-center shadow-2xl">
                <div class="pointer-events-none absolute -top-20 left-1/2 size-56 -translate-x-1/2 rounded-full bg-accent/25 blur-3xl" />

                <div class="relative mx-auto size-36">
                    <svg class="size-full -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="var(--line)" stroke-width="8" />
                        <circle
                            cx="60"
                            cy="60"
                            r="52"
                            fill="none"
                            stroke="url(#cv-grad)"
                            stroke-width="8"
                            stroke-linecap="round"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="offset"
                            class="transition-[stroke-dashoffset] duration-1000 ease-linear"
                        />
                        <defs>
                            <linearGradient id="cv-grad" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="var(--accent)" />
                                <stop offset="100%" stop-color="var(--cyan)" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <div class="absolute inset-0 grid place-items-center">
                        <transition mode="out-in" enter-from-class="scale-150 opacity-0" enter-active-class="transition duration-300" leave-to-class="scale-50 opacity-0" leave-active-class="transition duration-200">
                            <span v-if="state === 'counting'" :key="count" class="font-display text-6xl font-bold text-ink">{{ count }}</span>
                            <svg v-else key="ok" class="size-14 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m5 12 5 5L20 7" />
                            </svg>
                        </transition>
                    </div>
                </div>

                <h2 id="cv-title" class="relative mt-6 font-display text-xl font-bold text-ink">
                    {{ state === 'counting' ? 'Preparing CV...' : 'Your CV is downloading!' }}
                </h2>
                <p class="relative mt-2 text-sm text-muted">
                    <template v-if="state === 'counting'">Download starts in {{ count }} {{ count === 1 ? 'second' : 'seconds' }}.</template>
                    <template v-else>
                        Didn't start?
                        <a :href="file" download class="font-semibold text-accent hover:underline">Click here</a>.
                    </template>
                </p>

                <button type="button" class="btn-ghost relative mt-6 w-full" @click="close">
                    {{ state === 'counting' ? 'Cancel' : 'Close' }}
                </button>
            </div>
        </div>
    </transition>
</template>
