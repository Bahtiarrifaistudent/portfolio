<script setup>
import { Link } from '@inertiajs/vue3';
import ProjectCover from './ProjectCover.vue';

defineProps({
    project: { type: Object, required: true },
    // Limit the number of features shown (null = all)
    limit: { type: Number, default: null },
});
</script>

<template>
    <article class="group card relative overflow-hidden">
        <Link :href="`/projects/${project.slug}`" class="block aspect-[16/9] overflow-hidden border-b border-line sm:aspect-[21/8]" :aria-label="project.title">
            <ProjectCover :project="project" large />
        </Link>
        <div class="pointer-events-none absolute -top-24 -right-24 size-72 rounded-full bg-laravel/15 blur-3xl" />
        <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_1.15fr] lg:gap-10 lg:p-10">
            <div class="flex flex-col">
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-laravel/10 px-3 py-1 font-mono text-xs font-medium text-laravel">
                    <span class="size-1.5 rounded-full bg-laravel" />
                    Featured Project
                </span>
                <h3 class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ project.title }}</h3>
                <p class="mt-4 leading-relaxed text-muted">{{ project.summary }}</p>

                <ul class="mt-6 flex flex-wrap gap-2">
                    <li v-for="t in project.tags" :key="t" class="chip">{{ t }}</li>
                </ul>

                <div class="mt-auto flex flex-wrap items-center gap-3 pt-8">
                    <Link :href="`/projects/${project.slug}`" class="btn-primary">
                        View Details
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                    </Link>
                    <span v-if="!project.url" class="inline-flex items-center gap-2 font-mono text-xs text-muted">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2" /><path d="M8 11V7a4 4 0 0 1 8 0v4" /></svg>
                        Private repository
                    </span>
                </div>
            </div>

            <ul class="grid gap-3 sm:grid-cols-2">
                <li
                    v-for="(f, i) in limit ? project.features.slice(0, limit) : project.features"
                    :key="f.title"
                    class="tile bg-bg/60 p-4"
                >
                    <p class="font-mono text-[11px] text-accent">{{ String(i + 1).padStart(2, '0') }}</p>
                    <p class="mt-1 font-semibold text-ink">{{ f.title }}</p>
                    <p class="mt-1 text-sm leading-relaxed text-muted">{{ f.text }}</p>
                </li>
            </ul>
        </div>
    </article>
</template>
