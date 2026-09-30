<script setup>
import { computed, ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '../Components/PageHeader.vue';
import CertificateModal from '../Components/CertificateModal.vue';

const props = defineProps({
    certificates: { type: Array, default: () => [] },
});

const categories = computed(() => ['All', ...new Set(props.certificates.map((c) => c.category).filter(Boolean))]);
const filter = ref('All');
const visible = computed(() => (filter.value === 'All' ? props.certificates : props.certificates.filter((c) => c.category === filter.value)));
watch(filter, () => (openIndex.value = null));

// Index (within the filtered list) of the certificate open in the popup
const openIndex = ref(null);
</script>

<template>
    <Head title="Certificates" />

    <PageHeader
        kicker="Certificates"
        title="Courses & certifications."
        description="Proof of the learning I have completed. Click any certificate to see its details."
        :breadcrumb="[{ label: 'Home', href: '/' }, { label: 'Certificates' }]"
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
                <article
                    v-for="(c, i) in visible"
                    :key="c.title"
                    v-reveal="i * 60"
                    class="group card flex cursor-pointer flex-col overflow-hidden"
                    role="button"
                    tabindex="0"
                    :aria-label="`View details of ${c.title}`"
                    @click="openIndex = i"
                    @keydown.enter.prevent="openIndex = i"
                    @keydown.space.prevent="openIndex = i"
                >
                    <div class="relative aspect-[4/3] overflow-hidden border-b border-line bg-surface-2">
                        <img v-if="c.image" :src="c.image" :alt="c.title" loading="lazy" class="size-full object-cover transition duration-500 group-hover:scale-105" />
                        <span v-else class="absolute inset-0 grid place-items-center">
                            <span class="bg-grid absolute inset-0 opacity-60" />
                            <svg class="relative size-14 text-accent/60 transition duration-500 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><circle cx="12" cy="9" r="6" /><path d="M8.5 14 7 22l5-3 5 3-1.5-8" /></svg>
                        </span>
                        <span v-if="c.example" class="absolute top-3 left-3 rounded-md border border-dashed border-amber-500/60 bg-bg/80 px-2 py-0.5 font-mono text-[11px] text-amber-500">Example</span>
                        <!-- Hover overlay -->
                        <span class="absolute inset-0 grid place-items-center bg-bg/40 opacity-0 backdrop-blur-[2px] transition duration-300 group-hover:opacity-100">
                            <span class="inline-flex translate-y-2 items-center gap-2 rounded-full bg-ink px-4 py-2 text-sm font-semibold text-bg shadow-lg transition duration-300 group-hover:translate-y-0">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
                                View details
                            </span>
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center justify-between gap-2 font-mono text-[11px] text-muted">
                            <span class="rounded-md bg-accent/10 px-2 py-0.5 text-accent">{{ c.category }}</span>
                            <span>{{ c.date }}</span>
                        </div>
                        <h2 class="mt-3 font-display text-lg leading-snug font-bold text-ink">{{ c.title }}</h2>
                        <p class="mt-1 flex-1 text-sm text-muted">{{ c.issuer }}</p>
                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-accent">
                            View details
                            <svg class="size-3.5 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                        </span>
                    </div>
                </article>
            </div>

            <p v-if="!visible.length" class="mt-10 text-center text-muted">No certificates yet.</p>
        </div>

        <CertificateModal v-model="openIndex" :certificates="visible" />
    </section>
</template>
