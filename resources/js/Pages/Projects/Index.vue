<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '../../Components/PageHeader.vue';
import FeaturedProject from '../../Components/FeaturedProject.vue';
import ProjectCard from '../../Components/ProjectCard.vue';
import { projectCategories } from '../../projectCategories';

const props = defineProps({
    projects: { type: Array, default: () => [] },
});

const featured = computed(() => props.projects.filter((p) => p.featured));
const others = computed(() => props.projects.filter((p) => !p.featured));

const filters = computed(() => [
    { key: 'all', label: 'Semua' },
    ...Object.entries(projectCategories)
        .filter(([key]) => others.value.some((p) => p.category === key))
        .map(([key, c]) => ({ key, label: c.label })),
]);
const filter = ref('all');
const visible = computed(() => (filter.value === 'all' ? others.value : others.value.filter((p) => p.category === filter.value)));
const count = (key) => (key === 'all' ? others.value.length : others.value.filter((p) => p.category === key).length);
</script>

<template>
    <Head title="Project" />

    <PageHeader
        kicker="Project"
        title="Hal-hal yang sudah saya bangun."
        description="Dari aplikasi web Laravel + Vue, eksperimen keamanan siber, sampai eksplorasi AI. Klik salah satu untuk melihat detailnya."
        :breadcrumb="[{ label: 'Beranda', href: '/' }, { label: 'Project' }]"
    />

    <section class="py-14 sm:py-20">
        <div class="container-page">
            <div class="grid gap-6">
                <FeaturedProject v-for="p in featured" :key="p.slug" v-reveal :project="p" />
            </div>

            <div class="mt-14 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 v-reveal class="font-display text-xl font-bold text-ink">Project lainnya</h2>
                <div v-reveal="60" class="-mx-4 overflow-x-auto px-4 sm:mx-0 sm:px-0">
                    <div class="inline-flex gap-2">
                        <button
                            v-for="f in filters"
                            :key="f.key"
                            type="button"
                            class="rounded-full border px-4 py-1.5 text-sm font-medium whitespace-nowrap transition"
                            :class="filter === f.key ? 'border-accent bg-accent/10 text-accent' : 'border-line text-muted hover:text-ink'"
                            @click="filter = f.key"
                        >
                            {{ f.label }} <span class="font-mono text-[10px] opacity-60">{{ count(f.key) }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <transition-group
                tag="div"
                class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                enter-from-class="opacity-0 translate-y-3"
                enter-active-class="transition duration-300"
                leave-active-class="hidden"
            >
                <ProjectCard v-for="p in visible" :key="p.slug" :project="p" />
            </transition-group>

            <div v-reveal class="mt-12 text-center">
                <a href="https://github.com/Bahtiarrifaistudent?tab=repositories" target="_blank" rel="noopener" class="btn-ghost">
                    Semua repository di GitHub
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
                </a>
            </div>
        </div>
    </section>
</template>
