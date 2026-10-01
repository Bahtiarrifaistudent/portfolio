<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '../Components/PageHeader.vue';
import ExperienceModal from '../Components/ExperienceModal.vue';

const props = defineProps({
    experience: { type: Array, default: () => [] },
});

// Label & icon for each experience type
const types = {
    education: { label: 'Education', icon: 'M22 10 12 5 2 10l10 5 10-5zM6 12v5c3 2 9 2 12 0v-5', cls: 'text-accent bg-accent/10' },
    work: { label: 'Work & Internship', icon: 'M3 7h18v13H3zM8 7V4h8v3', cls: 'text-laravel bg-laravel/10' },
    organization: { label: 'Organization', icon: 'M16 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 20a6 6 0 0 1 12 0M12 20a6 6 0 0 1 10 0', cls: 'text-cyan bg-cyan/10' },
    volunteer: { label: 'Volunteer', icon: 'M12 20s-7-4.4-9.3-8.6A5.2 5.2 0 0 1 12 6.3a5.2 5.2 0 0 1 9.3 5.1C19 15.6 12 20 12 20z', cls: 'text-rose-500 bg-rose-500/10' },
};
const typeOf = (key) => types[key] ?? { label: key, icon: types.work.icon, cls: 'text-muted bg-surface-2' };

const filters = computed(() => [
    { key: 'all', label: 'All' },
    ...Object.keys(types)
        .filter((k) => props.experience.some((e) => e.type === k))
        .map((k) => ({ key: k, label: types[k].label })),
]);
const filter = ref('all');
const visible = computed(() => (filter.value === 'all' ? props.experience : props.experience.filter((e) => e.type === filter.value)));

// Detail popup
const selected = ref(null);
const selectedTab = ref('company');
function openDetail(e, tab = 'company') {
    selectedTab.value = tab;
    selected.value = e;
}
const companyLabel = (e) => ({ education: 'Institution', organization: 'Organization', volunteer: 'Organizer' })[e.type] ?? 'Company';
const certCount = (e) => (e.certificates ?? (e.certificate ? [e.certificate] : [])).length;
const photoCount = (e) =>
    (e.company?.photos?.length ?? 0) +
    (e.projects ?? []).reduce((n, p) => n + (p.photos?.length ?? 0), 0) +
    (e.certificates ?? (e.certificate ? [e.certificate] : [])).reduce((n, c) => n + (c.photos?.length ?? 0), 0);
</script>

<template>
    <Head title="Experience" />

    <PageHeader
        kicker="Experience"
        title="Education, work, and organizations."
        description="Where I have studied, worked, and contributed."
        :breadcrumb="[{ label: 'Home', href: '/' }, { label: 'Experience' }]"
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

                    <article class="group card cursor-pointer p-5 sm:p-6" @click="openDetail(e)">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md px-2 py-0.5 font-mono text-[11px] font-medium" :class="typeOf(e.type).cls">{{ typeOf(e.type).label }}</span>
                            <span class="font-mono text-xs text-muted">{{ e.period }}</span>
                            <span v-if="e.example" class="rounded-md border border-dashed border-amber-500/50 px-2 py-0.5 font-mono text-[11px] text-amber-500">Example</span>
                        </div>
                        <div class="mt-3 flex items-center gap-3">
                            <span v-if="e.company?.logo" class="grid size-11 shrink-0 place-items-center overflow-hidden rounded-xl border border-line bg-surface-2">
                                <img :src="e.company.logo" :alt="e.place" class="size-full object-contain p-1" loading="lazy" />
                            </span>
                            <div class="min-w-0">
                                <h2 class="font-display text-xl font-bold text-ink transition group-hover:text-accent">{{ e.title }}</h2>
                                <p class="text-sm font-medium text-accent">{{ e.company?.name ?? e.place }}</p>
                            </div>
                        </div>
                        <p v-if="e.description" class="mt-3 text-sm leading-relaxed text-muted">{{ e.description }}</p>
                        <ul v-if="e.points?.length" class="mt-3 space-y-1.5">
                            <li v-for="pt in e.points" :key="pt" class="flex gap-2 text-sm text-muted">
                                <span class="mt-2 size-1.5 shrink-0 rounded-full bg-accent" />{{ pt }}
                            </li>
                        </ul>

                        <!-- Detail buttons -->
                        <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-line pt-4">
                            <button type="button" class="exp-btn" @click.stop="openDetail(e, 'company')">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1M14 9h1M9 13h1M14 13h1M10 21v-4h4v4" /></svg>
                                {{ companyLabel(e) }}
                            </button>
                            <button v-if="e.projects?.length" type="button" class="exp-btn" @click.stop="openDetail(e, 'projects')">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 9-4 3 4 3M16 9l4 3-4 3M13.5 6l-3 12" /></svg>
                                Projects <span class="opacity-60">{{ e.projects.length }}</span>
                            </button>
                            <button v-if="certCount(e)" type="button" class="exp-btn" @click.stop="openDetail(e, 'certificates')">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6" /><path d="M8.5 14 7 22l5-3 5 3-1.5-8" /></svg>
                                {{ certCount(e) > 1 ? 'Certificates' : 'Certificate' }} <span v-if="certCount(e) > 1" class="opacity-60">{{ certCount(e) }}</span>
                            </button>
                            <span v-if="photoCount(e)" class="ml-auto inline-flex items-center gap-1 font-mono text-[11px] text-muted">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2" /><circle cx="9" cy="10" r="2" /><path d="m21 16-5-5-9 8" /></svg>
                                {{ photoCount(e) }} photos
                            </span>
                        </div>
                    </article>
                </li>
            </ol>

            <p v-if="!visible.length" class="mt-10 text-center text-muted">Nothing here yet.</p>
        </div>

        <ExperienceModal :experience="selected" :tab="selectedTab" :type-label="selected ? typeOf(selected.type).label : ''" @close="selected = null" />
    </section>
</template>
