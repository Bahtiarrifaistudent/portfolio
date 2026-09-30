<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import HeroSection from '../Components/HeroSection.vue';
import ProjectCover from '../Components/ProjectCover.vue';
import ContributionGraph from '../Components/ContributionGraph.vue';
import { categoryOf } from '../projectCategories';
import TechIcon from '../Components/TechIcon.vue';

const props = defineProps({
    stats: { type: Array, default: () => [] },
    projects: { type: Array, default: () => [] },
    stackPreview: { type: Array, default: () => [] },
});

const profile = computed(() => usePage().props.profile);

// Project gallery: the featured project comes first
const showcase = computed(() => [...props.projects].sort((a, b) => Number(b.featured) - Number(a.featured)));

// Two marquee rows: the second row is reversed and moves the other way
const marqueeRows = computed(() => [props.stackPreview, [...props.stackPreview].reverse()]);

// Shortcut cards to other pages. Edit the text here.
const shortcuts = [
    { href: '/about', title: 'About', text: 'My story, how I build applications, and my tech stack.', icon: 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0' },
    { href: '/projects', title: 'Projects', text: 'Web applications, cyber security experiments, and AI.', icon: 'M3 7h18v13H3zM8 7V4h8v3' },
    { href: '/experience', title: 'Experience', text: 'Education, internships, and organizations.', icon: 'M12 8v4l3 2M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18z' },
    { href: '/certificates', title: 'Certificates', text: 'Courses and certifications I have completed.', icon: 'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM8.5 14 7 22l5-3 5 3-1.5-8' },
];
</script>

<template>
    <Head :title="profile.role" />

    <HeroSection :profile="profile" :stats="stats" />

    <!-- Page shortcuts -->
    <section class="py-16 sm:py-20">
        <div class="container-page">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="(s, i) in shortcuts"
                    :key="s.href"
                    v-reveal="i * 70"
                    :href="s.href"
                    class="group card flex flex-col p-6"
                >
                    <span class="grid size-11 place-items-center rounded-xl bg-accent/10 text-accent transition group-hover:bg-accent group-hover:text-white">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path :d="s.icon" /></svg>
                    </span>
                    <span class="mt-5 flex items-center justify-between font-display text-lg font-bold text-ink">
                        {{ s.title }}
                        <svg class="size-4 text-muted transition group-hover:translate-x-1 group-hover:text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                    </span>
                    <span class="mt-2 text-sm leading-relaxed text-muted">{{ s.text }}</span>
                </Link>
            </div>
        </div>
    </section>

    <!-- Projects: full-width moving gallery -->
    <section v-if="projects.length" class="relative overflow-hidden border-y border-line bg-surface/50 py-20 sm:py-24">
        <div class="container-page">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />Projects</p>
                    <h2 v-reveal="60" class="section-title">Things I have built.</h2>
                </div>
                <Link v-reveal="100" href="/projects" class="btn-ghost self-start sm:self-auto">
                    All Projects
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                </Link>
            </div>
        </div>

        <div v-reveal="120" class="marquee mt-8 flex overflow-hidden pt-4 pb-10">
            <ul
                v-for="copy in 2"
                :key="copy"
                class="marquee-track marquee-slow flex shrink-0 gap-5 pr-5"
                :aria-hidden="copy === 2"
            >
                <li v-for="(p, i) in showcase" :key="p.slug" class="shrink-0" :class="i % 2 ? 'sm:translate-y-6' : ''">
                    <Link
                        :href="`/projects/${p.slug}`"
                        :tabindex="copy === 2 ? -1 : 0"
                        class="group relative block aspect-[4/3] w-[18rem] overflow-hidden rounded-3xl border border-line bg-surface shadow-xl shadow-black/10 transition duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-accent/20 sm:w-[26rem]"
                    >
                        <ProjectCover :project="p" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent" />
                        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                            <div class="flex items-center gap-2">
                                <span class="rounded-md bg-white/15 px-2 py-0.5 font-mono text-[11px] text-white backdrop-blur">
                                    {{ categoryOf(p.category).label }}
                                </span>
                                <span v-if="p.featured" class="rounded-md bg-laravel px-2 py-0.5 font-mono text-[11px] text-white">Featured</span>
                            </div>
                            <h3 class="mt-2 font-display text-xl font-bold text-white sm:text-2xl">{{ p.title }}</h3>
                            <p class="mt-1 line-clamp-2 max-h-0 text-sm text-white/80 opacity-0 transition-all duration-500 group-hover:max-h-12 group-hover:opacity-100">
                                {{ p.summary }}
                            </p>
                        </div>
                        <span class="absolute top-4 right-4 grid size-9 place-items-center rounded-full bg-white/15 text-white backdrop-blur transition group-hover:rotate-45 group-hover:bg-white group-hover:text-black">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
                        </span>
                    </Link>
                </li>
            </ul>
        </div>
    </section>

    <!-- GitHub contribution graph -->
    <section class="py-20 sm:py-24">
        <div class="container-page">
            <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />GitHub Activity</p>
            <h2 v-reveal="60" class="section-title">My commit history over time.</h2>
            <p v-reveal="100" class="mt-3 max-w-2xl text-muted">All of my public contributions on GitHub, updated automatically.</p>
            <ContributionGraph v-reveal="140" class="mt-10" />
        </div>
    </section>

    <!-- Core stack: full-width horizontal marquee -->
    <section class="py-20 sm:py-24">
        <div class="container-page text-center">
            <p v-reveal class="section-kicker justify-center"><span class="h-px w-6 bg-accent" />Core Tech Stack<span class="h-px w-6 bg-accent" /></p>
            <h2 v-reveal="60" class="section-title">Laravel on the back, Vue on the front.</h2>
        </div>

        <div v-reveal="120" class="marquee-mask mt-10 grid gap-4">
            <div v-for="(row, r) in marqueeRows" :key="r" class="marquee group flex overflow-hidden py-2">
                <!-- Two copies for a seamless loop -->
                <ul
                    v-for="copy in 2"
                    :key="copy"
                    class="marquee-track flex shrink-0 gap-4 pr-4"
                    :class="r % 2 ? 'marquee-reverse' : ''"
                    :aria-hidden="copy === 2"
                >
                    <li v-for="t in row" :key="t.name" class="card flex shrink-0 items-center gap-3 px-5 py-3.5">
                        <TechIcon :name="t.icon" brand class="size-6" />
                        <span class="text-sm font-semibold whitespace-nowrap text-ink sm:text-base">{{ t.name }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container-page text-center">
            <Link v-reveal="160" href="/about" class="mt-10 inline-flex items-center gap-2 text-sm font-semibold text-accent hover:underline">
                See the full stack
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
            </Link>
        </div>
    </section>

    <!-- CTA -->
    <section class="pb-20 sm:pb-28">
        <div class="container-page">
            <div v-reveal class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-accent-2 via-accent to-cyan p-8 text-white sm:p-12">
                <div class="bg-grid absolute inset-0 opacity-30 mix-blend-overlay" />
                <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="font-display text-3xl font-bold sm:text-4xl">Have a project or an internship opening?</h2>
                        <p class="mt-2 max-w-xl text-white/85">I am open to internships, freelance work, and collaboration in web development.</p>
                    </div>
                    <Link href="/contact" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-[#1a0f2e] transition hover:-translate-y-0.5 hover:shadow-xl">
                        Contact Me
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
