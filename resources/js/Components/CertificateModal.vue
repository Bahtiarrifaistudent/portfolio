<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

// Certificate detail popup.
// Optional fields per certificate in config/portfolio.php:
//   image, file (PDF), credential_id, expires, description, skills[]
const props = defineProps({
    certificates: { type: Array, required: true },
    // index of the open certificate, null = closed
    modelValue: { type: Number, default: null },
});
const emit = defineEmits(['update:modelValue']);

const cert = computed(() => (props.modelValue === null ? null : props.certificates[props.modelValue]));
const total = computed(() => props.certificates.length);
const zoomed = ref(false);
const copied = ref(false);
const direction = ref('next');

function close() {
    emit('update:modelValue', null);
}
function go(step) {
    if (total.value < 2) return;
    direction.value = step > 0 ? 'next' : 'prev';
    emit('update:modelValue', (props.modelValue + step + total.value) % total.value);
}

async function copyId() {
    try {
        await navigator.clipboard.writeText(cert.value.credential_id);
        copied.value = true;
        setTimeout(() => (copied.value = false), 1500);
    } catch (e) {
        // clipboard unavailable
    }
}

watch(
    () => props.modelValue,
    (v) => {
        zoomed.value = false;
        document.documentElement.style.overflow = v === null ? '' : 'hidden';
    },
);

function onKey(e) {
    if (props.modelValue === null) return;
    if (e.key === 'Escape') zoomed.value ? (zoomed.value = false) : close();
    if (e.key === 'ArrowRight') go(1);
    if (e.key === 'ArrowLeft') go(-1);
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
            v-if="cert"
            class="fixed inset-0 z-[85] flex items-center justify-center bg-bg/80 p-3 backdrop-blur-md sm:p-6"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cert-title"
            @click.self="close"
        >
            <!-- Prev / next (desktop) -->
            <button
                v-if="total > 1"
                type="button"
                class="absolute left-4 hidden size-12 place-items-center rounded-full border border-line bg-surface text-ink shadow-lg transition hover:scale-110 hover:border-accent hover:text-accent lg:grid"
                aria-label="Previous certificate"
                @click="go(-1)"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
            </button>
            <button
                v-if="total > 1"
                type="button"
                class="absolute right-4 hidden size-12 place-items-center rounded-full border border-line bg-surface text-ink shadow-lg transition hover:scale-110 hover:border-accent hover:text-accent lg:grid"
                aria-label="Next certificate"
                @click="go(1)"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6" /></svg>
            </button>

            <transition mode="out-in" :enter-from-class="direction === 'next' ? 'opacity-0 translate-x-6' : 'opacity-0 -translate-x-6'" enter-active-class="transition duration-300 ease-out" leave-to-class="opacity-0" leave-active-class="transition duration-150">
                <article
                    :key="modelValue"
                    class="cert-in card card-static grid max-h-[92vh] w-full max-w-5xl grid-rows-[auto_minmax(0,1fr)] overflow-hidden shadow-2xl lg:grid-cols-[1.35fr_1fr] lg:grid-rows-1"
                >
                    <!-- Visual -->
                    <div class="relative min-h-56 overflow-hidden border-b border-line bg-surface-2 lg:border-r lg:border-b-0">
                        <template v-if="cert.image">
                            <div class="h-full max-h-[34vh] overflow-auto lg:max-h-[92vh]" :class="zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'" @click="zoomed = !zoomed">
                                <img
                                    :src="cert.image"
                                    :alt="cert.title"
                                    class="transition-all duration-300"
                                    :class="zoomed ? 'max-w-none w-[180%]' : 'mx-auto size-full object-contain p-4 sm:p-6'"
                                />
                            </div>
                            <span class="pointer-events-none absolute bottom-3 left-3 rounded-md bg-bg/80 px-2 py-1 font-mono text-[10px] text-muted backdrop-blur">
                                {{ zoomed ? 'Click to fit' : 'Click to zoom' }}
                            </span>
                        </template>
                        <div v-else class="relative grid h-full min-h-56 place-items-center p-8">
                            <div class="bg-grid absolute inset-0 opacity-60" />
                            <div class="relative text-center">
                                <svg class="mx-auto size-20 text-accent/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="9" r="6" /><path d="M8.5 14 7 22l5-3 5 3-1.5-8" /></svg>
                                <p class="mt-3 font-mono text-xs text-muted">No certificate image yet</p>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex min-h-0 flex-col overflow-y-auto">
                        <header class="flex items-center justify-between gap-3 border-b border-line px-5 py-3">
                            <span class="font-mono text-[11px] text-muted">Certificate {{ modelValue + 1 }} / {{ total }}</span>
                            <button type="button" class="grid size-8 place-items-center rounded-lg text-muted transition hover:bg-surface-2 hover:text-ink" aria-label="Close" @click="close">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 6l12 12M18 6L6 18" /></svg>
                            </button>
                        </header>

                        <div class="flex-1 p-5 sm:p-6">
                            <div class="flex flex-wrap items-center gap-2">
                                <span v-if="cert.category" class="rounded-md bg-accent/10 px-2 py-0.5 font-mono text-[11px] text-accent">{{ cert.category }}</span>
                                <span v-if="cert.example" class="rounded-md border border-dashed border-amber-500/60 px-2 py-0.5 font-mono text-[11px] text-amber-500">Example</span>
                            </div>
                            <h2 id="cert-title" class="mt-3 font-display text-2xl leading-tight font-bold text-ink">{{ cert.title }}</h2>
                            <p class="mt-1 font-medium text-accent">{{ cert.issuer }}</p>

                            <p v-if="cert.description" class="mt-4 text-sm leading-relaxed text-muted">{{ cert.description }}</p>

                            <dl class="mt-5 grid grid-cols-2 gap-3">
                                <div class="tile p-3">
                                    <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Issued</dt>
                                    <dd class="mt-0.5 text-sm font-semibold text-ink">{{ cert.date || '-' }}</dd>
                                </div>
                                <div class="tile p-3">
                                    <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Expires</dt>
                                    <dd class="mt-0.5 text-sm font-semibold text-ink">{{ cert.expires || 'No expiration' }}</dd>
                                </div>
                                <div v-if="cert.credential_id" class="tile col-span-2 flex items-center justify-between gap-2 p-3">
                                    <div class="min-w-0">
                                        <dt class="font-mono text-[10px] tracking-wider text-muted uppercase">Credential ID</dt>
                                        <dd class="mt-0.5 truncate font-mono text-sm font-semibold text-ink">{{ cert.credential_id }}</dd>
                                    </div>
                                    <button type="button" class="shrink-0 rounded-lg border border-line px-2.5 py-1 text-xs font-semibold text-muted transition hover:border-accent hover:text-accent" @click="copyId">
                                        {{ copied ? 'Copied!' : 'Copy' }}
                                    </button>
                                </div>
                            </dl>

                            <div v-if="cert.skills?.length" class="mt-5">
                                <p class="font-mono text-[10px] tracking-wider text-muted uppercase">Skills covered</p>
                                <ul class="mt-2 flex flex-wrap gap-1.5">
                                    <li v-for="s in cert.skills" :key="s" class="chip">{{ s }}</li>
                                </ul>
                            </div>
                        </div>

                        <footer class="grid gap-2 border-t border-line p-5">
                            <a v-if="cert.file" :href="cert.file" target="_blank" rel="noopener" class="btn-accent !py-2.5">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" /><path d="M14 3v6h6" /></svg>
                                Open PDF
                            </a>
                            <a v-else-if="cert.image" :href="cert.image" target="_blank" rel="noopener" class="btn-accent !py-2.5">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5" /></svg>
                                Full image
                            </a>
                            <!-- Prev / next on smaller screens -->
                            <div v-if="total > 1" class="col-span-full flex gap-2 lg:hidden">
                                <button type="button" class="btn-ghost flex-1 !py-2" @click="go(-1)">&larr; Prev</button>
                                <button type="button" class="btn-ghost flex-1 !py-2" @click="go(1)">Next &rarr;</button>
                            </div>
                        </footer>
                    </div>
                </article>
            </transition>
        </div>
    </transition>
</template>
