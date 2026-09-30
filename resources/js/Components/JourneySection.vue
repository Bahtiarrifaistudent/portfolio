<script setup>
defineProps({
    journey: { type: Array, required: true },
});

const status = {
    done: { label: 'merged', dot: 'bg-emerald-500 border-emerald-500', tag: 'text-emerald-500 bg-emerald-500/10' },
    progress: { label: 'in progress', dot: 'bg-amber-400 border-amber-400 animate-pulse', tag: 'text-amber-500 bg-amber-400/10' },
    next: { label: 'planned', dot: 'bg-bg border-muted', tag: 'text-muted bg-surface-2' },
};
</script>

<template>
    <section id="journey" class="py-20 sm:py-28">
        <div class="container-page grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
            <div class="lg:sticky lg:top-28 lg:self-start">
                <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />Roadmap</p>
                <h2 v-reveal="60" class="section-title">My learning journey, as a commit log.</h2>
                <p v-reveal="120" class="mt-4 text-muted">
                    What I have mastered, what I am working on, and what comes next.
                </p>
                <div v-reveal="180" class="card mt-6 inline-flex items-center gap-3 px-4 py-3 font-mono text-xs text-muted">
                    <span class="text-emerald-500">$</span> git log --oneline --graph
                </div>
            </div>

            <ol class="relative">
                <span class="absolute top-2 bottom-2 left-[11px] w-px bg-gradient-to-b from-emerald-500 via-amber-400 to-line" />
                <li v-for="(step, i) in journey" :key="step.title" v-reveal="i * 70" class="relative pb-8 pl-12 last:pb-0">
                    <span class="absolute top-1.5 left-0 grid size-6 place-items-center">
                        <span class="size-3.5 rounded-full border-2" :class="status[step.status].dot" />
                    </span>
                    <div class="card p-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-[11px] text-muted">step-{{ String(i + 1).padStart(2, '0') }}</span>
                            <span class="rounded-md px-2 py-0.5 font-mono text-[11px] font-medium" :class="status[step.status].tag">
                                {{ status[step.status].label }}
                            </span>
                        </div>
                        <h3 class="mt-2 font-display text-lg font-bold text-ink">{{ step.title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-muted">{{ step.text }}</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>
</template>
