<script setup>
import { computed } from 'vue';

// Project cover.
// - With a screenshot: a "showcase" (gradient stage by category with decorations, the app in a
//   tilted window, a second window, floating tech chips and a small info card).
// - Photo project (robotics/community): the photo itself, tinted, with camera viewfinder marks,
//   a "REC" label and polaroids of the next photos.
// - With a phone (portrait) screenshot: phone frames instead of a window.
// - Without one: a cover generated from the category, title, and tags.
const props = defineProps({
    project: { type: Object, required: true },
    // true = large cover (detail page / featured)
    large: { type: Boolean, default: false },
    // true = windows centered vertically (detail page); false = anchored near the top (cards)
    center: { type: Boolean, default: false },
});

const gradients = {
    web: 'from-[#ff2d20] via-[#c026d3] to-[#7c3aed]',
    security: 'from-[#0891b2] via-[#1e3a8a] to-[#312e81]',
    ai: 'from-[#7c3aed] via-[#c026d3] to-[#f472b6]',
    mobile: 'from-[#059669] via-[#0891b2] to-[#7c3aed]',
    robotics: 'from-[#d97706] via-[#dc2626] to-[#7c3aed]',
    community: 'from-[#e11d48] via-[#c026d3] to-[#f59e0b]',
};
const gradient = computed(() => gradients[props.project.category] ?? gradients.web);
const shots = computed(() => props.project.screenshots ?? []);

// Second screenshot (the first one is the cover itself)
// 'cover_second' in config/portfolio.php chooses it by keyword (e.g. 'remote'); otherwise the next screenshot
const second = computed(
    () => props.project.cover_second_src ?? shots.value.find((x) => x.src !== props.project.image && x.type !== 'mobile')?.src ?? null,
);
// Third screenshot: stacked behind the second one on large covers
const third = computed(() => shots.value.find((x) => x.type !== 'mobile' && x.type !== 'photo' && x.src !== props.project.image && x.src !== second.value)?.src ?? null);

// Photo projects: next photos (cover_second keyword or the next photo) shown as polaroids
const photoSecond = computed(
    () =>
        (props.project.cover_second_src !== props.project.image ? props.project.cover_second_src : null) ??
        shots.value.find((x) => x.src !== props.project.image)?.src ??
        null,
);
const photoThird = computed(() => shots.value.find((x) => x.src !== props.project.image && x.src !== photoSecond.value)?.src ?? null);

// Phone app: the cover image is a portrait (phone) screenshot -> phone frames instead of a window
const isMobile = computed(() => {
    const cover = shots.value.find((x) => x.src === props.project.image);
    return cover ? cover.type === 'mobile' : false;
});
// Up to 2 more phone screenshots next to the main one (large covers show 2, cards show 1)
const phones = computed(() => {
    const rest = shots.value.filter((x) => x.type === 'mobile' && x.src !== props.project.image);
    const pick = props.project.cover_second_src ? [rest.find((x) => x.src === props.project.cover_second_src), ...rest] : rest;
    return [...new Set(pick.filter(Boolean).map((x) => x.src))].slice(0, props.large ? 2 : 1);
});

// Short tags as floating chips (cards 2, large covers 3)
const chips = computed(() =>
    (props.project.tags ?? [])
        .map((t) => t.replace(/\s*\(.*\)$/, ''))
        .filter((t) => t.length <= 16)
        .slice(0, props.large ? 3 : 2),
);
const countLabel = computed(() => {
    const n = shots.value.length;
    if (!n) return null;
    if (props.project.media === 'photo') return `${n} photo${n > 1 ? 's' : ''}`;
    return `${n} screen${n > 1 ? 's' : ''}`;
});

// Initials from the title (text before ":" only). One-word titles use the first 2 letters.
// Override per project with 'initials' => 'XX' in config/portfolio.php.
const initials = computed(() => {
    if (props.project.initials) return props.project.initials;
    const words = props.project.title.split(':')[0].split(/\s+/).filter((w) => /^[A-Za-z]/.test(w));
    if (words.length === 1) return words[0].slice(0, 2).toUpperCase();
    return words.slice(0, 2).map((w) => w[0].toUpperCase()).join('');
});

const dots = {
    backgroundImage: 'radial-gradient(rgba(255,255,255,0.7) 1px, transparent 1.6px)',
    backgroundSize: '11px 11px',
    maskImage: 'radial-gradient(closest-side, black, transparent)',
    WebkitMaskImage: 'radial-gradient(closest-side, black, transparent)',
};
const star = 'M12 0c.7 6.4 5.6 11.3 12 12-6.4.7-11.3 5.6-12 12-.7-6.4-5.6-11.3-12-12C6.4 11.3 11.3 6.4 12 0z';
</script>

<template>
    <div class="relative size-full overflow-hidden">
        <!-- ===================== Photo project (robotics, community) ===================== -->
        <div v-if="project.image && project.media === 'photo'" class="absolute inset-0 overflow-hidden bg-neutral-900">
            <img
                :src="project.image"
                :alt="project.title"
                loading="lazy"
                class="absolute inset-0 size-full object-cover transition duration-700 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-br opacity-55 mix-blend-multiply" :class="gradient" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/5 to-transparent" />
            <div class="pointer-events-none absolute -top-1/4 -left-1/4 size-[60%] rounded-full bg-white/15 blur-3xl" />

            <!-- Camera viewfinder -->
            <div class="pointer-events-none absolute inset-[5%] text-white/75">
                <span class="absolute top-0 left-0 size-[9%] min-h-4 min-w-4 rounded-tl-md border-t-2 border-l-2 border-current" :class="large ? '' : 'opacity-0'" />
                <span class="absolute top-0 right-0 size-[9%] min-h-4 min-w-4 rounded-tr-md border-t-2 border-r-2 border-current" :class="large ? '' : 'opacity-0'" />
                <span class="absolute bottom-0 left-0 size-[9%] min-h-4 min-w-4 rounded-bl-md border-b-2 border-l-2 border-current" />
                <span class="absolute right-0 bottom-0 size-[9%] min-h-4 min-w-4 rounded-br-md border-r-2 border-b-2 border-current" />
                <!-- focus mark -->
                <span class="absolute top-1/2 left-1/2 size-[7%] min-h-5 min-w-5 -translate-1/2 rounded-full border border-current opacity-60 transition duration-700 group-hover:scale-75" />
                <span class="absolute top-1/2 left-1/2 h-px w-[3%] min-w-2 -translate-1/2 bg-current opacity-60" />
                <span class="absolute top-1/2 left-1/2 h-[4%] min-h-2 w-px -translate-1/2 bg-current opacity-60" />
            </div>

            <!-- REC label -->
            <div class="absolute top-[10%] left-[10%] flex items-center gap-1.5 rounded-full bg-black/35 px-2.5 py-1 font-mono font-semibold tracking-wider text-white backdrop-blur" :class="large ? 'text-xs' : 'text-[10px]'">
                <span class="cover-pulse size-1.5 rounded-full bg-red-500" :class="large ? 'size-2' : ''" />
                REC<span v-if="project.year" class="font-normal text-white/70">· {{ project.year }}</span>
            </div>

            <!-- Floating chips (large covers) -->
            <div v-if="large" class="absolute top-[19%] left-[10%] hidden flex-col items-start gap-1.5 sm:flex">
                <span v-for="(t, i) in chips" :key="t" class="cover-float rounded-full border border-white/25 bg-white/15 px-2.5 py-1 text-[11px] font-medium text-white backdrop-blur" :style="{ animationDelay: `${i * 0.8}s` }">
                    {{ t }}
                </span>
            </div>

            <!-- Polaroids of the next photos -->
            <div
                v-if="large && photoThird"
                class="absolute right-[18%] bottom-[16%] w-[22%] -rotate-6 rounded-md bg-white p-1.5 pb-5 shadow-2xl shadow-black/50 transition duration-700 group-hover:-rotate-9 sm:p-2 sm:pb-7"
            >
                <img :src="photoThird" alt="" loading="lazy" class="aspect-[4/3] w-full rounded-sm object-cover" />
            </div>
            <div
                v-if="photoSecond"
                class="absolute rotate-3 rounded-md bg-white shadow-2xl shadow-black/50 transition duration-700 group-hover:-translate-y-1 group-hover:rotate-6"
                :class="large ? 'right-[5%] bottom-[9%] w-[26%] p-1.5 pb-5 sm:p-2 sm:pb-7' : 'top-[24%] right-[7%] w-[30%] p-1 pb-3.5'"
            >
                <img :src="photoSecond" alt="" loading="lazy" class="aspect-[4/3] w-full rounded-sm object-cover" />
                <span v-if="large && countLabel" class="absolute inset-x-0 bottom-0.5 text-center font-mono text-[9px] text-neutral-500 sm:bottom-1.5 sm:text-[10px]">{{ countLabel }}</span>
            </div>
        </div>

        <!-- ===================== Gradient stage (screenshots, phones, generated) ===================== -->
        <div v-else class="absolute inset-0 overflow-hidden bg-gradient-to-br" :class="gradient">
            <!-- Decorations -->
            <div class="bg-grid absolute inset-0 opacity-30 mix-blend-overlay" />
            <div class="pointer-events-none absolute -top-1/3 left-1/4 size-[70%] rounded-full bg-white/25 blur-3xl" />
            <div class="pointer-events-none absolute -right-[10%] -bottom-1/3 size-[55%] rounded-full bg-black/25 blur-3xl" />
            <div class="pointer-events-none absolute -bottom-1/4 -left-[10%] size-[45%] rounded-full bg-cyan-300/20 blur-3xl" />
            <div class="pointer-events-none absolute top-[3%] right-[3%] h-[34%] w-[26%] opacity-50" :style="dots" />
            <div class="pointer-events-none absolute bottom-[4%] left-[2%] h-[26%] w-[18%] opacity-35" :style="dots" />
            <svg class="pointer-events-none absolute -right-[18%] -bottom-[45%] w-[85%] text-white/15 transition duration-1000 group-hover:rotate-12" viewBox="0 0 200 200" fill="none" stroke="currentColor">
                <circle cx="100" cy="100" r="98" stroke-width="0.6" />
                <circle cx="100" cy="100" r="74" stroke-width="0.6" stroke-dasharray="3 5" />
                <circle cx="100" cy="100" r="50" stroke-width="0.6" />
                <circle cx="174" cy="100" r="3" fill="currentColor" />
                <circle cx="100" cy="26" r="2" fill="currentColor" />
            </svg>
            <span
                class="pointer-events-none absolute -right-[2%] -bottom-[10%] font-display leading-none font-black tracking-tighter text-white/10 select-none"
                :class="large ? 'text-[9rem] sm:text-[13rem]' : 'text-[7rem]'"
            >
                {{ initials }}
            </span>
            <!-- Sparkles -->
            <svg class="cover-twinkle pointer-events-none absolute top-[7%] right-[22%] size-3 text-white/80 sm:size-4" viewBox="0 0 24 24" fill="currentColor"><path :d="star" /></svg>
            <svg class="cover-twinkle pointer-events-none absolute top-[44%] left-[3%] size-2.5 text-white/70" style="animation-delay: 1.2s" viewBox="0 0 24 24" fill="currentColor"><path :d="star" /></svg>
            <svg class="cover-twinkle pointer-events-none absolute right-[4%] bottom-[30%] size-2 text-white/60" style="animation-delay: 2.1s" viewBox="0 0 24 24" fill="currentColor"><path :d="star" /></svg>

            <!-- ---------- Phone app: phone frames ---------- -->
            <template v-if="project.image && isMobile">
                <div class="absolute inset-x-0 flex items-start justify-center" :class="[center ? 'top-[9%]' : 'top-[11%]', large ? 'gap-[3%]' : 'gap-[4%]']">
                    <div
                        v-if="phones[0]"
                        class="order-1 mt-[6%] aspect-[9/19] -rotate-6 overflow-hidden rounded-[1.4rem] border-[5px] border-neutral-900 bg-neutral-900 opacity-95 shadow-2xl shadow-black/40 transition duration-700 group-hover:-translate-y-1 group-hover:-rotate-3"
                        :class="large ? 'w-[17%]' : 'w-[24%]'"
                    >
                        <img :src="phones[0]" alt="" loading="lazy" class="size-full rounded-[1rem] object-cover object-top" />
                    </div>
                    <div
                        class="relative order-2 aspect-[9/19] overflow-hidden rounded-[1.6rem] border-[6px] border-neutral-900 bg-neutral-900 shadow-2xl shadow-black/50 ring-4 ring-white/15 transition duration-700 group-hover:-translate-y-2"
                        :class="large ? 'w-[21%]' : 'w-[30%]'"
                    >
                        <span class="absolute top-1.5 left-1/2 z-10 h-1.5 w-1/4 -translate-x-1/2 rounded-full bg-neutral-900" />
                        <img :src="project.image" :alt="project.title" loading="lazy" class="size-full rounded-[1.1rem] object-cover object-top" />
                    </div>
                    <div
                        v-if="phones[1]"
                        class="order-3 mt-[6%] aspect-[9/19] w-[17%] rotate-6 overflow-hidden rounded-[1.4rem] border-[5px] border-neutral-900 bg-neutral-900 opacity-95 shadow-2xl shadow-black/40 transition duration-700 group-hover:-translate-y-1 group-hover:rotate-3"
                    >
                        <img :src="phones[1]" alt="" loading="lazy" class="size-full rounded-[1rem] object-cover object-top" />
                    </div>
                </div>
                <!-- Notification bubble -->
                <div
                    class="cover-float absolute flex items-center gap-1.5 rounded-xl border border-white/30 bg-white/90 text-neutral-800 shadow-xl shadow-black/20"
                    :class="large ? 'bottom-[12%] left-[6%] px-3 py-2 text-xs' : 'bottom-[38%] left-[4%] px-2 py-1 text-[10px]'"
                >
                    <span class="grid place-items-center rounded-md bg-emerald-500 text-white" :class="large ? 'size-5' : 'size-3.5'">
                        <svg class="size-[70%]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="m5 12 5 5L20 7" /></svg>
                    </span>
                    <span class="font-semibold">{{ countLabel ?? 'Mobile app' }}</span>
                </div>
            </template>

            <!-- ---------- Screenshot: app windows ---------- -->
            <template v-else-if="project.image">
                <!-- Third screenshot, stacked behind the second one (large covers) -->
                <div
                    v-if="large && third"
                    class="absolute right-[2%] w-[28%] overflow-hidden rounded-lg border border-white/25 bg-white/10 opacity-60 shadow-xl shadow-black/30 transition duration-700 group-hover:-translate-y-2"
                    :class="center ? 'top-[16%]' : 'top-[10%]'"
                >
                    <div class="h-3 border-b border-white/20 bg-white/15" />
                    <img :src="third" alt="" loading="lazy" class="block w-full" />
                </div>

                <!-- Second screenshot, floating on the right -->
                <div
                    v-if="large && second"
                    class="absolute right-[5%] z-10 w-[34%] overflow-hidden rounded-lg border border-white/30 bg-white/10 opacity-95 shadow-2xl shadow-black/40 transition duration-700 group-hover:-translate-y-1 group-hover:rotate-1"
                    :class="center ? 'top-[30%]' : 'top-[24%]'"
                >
                    <div class="flex items-center gap-1 border-b border-white/20 bg-white/15 px-2 py-1.5 backdrop-blur">
                        <span class="size-1.5 rounded-full bg-white/70" />
                        <span class="size-1.5 rounded-full bg-white/50" />
                        <span class="size-1.5 rounded-full bg-white/30" />
                    </div>
                    <img :src="second" alt="" loading="lazy" class="block w-full" />
                </div>

                <!-- Main window (slight 3D tilt, straightens on hover) -->
                <div
                    class="absolute overflow-hidden rounded-xl border border-white/30 bg-white/10 shadow-2xl shadow-black/40 transition duration-700 [transform:perspective(1400px)_rotateY(-7deg)_rotateX(3deg)] group-hover:-translate-y-1.5 group-hover:[transform:perspective(1400px)_rotateY(0deg)_rotateX(0deg)]"
                    :class="[large && second ? 'left-[6%] w-[66%]' : large ? 'left-[10%] w-[80%]' : 'left-[9%] w-[82%]', center ? 'top-[18%]' : large ? 'top-[12%]' : 'top-[13%]']"
                >
                    <div class="flex items-center gap-1.5 border-b border-white/20 bg-white/15 backdrop-blur" :class="large ? 'px-3 py-2' : 'px-2.5 py-1.5'">
                        <span class="rounded-full bg-white/80" :class="large ? 'size-2.5' : 'size-1.5'" />
                        <span class="rounded-full bg-white/55" :class="large ? 'size-2.5' : 'size-1.5'" />
                        <span class="rounded-full bg-white/35" :class="large ? 'size-2.5' : 'size-1.5'" />
                        <span v-if="large" class="mx-auto hidden truncate rounded-md bg-white/15 px-3 py-0.5 font-mono text-[10px] text-white/85 sm:block">{{ project.slug }}.app</span>
                    </div>
                    <img :src="project.image" :alt="project.title" loading="lazy" class="block w-full" />
                </div>

                <!-- Small second window on cards (bottom right, in front) -->
                <div
                    v-if="!large && second"
                    class="absolute top-[42%] right-[4%] z-10 w-[36%] rotate-2 overflow-hidden rounded-md border border-white/40 bg-white/10 shadow-2xl shadow-black/50 transition duration-700 group-hover:-translate-y-1 group-hover:rotate-0"
                >
                    <div class="flex items-center gap-0.5 border-b border-white/20 bg-white/20 px-1.5 py-1 backdrop-blur">
                        <span class="size-1 rounded-full bg-white/80" />
                        <span class="size-1 rounded-full bg-white/55" />
                        <span class="size-1 rounded-full bg-white/35" />
                    </div>
                    <img :src="second" alt="" loading="lazy" class="block w-full" />
                </div>

                <!-- Info card (large covers): screens count + mini chart -->
                <div
                    v-if="large && countLabel"
                    class="cover-float absolute bottom-[8%] left-[3%] z-20 hidden items-center gap-2.5 rounded-xl border border-white/40 bg-white/90 px-3 py-2 text-neutral-800 shadow-xl shadow-black/25 sm:flex"
                    style="animation-delay: 1.5s"
                >
                    <span class="grid size-8 place-items-center rounded-lg bg-gradient-to-br text-white" :class="gradient">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="12" rx="2" /><path d="M8 20h8M12 16v4" /></svg>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-xs font-bold">{{ countLabel }}</span>
                        <span class="block font-mono text-[10px] text-neutral-500">{{ project.year ?? 'project' }} · live build</span>
                    </span>
                    <span class="ml-1 flex h-7 items-end gap-0.5">
                        <span v-for="(h, i) in [40, 70, 55, 90, 65]" :key="i" class="w-1 rounded-sm bg-gradient-to-t" :class="gradient" :style="{ height: `${h}%` }" />
                    </span>
                </div>
            </template>

            <!-- ---------- No image: generated cover ---------- -->
            <template v-else>
                <div class="absolute right-[8%] bottom-0 left-[8%] h-[62%] rounded-t-xl border border-white/25 bg-white/10 backdrop-blur-sm transition duration-700 group-hover:-translate-y-1">
                    <div class="flex items-center gap-1.5 border-b border-white/20 px-3 py-2">
                        <span class="size-2 rounded-full bg-white/60" />
                        <span class="size-2 rounded-full bg-white/40" />
                        <span class="size-2 rounded-full bg-white/25" />
                        <span class="ml-2 h-2 w-1/3 rounded-full bg-white/20" />
                    </div>
                    <div class="grid grid-cols-[1fr_auto] gap-3 p-3" :class="large ? 'sm:p-5' : ''">
                        <div class="grid content-start gap-2">
                            <span class="h-2 w-2/3 rounded-full bg-white/35" />
                            <span class="h-2 w-1/2 rounded-full bg-white/20" />
                            <span class="h-2 w-3/5 rounded-full bg-white/15" />
                            <div v-if="large" class="mt-1 flex flex-wrap gap-1.5">
                                <span v-for="t in project.tags.slice(0, 5)" :key="t" class="rounded-md bg-white/15 px-2 py-0.5 font-mono text-[10px] text-white/90">{{ t }}</span>
                            </div>
                        </div>
                        <span class="flex h-10 items-end gap-1 sm:h-14">
                            <span v-for="(h, i) in [35, 60, 45, 85, 70, 95]" :key="i" class="w-1.5 rounded-sm bg-white/35 sm:w-2" :style="{ height: `${h}%` }" />
                        </span>
                    </div>
                </div>
                <span
                    class="absolute top-[10%] left-[8%] font-display leading-none font-bold tracking-tighter text-white/90"
                    :class="large ? 'text-7xl sm:text-8xl' : 'text-5xl'"
                >
                    {{ initials }}
                </span>
            </template>

            <!-- Floating tech chips (screenshot & phone covers) -->
            <template v-if="project.image">
                <span
                    v-for="(t, i) in chips"
                    :key="t"
                    class="cover-float absolute z-20 flex items-center gap-1 rounded-full border border-white/40 bg-white/20 font-semibold whitespace-nowrap text-white shadow-lg shadow-black/20 backdrop-blur-md"
                    :class="[
                        large ? 'px-2 py-0.5 text-[10px] sm:px-3 sm:py-1 sm:text-xs' : 'px-2 py-0.5 text-[10px]',
                        large
                            ? ['top-[6%] left-[5%]', 'top-[8%] right-[38%] hidden sm:flex', 'right-[4%] bottom-[12%]'][i]
                            : isMobile
                              ? ['top-[8%] left-[5%]', 'top-[30%] right-[5%]'][i]
                              : ['top-[5%] left-[5%]', 'top-[30%] left-[3%]'][i],
                    ]"
                    :style="{ animationDelay: `${i * 0.9}s` }"
                >
                    <span class="size-1.5 rounded-full bg-emerald-300 shadow-[0_0_6px_#6ee7b7]" />
                    {{ t }}
                </span>
            </template>

            <!-- Light sweep on hover -->
            <div class="pointer-events-none absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition duration-1000 group-hover:translate-x-full" />
        </div>
    </div>
</template>
