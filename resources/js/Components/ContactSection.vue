<script setup>
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import TechIcon from './TechIcon.vue';

defineProps({
    profile: { type: Object, required: true },
});

const page = usePage();
const success = computed(() => page.props.flash?.success);

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

function submit() {
    form.post('/kontak', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

const field =
    'w-full rounded-xl border bg-bg px-4 py-3 text-sm text-ink placeholder:text-muted/70 transition focus:border-accent focus:ring-4 focus:ring-accent/15 focus:outline-none';
</script>

<template>
    <section id="contact" class="relative overflow-hidden border-t border-line bg-surface/50 py-20 sm:py-28">
        <div class="pointer-events-none absolute -bottom-40 left-1/2 h-80 w-[40rem] -translate-x-1/2 rounded-full bg-accent-2/20 blur-[120px]" />

        <div class="container-page relative grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-14">
            <div>
                <p v-reveal class="section-kicker"><span class="h-px w-6 bg-accent" />Kontak</p>
                <h2 v-reveal="60" class="section-title">
                    Punya project atau<br class="hidden sm:block" />
                    tawaran magang? <span class="text-gradient">Ayo ngobrol.</span>
                </h2>
                <p v-reveal="120" class="mt-4 max-w-md text-muted">
                    Kirim pesan lewat form, atau hubungi saya langsung melalui salah satu kanal di bawah.
                </p>

                <ul class="mt-8 grid gap-3">
                    <li v-for="(s, i) in profile.socials" :key="s.label" v-reveal="160 + i * 60">
                        <a
                            :href="s.url"
                            target="_blank"
                            rel="noopener"
                            class="group card flex items-center gap-4 p-4 transition hover:border-accent/50"
                        >
                            <span class="grid size-11 place-items-center rounded-xl bg-surface-2 text-ink transition group-hover:text-accent">
                                <TechIcon :name="s.icon" class="size-5" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-xs text-muted">{{ s.label }}</span>
                                <span class="block truncate text-sm font-semibold text-ink">{{ s.handle }}</span>
                            </span>
                            <svg class="size-4 text-muted transition group-hover:translate-x-1 group-hover:text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                        </a>
                    </li>
                </ul>
            </div>

            <form v-reveal="120" class="card p-6 shadow-xl shadow-black/5 sm:p-8" novalidate @submit.prevent="submit">
                <div class="mb-6 flex items-center justify-between">
                    <p class="font-mono text-xs text-muted"><span class="text-accent">POST</span> /kontak</p>
                    <span class="font-mono text-[11px] text-muted">{{ form.message.length }}/2000</span>
                </div>

                <transition enter-from-class="opacity-0 -translate-y-1" enter-active-class="transition duration-300">
                    <div
                        v-if="success && !form.isDirty"
                        class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-600 dark:text-emerald-400"
                        role="status"
                    >
                        <svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 5 5L20 7" /></svg>
                        {{ success }}
                    </div>
                </transition>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-ink">Nama</label>
                        <input id="name" v-model="form.name" type="text" autocomplete="name" placeholder="Nama lengkap" :class="[field, form.errors.name ? 'border-red-500' : 'border-line']" />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-ink">Email</label>
                        <input id="email" v-model="form.email" type="email" autocomplete="email" placeholder="nama@email.com" :class="[field, form.errors.email ? 'border-red-500' : 'border-line']" />
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500">{{ form.errors.email }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="subject" class="mb-1.5 block text-sm font-medium text-ink">Subjek <span class="font-normal text-muted">(opsional)</span></label>
                    <input id="subject" v-model="form.subject" type="text" placeholder="Tawaran magang, kolaborasi, ..." :class="[field, form.errors.subject ? 'border-red-500' : 'border-line']" />
                    <p v-if="form.errors.subject" class="mt-1.5 text-xs text-red-500">{{ form.errors.subject }}</p>
                </div>

                <div class="mt-4">
                    <label for="message" class="mb-1.5 block text-sm font-medium text-ink">Pesan</label>
                    <textarea id="message" v-model="form.message" rows="5" maxlength="2000" placeholder="Ceritakan kebutuhan Anda..." :class="[field, 'resize-none', form.errors.message ? 'border-red-500' : 'border-line']" />
                    <p v-if="form.errors.message" class="mt-1.5 text-xs text-red-500">{{ form.errors.message }}</p>
                </div>

                <button type="submit" class="btn-primary mt-6 w-full disabled:cursor-not-allowed disabled:opacity-60" :disabled="form.processing">
                    <svg v-if="form.processing" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.2-8.6" /></svg>
                    {{ form.processing ? 'Mengirim...' : 'Kirim Pesan' }}
                </button>
            </form>
        </div>
    </section>
</template>
