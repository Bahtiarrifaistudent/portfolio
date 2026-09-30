<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import HeroSection from '../Components/HeroSection.vue';
import FeaturedProject from '../Components/FeaturedProject.vue';
import ProjectCard from '../Components/ProjectCard.vue';
import TechIcon from '../Components/TechIcon.vue';

defineProps({
    stats: { type: Array, default: () => [] },
    featured: { type: Object, default: null },
    latestProjects: { type: Array, default: () => [] },
    stackPreview: { type: Array, default: () => [] },
});

const profile = computed(() => usePage().props.profile);

// Kartu pintasan ke halaman lain. Ubah teks di sini.
const shortcuts = [
    { href: '/tentang', title: 'Tentang', text: 'Cerita singkat, cara saya membangun aplikasi, dan tech stack.', icon: 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0' },
    { href: '/project', title: 'Project', text: 'Aplikasi web, eksperimen keamanan siber, dan AI.', icon: 'M3 7h18v13H3zM8 7V4h8v3' },
    { href: '/pengalaman', title: 'Pengalaman', text: 'Pendidikan, magang, dan organisasi.', icon: 'M12 8v4l3 2M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18z' },
    { href: '/sertifikat', title: 'Sertifikat', text: 'Kursus dan sertifikasi yang sudah diselesaikan.', icon: 'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM8.5 14 7 22l5-3 5 3-1.5-8' },
];
</script>

<template>
    <Head :title="profile.role" />

    <HeroSection :profile="profile" :stats="stats" />

    <!-- Pintasan halaman -->
    <section class="py-16 sm:py-20">
        <div class="container-page">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="(s, i) in shortcuts"
                    :key="s.href"
                    v-reveal="i * 70"
                    :href="s.href"
                    class="group card flex flex-col p-6 transition hover:-translate-y-1 hover:border-accent/50"
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

    <!-- Project unggulan -->
    <section v-if="featured" class="border-y border-line bg-surface/50 py-20 sm:py-24">
        <div class="container-page">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />Sorotan</p>
                    <h2 v-reveal="60" class="section-title">Project yang paling saya banggakan.</h2>
                </div>
                <Link v-reveal="100" href="/project" class="btn-ghost self-start sm:self-auto">Semua Project</Link>
            </div>

            <FeaturedProject v-reveal="120" :project="featured" :limit="4" class="mt-10" />

            <div v-if="latestProjects.length" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="(p, i) in latestProjects" :key="p.slug" v-reveal="i * 70">
                    <ProjectCard :project="p" />
                </div>
            </div>
        </div>
    </section>

    <!-- Stack utama -->
    <section class="py-20 sm:py-24">
        <div class="container-page text-center">
            <p v-reveal class="section-kicker justify-center"><span class="h-px w-6 bg-accent" />Tech Stack Utama<span class="h-px w-6 bg-accent" /></p>
            <h2 v-reveal="60" class="section-title">Laravel di belakang, Vue di depan.</h2>
            <ul v-reveal="120" class="mx-auto mt-10 flex max-w-4xl flex-wrap justify-center gap-3">
                <li v-for="t in stackPreview" :key="t.name" class="card flex items-center gap-2.5 px-4 py-3">
                    <TechIcon :name="t.icon" brand class="size-5" />
                    <span class="text-sm font-semibold text-ink">{{ t.name }}</span>
                </li>
            </ul>
            <Link v-reveal="160" href="/tentang" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-accent hover:underline">
                Lihat stack lengkap
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
                        <h2 class="font-display text-3xl font-bold sm:text-4xl">Punya project atau tawaran magang?</h2>
                        <p class="mt-2 max-w-xl text-white/85">Saya terbuka untuk magang, freelance, dan kolaborasi di bidang web development.</p>
                    </div>
                    <Link href="/kontak" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-[#1a0f2e] transition hover:-translate-y-0.5 hover:shadow-xl">
                        Hubungi Saya
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
