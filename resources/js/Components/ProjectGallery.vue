<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue';

// Screenshot gallery for the project detail page.
// Layout: 2-column grid that keeps the real screenshot shape (nothing is cropped).
// With an odd number of screenshots the first one is shown full width.
// Phone screenshots (type: 'mobile') are shown in a phone frame.
// Clicking a screenshot opens a full-screen viewer (arrows, swipe, Esc).
const props = defineProps({
    shots: { type: Array, required: true },
    title: { type: String, default: '' },
});

const desktop = computed(() => props.shots.map((s, i) => ({ ...s, i })).filter((s) => s.type === 'desktop' || !s.type));
const mobile = computed(() => props.shots.map((s, i) => ({ ...s, i })).filter((s) => s.type === 'mobile'));
const photos = computed(() => props.shots.map((s, i) => ({ ...s, i })).filter((s) => s.type === 'photo'));
// Shape of the tiles = shape of the first desktop screenshot (so rows line up)
const ratio = computed(() => desktop.value[0]?.ratio ?? 1.6);
const wide = (k) => k === 0 && desktop.value.length % 2 === 1 && desktop.value.length > 1;

/* ---------- Lightbox ---------- */
const open = ref(null);
const current = computed(() => (open.value === null ? null : props.shots[open.value]));
const go = (d) => (open.value = (open.value + d + props.shots.length) % props.shots.length);

function onKey(e) {
    if (open.value === null) return;
    if (e.key === 'Escape') open.value = null;
    if (e.key === 'ArrowRight') go(1);
    if (e.key === 'ArrowLeft') go(-1);
}
watch(open, (v) => {
    document.documentElement.style.overflow = v === null ? '' : 'hidden';
    if (v === null) window.removeEventListener('keydown', onKey);
    else window.addEventListener('keydown', onKey);
});
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    document.documentElement.style.overflow = '';
});

let touchX = null;
const onTouchStart = (e) => (touchX = e.touches[0].clientX);
function onTouchEnd(e) {
    if (touchX === null) return;
    const dx = e.changedTouches[0].clientX - touchX;
    if (Math.abs(dx) > 40) go(dx < 0 ? 1 : -1);
    touchX = null;
}
</script>

<template>
    <div>
        <!-- Desktop screenshots: bento grid -->
        <ul v-if="desktop.length" class="grid gap-4 sm:grid-cols-2">
            <li
                v-for="(s, k) in desktop"
                :key="s.src"
                v-reveal="k * 60"
                :class="wide(k) ? 'sm:col-span-2' : ''"
            >
                <button
                    type="button"
                    class="group card relative flex size-full flex-col overflow-hidden text-left"
                    :aria-label="`Open view: ${s.caption}`"
                    @click="open = s.i"
                >
                    <!-- Mini browser bar -->
                    <span class="flex items-center gap-1.5 border-b border-line bg-surface-2/70 px-3 py-2">
                        <span class="size-2 rounded-full bg-rose-400/80" />
                        <span class="size-2 rounded-full bg-amber-400/80" />
                        <span class="size-2 rounded-full bg-emerald-400/80" />
                        <span class="ml-2 truncate font-mono text-[10px] text-muted">{{ s.caption }}</span>
                    </span>
                    <span class="block overflow-hidden bg-surface-2" :style="{ aspectRatio: wide(k) ? s.ratio ?? ratio : ratio }">
                        <img :src="s.src" :alt="s.caption" loading="lazy" class="size-full object-cover object-top transition duration-700 group-hover:scale-[1.03]" />
                    </span>
                    <!-- Hover overlay -->
                    <span class="pointer-events-none absolute inset-x-0 bottom-0 flex items-end justify-between gap-2 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-4 pt-10 opacity-0 transition duration-300 group-hover:opacity-100">
                        <span class="text-sm font-semibold text-white">{{ s.caption }}</span>
                        <span class="grid size-8 shrink-0 place-items-center rounded-full bg-white/90 text-ink">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" /></svg>
                        </span>
                    </span>
                </button>
            </li>
        </ul>

        <!-- Photos (robotics, events): masonry, keeps portrait & landscape shapes -->
        <ul v-if="photos.length" class="columns-2 gap-4 sm:columns-3 [&>li]:mb-4">
            <li v-for="(s, k) in photos" :key="s.src" v-reveal="k * 50" class="break-inside-avoid">
                <button
                    type="button"
                    class="group card relative block w-full overflow-hidden text-left"
                    :aria-label="`Open photo: ${s.caption}`"
                    @click="open = s.i"
                >
                    <img :src="s.src" :alt="s.caption" loading="lazy" class="block h-auto w-full transition duration-700 group-hover:scale-[1.04]" />
                    <span class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 pt-8 text-sm font-semibold text-white opacity-0 transition duration-300 group-hover:opacity-100">{{ s.caption }}</span>
                </button>
            </li>
        </ul>

        <!-- Phone screenshots: row of phone frames -->
        <ul v-if="mobile.length" class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4" :class="desktop.length ? 'mt-6' : ''">
            <li v-for="(s, k) in mobile" :key="s.src" v-reveal="k * 60">
                <button type="button" class="group mx-auto block w-full max-w-[13rem] text-center" @click="open = s.i">
                    <span class="block overflow-hidden rounded-[1.75rem] border-[6px] border-ink/90 bg-ink shadow-xl transition duration-300 group-hover:-translate-y-1">
                        <img :src="s.src" :alt="s.caption" loading="lazy" class="aspect-[9/19] w-full object-cover object-top" />
                    </span>
                    <span class="mt-2 block text-xs font-medium text-muted group-hover:text-accent">{{ s.caption }}</span>
                </button>
            </li>
        </ul>

        <!-- Lightbox -->
        <Teleport to="body">
            <transition enter-from-class="opacity-0" leave-to-class="opacity-0" enter-active-class="transition duration-200" leave-active-class="transition duration-150">
                <div
                    v-if="current"
                    class="fixed inset-0 z-[90] flex flex-col bg-black/90 backdrop-blur-sm"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="`${title} gallery`"
                    @click.self="open = null"
                    @touchstart.passive="onTouchStart"
                    @touchend="onTouchEnd"
                >
                    <header class="flex items-center justify-between gap-4 px-4 py-3 text-white sm:px-6">
                        <p class="min-w-0 truncate text-sm"><span class="font-mono text-white/60">{{ open + 1 }} / {{ shots.length }}</span> · {{ current.caption }}</p>
                        <button type="button" class="grid size-10 place-items-center rounded-full bg-white/10 hover:bg-white/20" aria-label="Close" @click="open = null">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12" /></svg>
                        </button>
                    </header>

                    <div class="relative flex min-h-0 flex-1 items-center justify-center px-4 sm:px-16" @click.self="open = null">
                        <transition mode="out-in" enter-from-class="opacity-0 scale-[0.98]" leave-to-class="opacity-0" enter-active-class="transition duration-200" leave-active-class="transition duration-100">
                            <img :key="current.src" :src="current.src" :alt="current.caption" class="max-h-full max-w-full rounded-lg object-contain shadow-2xl" />
                        </transition>
                        <button v-if="shots.length > 1" type="button" class="absolute left-2 grid size-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/25 sm:left-4" aria-label="Previous" @click="go(-1)">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                        </button>
                        <button v-if="shots.length > 1" type="button" class="absolute right-2 grid size-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/25 sm:right-4" aria-label="Next" @click="go(1)">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6" /></svg>
                        </button>
                    </div>

                    <!-- Thumbnails -->
                    <div v-if="shots.length > 1" class="flex justify-center gap-2 overflow-x-auto px-4 py-3">
                        <button
                            v-for="(s, i) in shots"
                            :key="s.src"
                            type="button"
                            class="h-12 shrink-0 overflow-hidden rounded-md border-2 transition sm:h-14"
                            :class="i === open ? 'border-white opacity-100' : 'border-transparent opacity-50 hover:opacity-90'"
                            :aria-label="s.caption"
                            @click="open = i"
                        >
                            <img :src="s.src" alt="" class="h-full w-auto object-cover" />
                        </button>
                    </div>
                </div>
            </transition>
        </Teleport>
    </div>
</template>
