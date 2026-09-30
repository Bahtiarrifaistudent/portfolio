<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useCvViewer } from '../composables/useCvViewer';
import { useCvDownload } from '../composables/useCvDownload';

const props = defineProps({
    profile: { type: Object, required: true },
});

const { file, close } = useCvViewer();
const { start: startDownload } = useCvDownload();
const loaded = ref(false);

let fallback;
watch(file, (f) => {
    loaded.value = false;
    document.documentElement.style.overflow = f ? 'hidden' : '';
    // Some browsers never fire "load" for PDFs; never keep the spinner forever
    clearTimeout(fallback);
    if (f) fallback = setTimeout(() => (loaded.value = true), 2500);
});

function download() {
    const url = file.value;
    close();
    startDownload(url);
}

function onKey(e) {
    if (e.key === 'Escape' && file.value) close();
}
onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => {
    clearTimeout(fallback);
    window.removeEventListener('keydown', onKey);
    document.documentElement.style.overflow = '';
});
</script>

<template>
    <transition
        enter-from-class="opacity-0"
        enter-active-class="transition duration-300"
        leave-to-class="opacity-0"
        leave-active-class="transition duration-200"
    >
        <div
            v-if="file"
            class="fixed inset-0 z-[85] flex items-center justify-center bg-bg/75 p-4 backdrop-blur-md sm:p-8"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cv-viewer-title"
            @click.self="close"
        >
            <div class="cv-viewer-in card card-static flex h-full max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden shadow-2xl">
                <!-- Toolbar -->
                <header class="flex items-center gap-3 border-b border-line bg-surface-2 px-4 py-3">
                    <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-accent/15 text-accent">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" /><path d="M14 3v6h6M8 13h8M8 17h5" /></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p id="cv-viewer-title" class="truncate text-sm font-semibold text-ink">Curriculum Vitae · {{ profile.name }}</p>
                        <p class="truncate font-mono text-[11px] text-muted">{{ file.split('/').pop() }}</p>
                    </div>
                    <a :href="file" target="_blank" rel="noopener" class="btn-ghost hidden !px-3 !py-2 sm:inline-flex" title="Open in new tab">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5" /></svg>
                        <span class="hidden md:inline">New tab</span>
                    </a>
                    <button type="button" class="btn-primary !px-3 !py-2" @click="download">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                        <span class="hidden sm:inline">Download</span>
                    </button>
                    <button type="button" class="grid size-9 shrink-0 place-items-center rounded-lg text-muted transition hover:bg-surface hover:text-ink" aria-label="Close" @click="close">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 6l12 12M18 6L6 18" /></svg>
                    </button>
                </header>

                <!-- Document -->
                <div class="relative flex-1 bg-surface-2">
                    <div v-if="!loaded" class="absolute inset-0 grid place-items-center">
                        <div class="flex flex-col items-center gap-3 text-sm text-muted">
                            <span class="size-8 animate-spin rounded-full border-2 border-line border-t-accent" />
                            Loading CV...
                        </div>
                    </div>
                    <iframe
                        :src="`${file}#view=FitH`"
                        title="Curriculum Vitae"
                        class="size-full border-0 transition-opacity duration-300"
                        :class="loaded ? 'opacity-100' : 'opacity-0'"
                        @load="loaded = true"
                    />
                </div>
            </div>
        </div>
    </transition>
</template>
