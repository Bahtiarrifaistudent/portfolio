<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '../../Components/PageHeader.vue';
import { categoryOf } from '../../projectCategories';
import ProjectCover from '../../Components/ProjectCover.vue';

defineProps({
    project: { type: Object, required: true },
    prev: { type: Object, default: null },
    next: { type: Object, default: null },
});
</script>

<template>
    <Head :title="project.title" />

    <PageHeader
        :kicker="categoryOf(project.category).label"
        :title="project.title"
        :description="project.summary"
        :breadcrumb="[{ label: 'Home', href: '/' }, { label: 'Projects', href: '/projects' }, { label: project.title }]"
    >
        <div class="flex flex-wrap gap-3">
            <a v-if="project.demo" :href="project.demo" target="_blank" rel="noopener" class="btn-primary">
                Live Demo
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
            </a>
            <a v-if="project.url" :href="project.url" target="_blank" rel="noopener" :class="project.demo ? 'btn-ghost' : 'btn-primary'">
                Repository GitHub
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
            </a>
            <span v-if="!project.url && !project.demo" class="chip !px-3 !py-2 !text-xs">Private repository · demo available on request</span>
        </div>
    </PageHeader>

    <section class="py-14 sm:py-20">
        <div class="container-page grid gap-10 lg:grid-cols-[1fr_18rem]">
            <div class="min-w-0">
                <div v-reveal class="card mb-10 aspect-video overflow-hidden">
                    <ProjectCover :project="project" large />
                </div>

                <div v-reveal>
                    <h2 class="font-display text-2xl font-bold text-ink">About this project</h2>
                    <div v-if="project.description.length" class="mt-4 space-y-4 leading-relaxed text-muted">
                        <p v-for="(p, i) in project.description" :key="i">{{ p }}</p>
                    </div>
                    <p v-else class="mt-4 leading-relaxed text-muted">{{ project.summary }}</p>
                </div>

                <div v-if="project.features.length" class="mt-12">
                    <h2 v-reveal class="font-display text-2xl font-bold text-ink">Key features</h2>
                    <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                        <li v-for="(f, i) in project.features" :key="f.title" v-reveal="i * 50" class="card p-5">
                            <p class="font-mono text-[11px] text-accent">{{ String(i + 1).padStart(2, '0') }}</p>
                            <p class="mt-1 font-semibold text-ink">{{ f.title }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-muted">{{ f.text }}</p>
                        </li>
                    </ul>
                </div>
            </div>

            <aside v-reveal="100" class="lg:sticky lg:top-24 lg:self-start">
                <dl class="card card-static divide-y divide-line">
                    <div class="p-5">
                        <dt class="font-mono text-[11px] tracking-wider text-muted uppercase">Category</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ categoryOf(project.category).label }}</dd>
                    </div>
                    <div v-if="project.role" class="p-5">
                        <dt class="font-mono text-[11px] tracking-wider text-muted uppercase">Role</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ project.role }}</dd>
                    </div>
                    <div v-if="project.year" class="p-5">
                        <dt class="font-mono text-[11px] tracking-wider text-muted uppercase">Year</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ project.year }}</dd>
                    </div>
                    <div class="p-5">
                        <dt class="font-mono text-[11px] tracking-wider text-muted uppercase">Tech stack</dt>
                        <dd class="mt-3 flex flex-wrap gap-1.5">
                            <span v-for="t in project.tags" :key="t" class="chip">{{ t }}</span>
                        </dd>
                    </div>
                </dl>
            </aside>
        </div>

        <!-- Project navigation -->
        <nav class="container-page mt-16 grid gap-3 sm:grid-cols-2" aria-label="Other projects">
            <Link v-if="prev" :href="`/projects/${prev.slug}`" class="group card p-5">
                <span class="font-mono text-xs text-muted">&larr; Previous</span>
                <span class="mt-1 block font-semibold text-ink group-hover:text-accent">{{ prev.title }}</span>
            </Link>
            <span v-else class="hidden sm:block" />
            <Link v-if="next" :href="`/projects/${next.slug}`" class="group card p-5 text-right">
                <span class="font-mono text-xs text-muted">Next &rarr;</span>
                <span class="mt-1 block font-semibold text-ink group-hover:text-accent">{{ next.title }}</span>
            </Link>
        </nav>
    </section>
</template>
