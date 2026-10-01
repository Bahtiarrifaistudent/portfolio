<script setup>
import { computed } from 'vue';

// Project image. If a project has no image yet, show a cover
// generated automatically from its category, title, and tags.
const props = defineProps({
    project: { type: Object, required: true },
    // true = large cover (detail page / featured)
    large: { type: Boolean, default: false },
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
        <img
            v-if="project.image"
            :src="project.image"
            :alt="project.title"
            loading="lazy"
            class="size-full object-cover transition duration-700 group-hover:scale-105"
        />

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
