<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import PhotoCarousel from './PhotoCarousel.vue';

// Experience detail popup with tabs: Company / Projects / Certificate.
// Optional fields per experience entry in config/portfolio.php:
//   company      => [name, logo, website, location, industry, description, photos[]]
//   projects     => [[title, description, tech[], url, photos[]], ...]
//   certificates => [[title, issuer, date, credential_id, url, photos[]], ...]
//                   (a single 'certificate' => [...] also works)
const props = defineProps({
    experience: { type: Object, default: null },
    // which tab to open: company | projects | certificates
    tab: { type: String, default: 'company' },
    typeLabel: { type: String, default: '' },
});
const emit = defineEmits(['close']);

const active = ref('company');
const projectIndex = ref(0);
const certIndex = ref(0);

const e = computed(() => props.experience);
const company = computed(() => ({ name: e.value?.place, ...(e.value?.company ?? {}) }));
const projects = computed(() => e.value?.projects ?? []);
const certificates = computed(() => e.value?.certificates ?? (e.value?.certificate ? [e.value.certificate] : []));
const project = computed(() => projects.value[projectIndex.value]);
const certificate = computed(() => certificates.value[certIndex.value]);

const companyLabel = computed(() => ({ education: 'Institution', organization: 'Organization' })[e.value?.type] ?? 'Company');

const tabs = computed(() =>
    [
        { key: 'company', label: companyLabel.value, count: null },
        { key: 'projects', label: 'Projects', count: projects.value.length },
        { key: 'certificates', label: certificates.value.length > 1 ? 'Certificates' : 'Certificate', count: certificates.value.length },
    ].filter((t) => t.count === null || t.count > 0),
);

const initials = computed(() =>
    (company.value.name || '?')
        .split(/\s+/)
        .filter((w) => /^[A-Za-z]/.test(w))
        .slice(0, 2)
        .map((w) => w[0].toUpperCase())
        .join(''),
);

const host = (url) => {
    try {
        return new URL(url).host.replace(/^www\./, '');
    } catch (err) {
        return url;
    }
};

watch(
    () => props.experience,
    (v) => {
        document.documentElement.style.overflow = v ? 'hidden' : '';
        if (v) {
            active.value = tabs.value.some((t) => t.key === props.tab) ? props.tab : 'company';
            projectIndex.value = 0;
            certIndex.value = 0;
        }
    },
);

function onKey(ev) {
    if (ev.key === 'Escape' && props.experience) emit('close');
}
onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    document.documentElement.style.overflow = '';
});
</script>

<template>
    <transition enter-from-class="opacity-0" enter-active-class="transition duration-300" leave-to-class="opacity-0" leave-active-class="transition duration-200">
        <div
            v-if="experience"
            class="fixed inset-0 z-[85] flex items-center justify-center bg-bg/80 p-3 backdrop-blur-md sm:p-6"
            role="dialog"
            aria-modal="true"
            aria-labelledby="exp-title"
            @click.self="emit('close')"
        >
            <article class="cert-in card card-static flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden shadow-2xl">
                <!-- Header -->
                <header class="flex items-start gap-4 border-b border-line p-5">
                    <span class="grid size-14 shrink-0 place-items-center overflow-hidden rounded-2xl border border-line bg-surface-2">
                        <img v-if="company.logo" :src="company.logo" :alt="company.name" class="size-full object-contain p-1.5" />
                        <span v-else class="font-display text-lg font-bold text-accent">{{ initials }}</span>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span v-if="typeLabel" class="rounded-md bg-accent/10 px-2 py-0.5 font-mono text-[11px] text-accent">{{ typeLabel }}</span>
                            <span class="font-mono text-xs text-muted">{{ experience.period }}</span>
                            <span v-if="experience.example" class="rounded-md border border-dashed border-amber-500/60 px-2 py-0.5 font-mono text-[11px] text-amber-500">Example</span>
                        </div>
                        <h2 id="exp-title" class="mt-1.5 font-display text-xl leading-tight font-bold text-ink sm:text-2xl">{{ experience.title }}</h2>
                        <p class="text-sm font-medium text-accent">{{ company.name }}</p>
                    </div>
                    <button type="button" class="grid size-9 shrink-0 place-items-center rounded-lg text-muted transition hover:bg-surface-2 hover:text-ink" aria-label="Close" @click="emit('close')">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 6l12 12M18 6L6 18" /></svg>
                    </button>
                </header>

                <!-- Tabs -->
                <nav v-if="tabs.length > 1" class="flex gap-1 overflow-x-auto border-b border-line px-5 pt-2" role="tablist">
                    <button
                        v-for="t in tabs"
                        :key="t.key"
                        type="button"
                        role="tab"
                        :aria-selected="active === t.key"
                        class="relative shrink-0 px-3 py-2.5 text-sm font-semibold transition"
                        :class="active === t.key ? 'text-accent' : 'text-muted hover:text-ink'"
                        @click="active = t.key"
                    >
                        {{ t.label }}
                        <span v-if="t.count" class="ml-1 rounded-full bg-surface-2 px-1.5 font-mono text-[10px]">{{ t.count }}</span>
                        <span v-if="active === t.key" class="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-accent" />
                    </button>
                </nav>

                <!-- Body -->
                <div class="min-h-0 flex-1 overflow-y-auto p-5 sm:p-6">
                    <transition mode="out-in" enter-from-class="opacity-0 translate-y-2" enter-active-class="transition duration-250" leave-to-class="opacity-0" leave-active-class="transition duration-100">
                        <!-- Company -->
                        <section v-if="active === 'company'" key="company" class="grid gap-6 md:grid-cols-[1.25fr_1fr]">
                            <PhotoCarousel :photos="company.photos ?? []" :alt="company.name" />
                            <div>
                                <p class="text-sm leading-relaxed text-muted">{{ company.description || experience.description }}</p>
                                <dl class="mt-4 grid gap-2">
                                    <div v-if="company.industry" class="tile flex items-center justify-between gap-3 px-3 py-2.5">
                                        <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Industry</dt>
                                        <dd class="text-right text-sm font-semibold text-ink">{{ company.industry }}</dd>
                                    </div>
                                    <div v-if="company.location" class="tile flex items-center justify-between gap-3 px-3 py-2.5">
                                        <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Location</dt>
                                        <dd class="text-right text-sm font-semibold text-ink">{{ company.location }}</dd>
                                    </div>
                                    <div class="tile flex items-center justify-between gap-3 px-3 py-2.5">
                                        <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Period</dt>
                                        <dd class="text-right text-sm font-semibold text-ink">{{ experience.period }}</dd>
                                    </div>
                                </dl>
                                <div v-if="experience.points?.length" class="mt-5">
                                    <p class="font-mono text-[10px] tracking-wider text-muted uppercase">Highlights</p>
                                    <ul class="mt-2 space-y-1.5">
                                        <li v-for="pt in experience.points" :key="pt" class="flex gap-2 text-sm text-muted">
                                            <span class="mt-2 size-1.5 shrink-0 rounded-full bg-accent" />{{ pt }}
                                        </li>
                                    </ul>
                                </div>
                                <a v-if="company.website" :href="company.website" target="_blank" rel="noopener" class="btn-ghost mt-5 w-full !py-2.5">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9" /><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18" /></svg>
                                    {{ host(company.website) }}
                                </a>
                            </div>
                        </section>

                        <!-- Projects -->
                        <section v-else-if="active === 'projects'" key="projects">
                            <div v-if="projects.length > 1" class="mb-4 flex gap-2 overflow-x-auto pb-1">
                                <button
                                    v-for="(p, i) in projects"
                                    :key="p.title"
                                    type="button"
                                    class="shrink-0 rounded-full border px-3.5 py-1.5 text-sm font-medium transition"
                                    :class="projectIndex === i ? 'border-accent bg-accent/10 text-accent' : 'border-line text-muted hover:text-ink'"
                                    @click="projectIndex = i"
                                >
                                    {{ p.title }}
                                </button>
                            </div>
                            <div :key="projectIndex" class="grid gap-6 md:grid-cols-[1.25fr_1fr]">
                                <PhotoCarousel :photos="project.photos ?? []" :alt="project.title" />
                                <div>
                                    <h3 class="font-display text-xl font-bold text-ink">{{ project.title }}</h3>
                                    <p v-if="project.role" class="mt-0.5 text-sm font-medium text-accent">{{ project.role }}</p>
                                    <p class="mt-3 text-sm leading-relaxed text-muted">{{ project.description }}</p>
                                    <ul v-if="project.tech?.length" class="mt-4 flex flex-wrap gap-1.5">
                                        <li v-for="t in project.tech" :key="t" class="chip">{{ t }}</li>
                                    </ul>
                                    <a v-if="project.url" :href="project.url" target="_blank" rel="noopener" class="btn-accent mt-5 w-full !py-2.5">
                                        View project
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
                                    </a>
                                </div>
                            </div>
                        </section>

                        <!-- Certificates -->
                        <section v-else key="certificates">
                            <div v-if="certificates.length > 1" class="mb-4 flex gap-2 overflow-x-auto pb-1">
                                <button
                                    v-for="(c, i) in certificates"
                                    :key="c.title"
                                    type="button"
                                    class="shrink-0 rounded-full border px-3.5 py-1.5 text-sm font-medium transition"
                                    :class="certIndex === i ? 'border-accent bg-accent/10 text-accent' : 'border-line text-muted hover:text-ink'"
                                    @click="certIndex = i"
                                >
                                    {{ c.title }}
                                </button>
                            </div>
                            <div :key="certIndex" class="grid gap-6 md:grid-cols-[1.25fr_1fr]">
                                <PhotoCarousel :photos="certificate.photos ?? []" :alt="certificate.title" aspect="aspect-[4/3]" />
                                <div>
                                    <h3 class="font-display text-xl font-bold text-ink">{{ certificate.title }}</h3>
                                    <p class="mt-0.5 text-sm font-medium text-accent">{{ certificate.issuer || company.name }}</p>
                                    <p v-if="certificate.description" class="mt-3 text-sm leading-relaxed text-muted">{{ certificate.description }}</p>
                                    <dl class="mt-4 grid gap-2">
                                        <div v-if="certificate.date" class="tile flex items-center justify-between gap-3 px-3 py-2.5">
                                            <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Issued</dt>
                                            <dd class="text-sm font-semibold text-ink">{{ certificate.date }}</dd>
                                        </div>
                                        <div v-if="certificate.credential_id" class="tile flex items-center justify-between gap-3 px-3 py-2.5">
                                            <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Credential ID</dt>
                                            <dd class="truncate font-mono text-sm font-semibold text-ink">{{ certificate.credential_id }}</dd>
                                        </div>
                                    </dl>
                                    <a v-if="certificate.url" :href="certificate.url" target="_blank" rel="noopener" class="btn-accent mt-5 w-full !py-2.5">
                                        Verify certificate
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9" /></svg>
                                    </a>
                                </div>
                            </div>
                        </section>
                    </transition>
                </div>
            </article>
        </div>
    </transition>
</template>
