<script setup>
import { Link } from '@inertiajs/vue3';
import TechIcon from './TechIcon.vue';
import { navLinks } from '../navigation';

defineProps({
    profile: { type: Object, required: true },
});

const year = new Date().getFullYear();
</script>

<template>
    <footer class="border-t border-line bg-surface/40">
        <div class="container-page grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <Link href="/" class="font-display text-xl font-bold tracking-tight text-ink">
                    {{ profile.name.split(' ')[0] }}&nbsp;<span class="text-gradient">{{ profile.name.split(' ').slice(1).join(' ') }}</span>
                </Link>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-muted">{{ profile.tagline }}</p>
            </div>

            <div>
                <p class="font-mono text-xs tracking-wider text-muted uppercase">Pages</p>
                <ul class="mt-4 grid grid-cols-2 gap-2 text-sm md:grid-cols-1">
                    <li v-for="link in [...navLinks, { href: '/contact', label: 'Contact' }]" :key="link.href">
                        <Link :href="link.href" class="text-ink transition hover:text-accent">{{ link.label }}</Link>
                    </li>
                </ul>
            </div>

            <div>
                <p class="font-mono text-xs tracking-wider text-muted uppercase">Connect</p>
                <ul class="mt-4 grid gap-2 text-sm">
                    <li v-for="s in profile.socials" :key="s.label">
                        <a :href="s.url" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-ink transition hover:text-accent">
                            <TechIcon :name="s.icon" class="size-4" />
                            {{ s.label }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-line">
            <div class="container-page flex flex-col items-center justify-between gap-2 py-6 text-center text-xs text-muted sm:flex-row sm:text-left">
                <p>&copy; {{ year }} {{ profile.name }}. All rights reserved.</p>
                <p>
                    Built with <span class="text-laravel">Laravel</span>, <span class="text-emerald-500">Vue</span> &amp;
                    <span class="text-cyan">Tailwind</span>.
                </p>
            </div>
        </div>
    </footer>
</template>
