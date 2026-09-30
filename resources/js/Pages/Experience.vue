<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '../Components/PageHeader.vue';

const props = defineProps({
    experience: { type: Array, default: () => [] },
});

// Label & ikon tiap jenis pengalaman
const types = {
    education: { label: 'Pendidikan', icon: 'M22 10 12 5 2 10l10 5 10-5zM6 12v5c3 2 9 2 12 0v-5', cls: 'text-accent bg-accent/10' },
    work: { label: 'Kerja & Magang', icon: 'M3 7h18v13H3zM8 7V4h8v3', cls: 'text-laravel bg-laravel/10' },
    organization: { label: 'Organisasi', icon: 'M16 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 20a6 6 0 0 1 12 0M12 20a6 6 0 0 1 10 0', cls: 'text-cyan bg-cyan/10' },
};
const typeOf = (key) => types[key] ?? { label: key, icon: types.work.icon, cls: 'text-muted bg-surface-2' };

const filters = computed(() => [
    { key: 'all', label: 'Semua' },
    ...Object.keys(types)
        .filter((k) => props.experience.some((e) => e.type === k))
        .map((k) => ({ key: k, label: types[k].label })),
]);
const filter = ref('all');
const visible = computed(() => (filter.value === 'all' ? props.experience : props.experience.filter((e) => e.type === filter.value)));
</script>

<template>
    <Head title="Pengalaman" />

    <PageHeader
        kicker="Pengalaman"
        title="Perjalanan pendidikan, kerja, dan organisasi."
        description="Tempat saya belajar, bekerja, dan berkontribusi."
        :breadcrumb="[{ label: 'Beranda', href: '/' }, { label: 'Pengalaman' }]"
    />

    <section class="py-14 sm:py-20">
        <div class="container-page max-w-4xl">
            <div v-reveal class="-mx-4 overflow-x-auto px-4 sm:mx-0 sm:px-0">
                <div class="inline-flex gap-1 rounded-2xl border border-line bg-surface p-1">
                    <button
                        v-for="f in filters"
                        :key="f.key"
                        type="button"
                        class="rounded-xl px-4 py-2 text-sm font-semibold whitespace-nowrap transition"
                        :class="filter === f.key ? 'bg-ink text-bg' : 'text-muted hover:text-ink'"
                        @click="filter = f.key"
                    >
                        {{ f.label }}
                    </button>
                </div>
            </div>

            <ol class="relative mt-10">
                <span class="absolute top-2 bottom-2 left-[21px] w-px bg-line sm:left-[23px]" />
                <li v-for="(e, i) in visible" :key="`${e.title}-${e.place}`" v-reveal="i * 60" class="relative pb-8 pl-16 last:pb-0">
                    <span class="absolute top-0 left-0 grid size-11 place-items-center rounded-xl border border-line bg-bg sm:size-12" :class="typeOf(e.type).cls">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path :d="typeOf(e.type).icon" /></svg>
                    </span>

                    <article class="card p-5 sm:p-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md px-2 py-0.5 font-mono text-[11px] font-medium" :class="typeOf(e.type).cls">{{ typeOf(e.type).label }}</span>
                            <span class="font-mono text-xs text-muted">{{ e.period }}</span>
                            <span v-if="e.example" class="rounded-md border border-dashed border-amber-500/50 px-2 py-0.5 font-mono text-[11px] text-amber-500">Contoh</span>
                        </div>
                        <h2 class="mt-3 font-display text-xl font-bold text-ink">{{ e.title }}</h2>
                        <p class="text-sm font-medium text-accent">{{ e.place }}</p>
                        <p v-if="e.description" class="mt-3 text-sm leading-relaxed text-muted">{{ e.description }}</p>
                        <ul v-if="e.points?.length" class="mt-3 space-y-1.5">
                            <li v-for="pt in e.points" :key="pt" class="flex gap-2 text-sm text-muted">
                                <span class="mt-2 size-1.5 shrink-0 rounded-full bg-accent" />{{ pt }}
                            </li>
                        </ul>
                    </article>
                </li>
            </ol>

            <p v-if="!visible.length" class="mt-10 text-center text-muted">Belum ada data.</p>
        </div>
    </section>
</template>
