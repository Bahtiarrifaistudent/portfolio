<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useTheme } from '../composables/useTheme';
import { useCvDownload } from '../composables/useCvDownload';
import { isActive, navLinks as links } from '../navigation';

const props = defineProps({
    profile: { type: Object, required: true },
});

const page = usePage();

// Text logo: "Bahtiar Rifai" (first word + the rest of the name, the rest in gradient)
const firstName = computed(() => props.profile.name.split(' ')[0]);
const lastName = computed(() => props.profile.name.split(' ').slice(1).join(' '));
const { isDark, toggle } = useTheme();
const { start: startCv } = useCvDownload();
const open = ref(false);
const scrolled = ref(false);

function onScroll() {
    scrolled.value = window.scrollY > 12;
}

// Close the mobile menu on every page change
watch(() => page.url, () => (open.value = false));

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});

onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
        :class="scrolled || open ? 'border-b border-line bg-bg/80 backdrop-blur-xl' : 'border-b border-transparent'"
    >
        <nav class="container-page flex h-16 items-center justify-between">
            <Link href="/" class="font-display text-xl font-bold tracking-tight text-ink transition hover:opacity-80" :aria-label="profile.name">
                {{ firstName }}&nbsp;<span class="text-gradient">{{ lastName }}</span>
            </Link>

            <ul class="hidden items-center gap-0.5 lg:flex">
                <li v-for="link in links" :key="link.href">
                    <Link
                        :href="link.href"
                        class="group relative rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-300"
                        :class="isActive(page.url, link.href) ? 'text-accent' : 'text-muted hover:text-ink'"
                    >
                        {{ link.label }}
                        <!-- Underline: fills left-to-right on hover, stays full on the active page -->
                        <span
                            class="nav-underline absolute inset-x-3 -bottom-px h-0.5 rounded-full"
                            :class="isActive(page.url, link.href) ? 'is-active' : ''"
                        />
                    </Link>
                </li>
            </ul>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="grid size-9 place-items-center rounded-xl border border-line bg-surface text-muted transition hover:text-accent"
                    :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    @click="toggle"
                >
                    <svg v-if="isDark" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" />
                    </svg>
                    <svg v-else class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z" />
                    </svg>
                </button>

                <button
                    v-if="profile.cv"
                    type="button"
                    class="btn-accent hidden !px-4 !py-2 sm:inline-flex"
                    @click="startCv(profile.cv)"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                    Download CV
                </button>
                <Link href="/contact" class="btn-primary hidden !px-4 !py-2 sm:inline-flex">Contact</Link>

                <button
                    type="button"
                    class="grid size-9 place-items-center rounded-xl border border-line bg-surface text-ink lg:hidden"
                    :aria-expanded="open"
                    aria-label="Open menu"
                    @click="open = !open"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path v-if="!open" d="M4 7h16M4 12h16M4 17h16" />
                        <path v-else d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>
        </nav>

        <transition
            enter-from-class="opacity-0 -translate-y-2"
            enter-active-class="transition duration-200"
            leave-to-class="opacity-0 -translate-y-2"
            leave-active-class="transition duration-150"
        >
            <div v-if="open" class="container-page pb-5 lg:hidden">
                <ul class="grid gap-1">
                    <li v-for="link in links" :key="link.href">
                        <Link
                            :href="link.href"
                            class="flex items-center justify-between rounded-xl px-3 py-3 text-sm font-medium hover:bg-surface-2"
                            :class="isActive(page.url, link.href) ? 'bg-surface-2 text-accent' : 'text-ink'"
                        >
                            {{ link.label }}
                            <span class="font-mono text-xs text-muted">{{ link.href }}</span>
                        </Link>
                    </li>
                </ul>
                <div class="mt-3 grid gap-2" :class="profile.cv ? 'grid-cols-2' : ''">
                    <button v-if="profile.cv" type="button" class="btn-accent w-full" @click="open = false; startCv(profile.cv)">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                        Download CV
                    </button>
                    <Link href="/contact" class="btn-primary w-full">Contact Me</Link>
                </div>
            </div>
        </transition>
    </header>
</template>
