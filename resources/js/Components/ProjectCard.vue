<script setup>
import { Link } from '@inertiajs/vue3';
import { categoryOf } from '../projectCategories';

defineProps({
    project: { type: Object, required: true },
});
</script>

<template>
    <Link
        :href="`/project/${project.slug}`"
        class="group card flex h-full flex-col overflow-hidden transition hover:-translate-y-1 hover:border-accent/50 hover:shadow-xl hover:shadow-accent/5"
    >
        <div v-if="project.image" class="aspect-video overflow-hidden border-b border-line bg-surface-2">
            <img :src="project.image" :alt="project.title" loading="lazy" class="size-full object-cover transition duration-500 group-hover:scale-105" />
        </div>

        <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center justify-between">
                <span class="rounded-md px-2 py-1 font-mono text-[11px] font-medium" :class="categoryOf(project.category).cls">
                    {{ categoryOf(project.category).label }}
                </span>
                <span class="grid size-8 place-items-center rounded-lg border border-line text-muted transition group-hover:border-accent group-hover:text-accent">
                    <svg class="size-4 transition group-hover:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
                </span>
            </div>
            <h3 class="mt-5 font-display text-lg font-bold text-ink">{{ project.title }}</h3>
            <p class="mt-2 flex-1 text-sm leading-relaxed text-muted">{{ project.summary }}</p>
            <ul class="mt-5 flex flex-wrap gap-1.5">
                <li v-for="t in project.tags.slice(0, 4)" :key="t" class="chip">{{ t }}</li>
                <li v-if="project.tags.length > 4" class="chip">+{{ project.tags.length - 4 }}</li>
            </ul>
        </div>
    </Link>
</template>
