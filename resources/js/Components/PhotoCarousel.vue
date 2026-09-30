<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';

// Swipeable photo carousel: touch swipe, mouse drag, arrows, dots, thumbnails, keyboard.
// photos: ['/images/a.jpg', ...] or [{ src: '/images/a.jpg', caption: 'Text' }, ...]
const props = defineProps({
    photos: { type: Array, default: () => [] },
    alt: { type: String, default: '' },
    aspect: { type: String, default: 'aspect-video' },
});

const slides = computed(() => props.photos.map((p) => (typeof p === 'string' ? { src: p, caption: '' } : p)).filter((p) => p?.src));
const track = ref(null);
const index = ref(0);
const dragging = ref(false);

function goTo(i, smooth = true) {
    if (!track.value || !slides.value.length) return;
    const n = slides.value.length;
    index.value = ((i % n) + n) % n;
    track.value.scrollTo({ left: index.value * track.value.clientWidth, behavior: smooth ? 'smooth' : 'auto' });
}

let scrollTimer;
function onScroll() {
    if (dragging.value) return;
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(() => {
        if (track.value) index.value = Math.round(track.value.scrollLeft / track.value.clientWidth);
    }, 60);
}

// Mouse drag (touch devices already swipe natively)
let startX = 0;
let startLeft = 0;
let moved = false;
function onPointerDown(e) {
    if (e.pointerType !== 'mouse' || slides.value.length < 2) return;
    dragging.value = true;
    moved = false;
    startX = e.clientX;
    startLeft = track.value.scrollLeft;
    track.value.setPointerCapture(e.pointerId);
}
function onPointerMove(e) {
    if (!dragging.value) return;
    const dx = e.clientX - startX;
    if (Math.abs(dx) > 4) moved = true;
    track.value.scrollLeft = startLeft - dx;
}
function onPointerUp(e) {
    if (!dragging.value) return;
    dragging.value = false;
    const dx = e.clientX - startX;
    const threshold = track.value.clientWidth * 0.15;
    const base = Math.round(startLeft / track.value.clientWidth);
    goTo(dx < -threshold ? base + 1 : dx > threshold ? base - 1 : base);
}
function onClickCapture(e) {
    if (moved) {
        e.preventDefault();
        e.stopPropagation();
        moved = false;
    }
}

function onKey(e) {
    if (e.key === 'ArrowRight') {
        e.preventDefault();
        goTo(index.value + 1);
    }
    if (e.key === 'ArrowLeft') {
        e.preventDefault();
        goTo(index.value - 1);
    }
}

// Reset when the photo set changes (e.g. switching tabs)
watch(
    () => props.photos,
    async () => {
        index.value = 0;
        await nextTick();
        goTo(0, false);
    },
);

// Keep the current slide aligned when the container resizes
let ro;
onMounted(() => {
    ro = new ResizeObserver(() => goTo(index.value, false));
    if (track.value) ro.observe(track.value);
});
onBeforeUnmount(() => {
    ro?.disconnect();
    clearTimeout(scrollTimer);
});
</script>

<template>
    <div class="select-none">
        <div class="relative overflow-hidden rounded-xl border border-line bg-surface-2" :class="aspect">
            <!-- Empty state -->
            <div v-if="!slides.length" class="absolute inset-0 grid place-items-center">
                <div class="bg-grid absolute inset-0 opacity-60" />
                <div class="relative text-center text-muted">
                    <svg class="mx-auto size-10 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2" /><circle cx="9" cy="10" r="2" /><path d="m21 16-5-5-9 8" /></svg>
                    <p class="mt-2 font-mono text-[11px]">No photos yet</p>
                </div>
            </div>

            <!-- Track -->
            <div
                v-else
                ref="track"
                class="carousel-track absolute inset-0 flex overflow-x-auto overscroll-x-contain"
                :class="dragging ? 'cursor-grabbing' : slides.length > 1 ? 'snap-x snap-mandatory cursor-grab' : ''"
                tabindex="0"
                role="region"
                :aria-label="`${alt} photos`"
                @scroll.passive="onScroll"
                @pointerdown="onPointerDown"
                @pointermove="onPointerMove"
                @pointerup="onPointerUp"
                @pointercancel="onPointerUp"
                @click.capture="onClickCapture"
                @keydown="onKey"
            >
                <figure v-for="(s, i) in slides" :key="s.src + i" class="relative size-full shrink-0 snap-center">
                    <img :src="s.src" :alt="s.caption || `${alt} ${i + 1}`" class="pointer-events-none size-full object-cover" draggable="false" :loading="i === 0 ? 'eager' : 'lazy'" />
                    <figcaption v-if="s.caption" class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 to-transparent p-4 pt-10 text-sm text-white">
                        {{ s.caption }}
                    </figcaption>
                </figure>
            </div>

            <template v-if="slides.length > 1">
                <button
                    type="button"
                    class="absolute top-1/2 left-3 grid size-9 -translate-y-1/2 place-items-center rounded-full bg-bg/80 text-ink shadow-md backdrop-blur transition hover:scale-110 hover:text-accent"
                    aria-label="Previous photo"
                    @click="goTo(index - 1)"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6" /></svg>
                </button>
                <button
                    type="button"
                    class="absolute top-1/2 right-3 grid size-9 -translate-y-1/2 place-items-center rounded-full bg-bg/80 text-ink shadow-md backdrop-blur transition hover:scale-110 hover:text-accent"
                    aria-label="Next photo"
                    @click="goTo(index + 1)"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6" /></svg>
                </button>
                <span class="absolute top-3 right-3 rounded-md bg-bg/80 px-2 py-0.5 font-mono text-[11px] text-ink backdrop-blur">{{ index + 1 }} / {{ slides.length }}</span>
                <div class="absolute inset-x-0 bottom-3 flex justify-center gap-1.5">
                    <button
                        v-for="(s, i) in slides"
                        :key="i"
                        type="button"
                        class="h-1.5 rounded-full transition-all"
                        :class="i === index ? 'w-6 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/80'"
                        :aria-label="`Photo ${i + 1}`"
                        @click="goTo(i)"
                    />
                </div>
            </template>
        </div>

        <!-- Thumbnails -->
        <div v-if="slides.length > 1" class="mt-2 flex gap-2 overflow-x-auto pb-1">
            <button
                v-for="(s, i) in slides"
                :key="'t' + i"
                type="button"
                class="h-12 w-16 shrink-0 overflow-hidden rounded-lg border-2 transition"
                :class="i === index ? 'border-accent' : 'border-transparent opacity-60 hover:opacity-100'"
                :aria-label="`Show photo ${i + 1}`"
                @click="goTo(i)"
            >
                <img :src="s.src" alt="" class="size-full object-cover" draggable="false" loading="lazy" />
            </button>
        </div>
    </div>
</template>
