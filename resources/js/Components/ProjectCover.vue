<script setup>
import { computed } from 'vue';

// Project cover.
// - With a screenshot: a "showcase" (gradient stage by category, the app in a window, and on
//   large covers a second screenshot floating on the right).
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
// Second screenshot for the large showcase (the first one is the cover itself)
// 'cover_second' in config/portfolio.php chooses it by keyword (e.g. 'remote'); otherwise the next screenshot
const second = computed(
    () => props.project.cover_second_src ?? props.project.screenshots?.find((x) => x.src !== props.project.image && x.type !== 'mobile')?.src ?? null,
);

// Initials from the title (text before ":" only). One-word titles use the first 2 letters.
// Override per project with 'initials' => 'XX' in config/portfolio.php.
const initials = computed(() => {
    if (props.project.initials) return props.project.initials;
    const words = props.project.title.split(':')[0].split(/\s+/).filter((w) => /^[A-Za-z]/.test(w));
    if (words.length === 1) return words[0].slice(0, 2).toUpperCase();
    return words.slice(0, 2).map((w) => w[0].toUpperCase()).join('');
});
</script>

<template>
    <div class="relative size-full overflow-hidden">
        <!-- Real screenshot: showcase layout (gradient stage + app window, plus a second window when there are more screenshots) -->
        <div v-if="project.image" class="absolute inset-0 overflow-hidden bg-gradient-to-br" :class="gradient">
            <div class="bg-grid absolute inset-0 opacity-30 mix-blend-overlay" />
            <div class="pointer-events-none absolute -top-1/3 left-1/4 size-[70%] rounded-full bg-white/25 blur-3xl" />
            <div class="pointer-events-none absolute -right-[10%] -bottom-1/3 size-[55%] rounded-full bg-black/25 blur-3xl" />

            <!-- Second screenshot, floating behind on the right (large covers only) -->
            <div
                v-if="large && second"
                class="absolute right-[5%] w-[34%] overflow-hidden rounded-lg border border-white/30 bg-white/10 opacity-90 shadow-2xl shadow-black/40 transition duration-700 group-hover:-translate-y-1 group-hover:rotate-1"
                :class="center ? 'top-[30%]' : 'top-[24%]'"
            >
                <div class="flex items-center gap-1 border-b border-white/20 bg-white/15 px-2 py-1.5 backdrop-blur">
                    <span class="size-1.5 rounded-full bg-white/70" />
                    <span class="size-1.5 rounded-full bg-white/50" />
                    <span class="size-1.5 rounded-full bg-white/30" />
                </div>
                <img :src="second" alt="" loading="lazy" class="block w-full" />
            </div>

            <!-- Main window -->
            <div
                class="absolute overflow-hidden rounded-xl border border-white/30 bg-white/10 shadow-2xl shadow-black/40 transition duration-700 group-hover:-translate-y-1.5"
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
        </div>

        <div v-else class="absolute inset-0 bg-gradient-to-br transition duration-700 group-hover:scale-105" :class="gradient">
            <div class="bg-grid absolute inset-0 opacity-40 mix-blend-overlay" />
            <!-- Mock browser window -->
            <div
                class="absolute right-[8%] bottom-0 left-[8%] h-[62%] rounded-t-xl border border-white/25 bg-white/10 backdrop-blur-sm"
            >
                <div class="flex items-center gap-1.5 border-b border-white/20 px-3 py-2">
                    <span class="size-2 rounded-full bg-white/60" />
                    <span class="size-2 rounded-full bg-white/40" />
                    <span class="size-2 rounded-full bg-white/25" />
                    <span class="ml-2 h-2 w-1/3 rounded-full bg-white/20" />
                </div>
                <div class="grid gap-2 p-3" :class="large ? 'sm:p-5' : ''">
                    <span class="h-2 w-2/3 rounded-full bg-white/35" />
                    <span class="h-2 w-1/2 rounded-full bg-white/20" />
                    <div v-if="large" class="mt-1 flex flex-wrap gap-1.5">
                        <span
                            v-for="t in project.tags.slice(0, 5)"
                            :key="t"
                            class="rounded-md bg-white/15 px-2 py-0.5 font-mono text-[10px] text-white/90"
                        >
                            {{ t }}
                        </span>
                    </div>
                </div>
            </div>
            <span
                class="absolute top-[10%] left-[8%] font-display leading-none font-bold tracking-tighter text-white/90"
                :class="large ? 'text-7xl sm:text-8xl' : 'text-5xl'"
            >
                {{ initials }}
            </span>
        </div>
    </div>
</template>
