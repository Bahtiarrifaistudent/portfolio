<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    kicker: { type: String, default: '' },
    title: { type: String, required: true },
    description: { type: String, default: '' },
    // [{ label, href }] - the last item has no href
    breadcrumb: { type: Array, default: () => [] },
});
</script>

<template>
    <header class="relative overflow-hidden border-b border-line pt-28 pb-12 sm:pt-36 sm:pb-16">
        <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black_20%,transparent_70%)]" />
        <div class="pointer-events-none absolute -top-32 left-1/4 h-72 w-[36rem] rounded-full bg-accent-2/20 blur-[110px]" />

        <div class="container-page relative">
            <nav v-if="breadcrumb.length" v-reveal aria-label="Breadcrumb" class="mb-6 flex flex-wrap items-center gap-2 font-mono text-xs text-muted">
                <template v-for="(item, i) in breadcrumb" :key="item.label">
                    <Link v-if="item.href" :href="item.href" class="transition hover:text-accent">{{ item.label }}</Link>
                    <span v-else class="text-ink">{{ item.label }}</span>
                    <span v-if="i < breadcrumb.length - 1" class="text-line">/</span>
                </template>
            </nav>

            <p v-if="kicker" v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />{{ kicker }}</p>
            <h1 v-reveal="60" class="mt-3 max-w-3xl font-display text-4xl leading-tight font-bold tracking-tight text-ink sm:text-5xl">
                <slot name="title">{{ title }}</slot>
            </h1>
            <p v-if="description" v-reveal="120" class="mt-4 max-w-2xl text-base leading-relaxed text-muted sm:text-lg">{{ description }}</p>
            <div v-if="$slots.default" v-reveal="180" class="mt-6"><slot /></div>
        </div>
    </header>
</template>
