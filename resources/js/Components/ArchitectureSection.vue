<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    layers: { type: Array, required: true },
});

// Classes are written out in full so Tailwind can detect them at build time.
const tone = {
    fuchsia: { text: 'text-accent', bg: 'bg-accent', soft: 'bg-accent/10', ring: 'border-accent', shadow: 'shadow-accent/25' },
    red: { text: 'text-laravel', bg: 'bg-laravel', soft: 'bg-laravel/10', ring: 'border-laravel', shadow: 'shadow-laravel/25' },
    cyan: { text: 'text-cyan', bg: 'bg-cyan', soft: 'bg-cyan/10', ring: 'border-cyan', shadow: 'shadow-cyan/25' },
    violet: { text: 'text-accent-2', bg: 'bg-accent-2', soft: 'bg-accent-2/10', ring: 'border-accent-2', shadow: 'shadow-accent-2/25' },
    emerald: { text: 'text-emerald-500', bg: 'bg-emerald-500', soft: 'bg-emerald-500/10', ring: 'border-emerald-500', shadow: 'shadow-emerald-500/25' },
};

const icons = {
    frontend: 'M3 5h18v12H3zM8 21h8M12 17v4',
    backend: 'M4 5h16v5H4zM4 14h16v5H4zM8 7.5h.01M8 16.5h.01',
    data: 'M4 6c0-1.7 3.6-3 8-3s8 1.3 8 3-3.6 3-8 3-8-1.3-8-3zM4 6v12c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3',
    ai: 'M9 3v2M15 3v2M9 19v2M15 19v2M3 9h2M3 15h2M19 9h2M19 15h2M7 7h10v10H7zM10 10h4v4h-4z',
    tooling: 'M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18v3h3l6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.4-.6-.6-2.4z',
};

const selected = ref(props.layers[1]?.key ?? props.layers[0].key);
const current = computed(() => props.layers.find((l) => l.key === selected.value));
</script>

<template>
    <section id="architecture" class="relative border-y border-line bg-surface/50 py-20 sm:py-28">
        <div class="container-page">
            <div class="max-w-2xl">
                <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />How I Build</p>
                <h2 v-reveal="60" class="section-title">From a user's click to the model and the database.</h2>
                <p v-reveal="120" class="mt-4 text-muted">
                    Every request passes through these layers. Pick one to see what I work on there.
                </p>
            </div>

            <!-- Layer flow -->
            <div v-reveal="160" class="relative mt-12">
                <!-- Packet path (desktop: horizontal, mobile: vertical) -->
                <div class="pointer-events-none absolute top-9 right-[12%] left-[12%] hidden h-px bg-line md:block">
                    <span class="animate-packet absolute -top-1 size-2 rounded-full bg-accent shadow-[0_0_12px_var(--accent)]" />
                    <span class="animate-packet absolute -top-1 size-2 rounded-full bg-cyan shadow-[0_0_12px_var(--cyan)] [animation-delay:1.6s]" />
                </div>
                <div class="pointer-events-none absolute top-[12%] bottom-[12%] left-9 w-px bg-line md:hidden">
                    <span class="animate-packet-y absolute -left-1 size-2 rounded-full bg-accent shadow-[0_0_12px_var(--accent)]" />
                </div>

                <ol class="relative grid gap-4 md:gap-6" :class="layers.length >= 5 ? 'md:grid-cols-5' : 'md:grid-cols-4'">
                    <li v-for="(layer, i) in layers" :key="layer.key">
                        <button
                            type="button"
                            class="group flex w-full items-center gap-4 text-left md:flex-col md:text-center"
                            :aria-pressed="selected === layer.key"
                            @click="selected = layer.key"
                            @mouseenter="selected = layer.key"
                        >
                            <span
                                class="relative grid size-[4.5rem] shrink-0 place-items-center rounded-2xl border-2 bg-surface transition duration-300"
                                :class="
                                    selected === layer.key
                                        ? [tone[layer.color].ring, tone[layer.color].text, 'scale-105 shadow-xl', tone[layer.color].shadow]
                                        : 'border-line text-muted group-hover:text-ink'
                                "
                            >
                                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path :d="icons[layer.key]" />
                                </svg>
                                <span class="absolute -top-2 -right-2 grid size-6 place-items-center rounded-full border border-line bg-bg font-mono text-[10px] text-muted">
                                    {{ i + 1 }}
                                </span>
                            </span>
                            <span>
                                <span class="block font-display text-lg font-bold" :class="selected === layer.key ? tone[layer.color].text : 'text-ink'">
                                    {{ layer.title }}
                                </span>
                                <span class="block font-mono text-xs text-muted">{{ layer.subtitle }}</span>
                            </span>
                        </button>
                    </li>
                </ol>
            </div>

            <!-- Layer details -->
            <transition mode="out-in" enter-from-class="opacity-0 translate-y-2" enter-active-class="transition duration-300" leave-to-class="opacity-0" leave-active-class="transition duration-150">
                <div :key="current.key" class="card card-static mt-10 grid gap-6 p-6 sm:p-8 md:grid-cols-[1.2fr_1fr] md:items-center">
                    <div>
                        <p class="font-mono text-xs" :class="tone[current.color].text">layer::{{ current.key }}</p>
                        <h3 class="mt-2 font-display text-2xl font-bold text-ink">{{ current.title }} <span class="text-muted">/ {{ current.subtitle }}</span></h3>
                        <p class="mt-3 leading-relaxed text-muted">{{ current.description }}</p>
                    </div>
                    <ul class="flex flex-wrap gap-2 md:justify-end">
                        <li
                            v-for="item in current.items"
                            :key="item"
                            class="inline-flex items-center gap-2 rounded-lg border border-line px-3 py-2 text-sm font-medium text-ink"
                            :class="tone[current.color].soft"
                        >
                            <span class="size-1.5 rounded-full" :class="tone[current.color].bg" />
                            {{ item }}
                        </li>
                    </ul>
                </div>
            </transition>
        </div>
    </section>
</template>
