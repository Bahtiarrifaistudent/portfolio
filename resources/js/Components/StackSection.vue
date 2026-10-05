<script setup>
import { computed, ref } from 'vue';
import TechIcon from './TechIcon.vue';

const props = defineProps({
    stack: { type: Object, required: true },
});

const categories = computed(() => Object.keys(props.stack));
const active = ref(categories.value[0]);
const items = computed(() => props.stack[active.value] ?? []);
</script>

<template>
    <section id="stack" class="py-20 sm:py-28">
        <div class="container-page">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="max-w-xl">
                    <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />Tech Stack</p>
                    <h2 v-reveal="60" class="section-title">The tools I use every day.</h2>
                </div>

                <div v-reveal="120" class="flex items-center gap-4 font-mono text-xs text-muted">
                    <span class="inline-flex items-center gap-1.5"><span class="size-2 rounded-full bg-accent" />Used in my main project</span>
                    <span class="inline-flex items-center gap-1.5"><span class="size-2 rounded-full border border-muted" />Used before</span>
                </div>
            </div>

            <!-- Category tabs -->
            <div v-reveal="160" class="-mx-4 mt-8 overflow-x-auto px-4 sm:mx-0 sm:px-0" role="tablist">
                <div class="inline-flex gap-1 rounded-2xl border border-line bg-surface p-1">
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        type="button"
                        role="tab"
                        :aria-selected="active === cat"
                        class="rounded-xl px-4 py-2 text-sm font-semibold whitespace-nowrap transition"
                        :class="active === cat ? 'bg-ink text-bg' : 'text-muted hover:text-ink'"
                        @click="active = cat"
                    >
                        {{ cat }}
                        <span class="ml-1 font-mono text-[10px] opacity-60">{{ stack[cat].length }}</span>
                    </button>
                </div>
            </div>

            <!-- Grid: the old tab fades out completely before the new one comes in -->
            <Transition
                mode="out-in"
                leave-active-class="transition duration-150 ease-in"
                leave-to-class="opacity-0 -translate-y-1"
            >
                <ul :key="active" class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    <li
                        v-for="(tech, i) in items"
                        :key="tech.name"
                        class="stack-item group card relative flex flex-col items-start gap-3 p-4 sm:flex-row sm:items-center sm:p-5"
                        :style="{ animationDelay: `${i * 45}ms` }"
                    >
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-surface-2 text-ink transition group-hover:scale-110">
                            <TechIcon :name="tech.icon ?? ''" :label="tech.name" brand class="size-6" />
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold text-ink sm:truncate sm:text-base">{{ tech.name }}</span>
                            <span class="block truncate font-mono text-[11px] text-muted">{{ tech.note }}</span>
                        </span>
                        <span
                            class="absolute top-3 right-3 size-2 rounded-full"
                            :class="tech.level === 'core' ? 'bg-accent shadow-[0_0_10px_var(--accent)]' : 'border border-muted'"
                            :title="tech.level === 'core' ? 'Used in my main project' : 'Used before'"
                        />
                    </li>
                </ul>
            </Transition>
        </div>
    </section>
</template>
