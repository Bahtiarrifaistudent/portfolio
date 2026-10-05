<script setup>
defineProps({
    profile: { type: Object, required: true },
});
</script>

<template>
    <section id="about" class="py-20 sm:py-28">
        <div class="container-page grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
            <!-- Profile card -->
            <div v-reveal class="relative mx-auto w-full max-w-sm self-start lg:mx-0">
                <div class="absolute inset-0 translate-x-3 translate-y-3 rounded-3xl border-2 border-dashed border-accent/40" />
                <div v-tilt="6" class="card card-static relative overflow-hidden rounded-3xl">
                    <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-br from-accent-2 via-accent to-cyan">
                        <img
                            v-if="profile.photo"
                            :src="profile.photo"
                            :alt="`Photo of ${profile.name}`"
                            class="size-full object-cover"
                            loading="lazy"
                        />
                        <template v-else>
                            <div class="bg-grid absolute inset-0 opacity-40 mix-blend-overlay" />
                            <span
                                class="absolute inset-0 grid place-items-center font-display text-[9rem] font-bold tracking-tighter text-white/90 sm:text-[11rem]"
                            >
                                {{ profile.initials }}
                            </span>
                        </template>
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-5 pt-16">
                            <p class="font-display text-xl font-bold text-white">{{ profile.name }}</p>
                            <p class="font-mono text-xs text-white/75">{{ profile.role }} · {{ profile.focus }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Story -->
            <div>
                <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />Who I Am</p>
                <h2 v-reveal="60" class="section-title">
                    A solid backend,<br class="hidden sm:block" />
                    an interface that is <span class="text-gradient">a joy to use</span>.
                </h2>

                <div class="mt-6 space-y-4 text-base leading-relaxed text-muted">
                    <p v-for="(p, i) in profile.bio" :key="i" v-reveal="100 + i * 60">{{ p }}</p>
                </div>

                <dl v-reveal="300" class="mt-8 grid gap-3 sm:grid-cols-2">
                    <div class="card flex items-start gap-3 p-4">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-accent/10 text-accent">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10 12 5 2 10l10 5 10-5z" /><path d="M6 12v5c3 2 9 2 12 0v-5" /></svg>
                        </span>
                        <div>
                            <dt class="text-xs text-muted">Campus</dt>
                            <dd class="text-sm font-semibold text-ink">{{ profile.campus }}</dd>
                        </div>
                    </div>
                    <div class="card flex items-start gap-3 p-4">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-cyan/10 text-cyan">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z" /><circle cx="12" cy="10" r="2.5" /></svg>
                        </span>
                        <div>
                            <dt class="text-xs text-muted">Location</dt>
                            <dd class="text-sm font-semibold text-ink">{{ profile.location }}</dd>
                        </div>
                    </div>
                    <div class="card flex items-start gap-3 p-4">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-laravel/10 text-laravel">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m8 9-4 3 4 3M16 9l4 3-4 3M13.5 6l-3 12" /></svg>
                        </span>
                        <div>
                            <dt class="text-xs text-muted">Focus</dt>
                            <dd class="text-sm font-semibold text-ink">{{ profile.focus }}</dd>
                        </div>
                    </div>
                    <div class="card flex items-start gap-3 p-4">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-accent-2/10 text-accent-2">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></svg>
                        </span>
                        <div class="min-w-0">
                            <dt class="text-xs text-muted">Email</dt>
                            <dd class="truncate text-sm font-semibold text-ink">
                                <a :href="`mailto:${profile.email}`" class="hover:text-accent">{{ profile.email }}</a>
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </section>
</template>
