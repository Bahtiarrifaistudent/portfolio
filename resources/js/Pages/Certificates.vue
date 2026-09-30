<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '../Components/PageHeader.vue';

const props = defineProps({
    certificates: { type: Array, default: () => [] },
});

const categories = computed(() => ['Semua', ...new Set(props.certificates.map((c) => c.category).filter(Boolean))]);
const filter = ref('Semua');
const visible = computed(() => (filter.value === 'Semua' ? props.certificates : props.certificates.filter((c) => c.category === filter.value)));

// Pratinjau gambar sertifikat
const preview = ref(null);
</script>

<template>
    <Head title="Sertifikat" />

    <PageHeader
        kicker="Sertifikat"
        title="Kursus & sertifikasi."
        description="Bukti belajar yang sudah saya selesaikan. Klik gambar untuk memperbesar."
        :breadcrumb="[{ label: 'Beranda', href: '/' }, { label: 'Sertifikat' }]"
    />

    <section class="py-14 sm:py-20">
        <div class="container-page">
            <div v-if="categories.length > 2" v-reveal class="-mx-4 overflow-x-auto px-4 sm:mx-0 sm:px-0">
                <div class="inline-flex gap-2">
                    <button
                        v-for="c in categories"
                        :key="c"
                        type="button"
                        class="rounded-full border px-4 py-1.5 text-sm font-medium whitespace-nowrap transition"
                        :class="filter === c ? 'border-accent bg-accent/10 text-accent' : 'border-line text-muted hover:text-ink'"
                        @click="filter = c"
                    >
                        {{ c }}
                    </button>
                </div>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <article v-for="(c, i) in visible" :key="c.title" v-reveal="i * 60" class="card flex flex-col overflow-hidden">
                    <button
                        type="button"
                        class="relative aspect-[4/3] overflow-hidden border-b border-line bg-surface-2"
                        :disabled="!c.image"
                        :aria-label="c.image ? `Perbesar ${c.title}` : undefined"
                        @click="preview = c"
                    >
                        <img v-if="c.image" :src="c.image" :alt="c.title" loading="lazy" class="size-full object-cover transition duration-500 hover:scale-105" />
                        <span v-else class="absolute inset-0 grid place-items-center">
                            <span class="bg-grid absolute inset-0 opacity-60" />
                            <svg class="relative size-14 text-accent/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><circle cx="12" cy="9" r="6" /><path d="M8.5 14 7 22l5-3 5 3-1.5-8" /></svg>
                        </span>
                        <span v-if="c.example" class="absolute top-3 left-3 rounded-md border border-dashed border-amber-500/60 bg-bg/80 px-2 py-0.5 font-mono text-[11px] text-amber-500">Contoh</span>
                    </button>

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center justify-between gap-2 font-mono text-[11px] text-muted">
                            <span class="rounded-md bg-accent/10 px-2 py-0.5 text-accent">{{ c.category }}</span>
                            <span>{{ c.date }}</span>
                        </div>
                        <h2 class="mt-3 font-display text-lg leading-snug font-bold text-ink">{{ c.title }}</h2>
                        <p class="mt-1 flex-1 text-sm text-muted">{{ c.issuer }}</p>
                        <a v-if="c.url" :href="c.url" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:underline">
                            Lihat kredensial
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
                        </a>
                    </div>
                </article>
            </div>

            <p v-if="!visible.length" class="mt-10 text-center text-muted">Belum ada sertifikat.</p>
        </div>

        <!-- Modal pratinjau -->
        <transition enter-from-class="opacity-0" enter-active-class="transition duration-200" leave-to-class="opacity-0" leave-active-class="transition duration-150">
            <div v-if="preview" class="fixed inset-0 z-[60] grid place-items-center bg-black/80 p-4 backdrop-blur-sm" @click.self="preview = null" @keydown.esc="preview = null">
                <figure class="max-h-full max-w-4xl">
                    <img :src="preview.image" :alt="preview.title" class="max-h-[80vh] rounded-xl" />
                    <figcaption class="mt-3 flex items-center justify-between gap-4 text-sm text-white">
                        {{ preview.title }}
                        <button type="button" class="rounded-lg border border-white/30 px-3 py-1.5 hover:bg-white/10" @click="preview = null">Tutup</button>
                    </figcaption>
                </figure>
            </div>
        </transition>
    </section>
</template>
