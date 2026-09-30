<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useCvDownload } from '../composables/useCvDownload';

const props = defineProps({
    profile: { type: Object, required: true },
});

const { start: startCv } = useCvDownload();

const open = ref(false);
const input = ref('');
const loading = ref(false);
const unread = ref(true);
const listEl = ref(null);
const inputEl = ref(null);

const firstName = props.profile.name.split(' ')[0];

// Conversation history. role: bot | user
const messages = ref([
    {
        role: 'bot',
        text: `Hi! I'm ${firstName}'s virtual assistant. What would you like to know?`,
        links: [],
        suggestions: [`Who is ${firstName}?`, 'What are the skills?', 'Show projects', 'Download CV'],
    },
]);

function scrollBottom() {
    nextTick(() => {
        if (listEl.value) listEl.value.scrollTop = listEl.value.scrollHeight;
    });
}

function toggle() {
    open.value = !open.value;
    if (open.value) {
        unread.value = false;
        scrollBottom();
        nextTick(() => inputEl.value?.focus());
    }
}

async function send(text) {
    const message = (text ?? input.value).trim();
    if (!message || loading.value) return;

    messages.value.push({ role: 'user', text: message });
    input.value = '';
    loading.value = true;
    scrollBottom();

    const started = Date.now();
    try {
        const { data } = await axios.post('/chat', { message });
        // Minimum delay so the "typing" indicator feels natural
        await new Promise((r) => setTimeout(r, Math.max(0, 650 - (Date.now() - started))));
        messages.value.push({ role: 'bot', text: data.reply, links: data.links ?? [], suggestions: data.suggestions ?? [] });
    } catch (e) {
        const text =
            e.response?.status === 429
                ? 'Too many messages. Please wait a moment and try again.'
                : 'Sorry, something went wrong. Please try again, or use the Contact page.';
        messages.value.push({ role: 'bot', text, links: [{ label: 'Contact page', url: '/contact' }], suggestions: [] });
    } finally {
        loading.value = false;
        scrollBottom();
        if (!open.value) unread.value = true;
    }
}

function openLink(link) {
    if (link.download) {
        startCv(link.url);
        return;
    }
    if (link.url.startsWith('/')) {
        router.visit(link.url);
        if (window.innerWidth < 640) open.value = false;
        return;
    }
    window.open(link.url, link.url.startsWith('mailto:') ? '_self' : '_blank', 'noopener');
}

function onKey(e) {
    if (e.key === 'Escape' && open.value) open.value = false;
}
onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => window.removeEventListener('keydown', onKey));
</script>

<template>
    <div class="fixed right-4 bottom-4 z-[80] flex flex-col items-end sm:right-6 sm:bottom-6">
        <!-- Chat panel -->
        <transition
            enter-from-class="opacity-0 translate-y-4 scale-95"
            enter-active-class="transition duration-300 ease-out origin-bottom-right"
            leave-to-class="opacity-0 translate-y-4 scale-95"
            leave-active-class="transition duration-200 ease-in origin-bottom-right"
        >
            <section
                v-if="open"
                class="card card-static mb-4 flex h-[min(34rem,calc(100dvh-7rem))] w-[calc(100vw-2rem)] flex-col overflow-hidden shadow-2xl shadow-black/30 sm:w-96"
                role="dialog"
                aria-label="Chat assistant"
            >
                <!-- Header -->
                <header class="relative flex items-center gap-3 overflow-hidden bg-gradient-to-r from-accent-2 via-accent to-cyan px-4 py-3.5 text-white">
                    <div class="bg-grid absolute inset-0 opacity-30 mix-blend-overlay" />
                    <span class="relative grid size-10 shrink-0 place-items-center overflow-hidden rounded-full border-2 border-white/60 bg-white/20">
                        <span class="font-display text-sm font-bold">{{ profile.initials }}</span>
                        <span class="absolute right-0 bottom-0 size-2.5 rounded-full border-2 border-white bg-emerald-400" />
                    </span>
                    <div class="relative min-w-0 flex-1">
                        <p class="truncate font-display font-bold">{{ firstName }}'s Assistant</p>
                        <p class="text-xs text-white/80">Online · usually replies instantly</p>
                    </div>
                    <button type="button" class="relative grid size-8 place-items-center rounded-lg hover:bg-white/15" aria-label="Close chat" @click="open = false">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 6l12 12M18 6L6 18" /></svg>
                    </button>
                </header>

                <!-- Messages -->
                <div ref="listEl" class="flex-1 space-y-4 overflow-y-auto overscroll-contain bg-bg/60 px-4 py-4">
                    <div v-for="(m, i) in messages" :key="i" class="chat-in flex flex-col" :class="m.role === 'user' ? 'items-end' : 'items-start'">
                        <div
                            class="max-w-[85%] rounded-2xl px-3.5 py-2.5 text-sm leading-relaxed whitespace-pre-line"
                            :class="m.role === 'user' ? 'rounded-br-md bg-gradient-to-br from-accent-2 to-accent text-white' : 'rounded-bl-md border border-line bg-surface text-ink'"
                        >
                            {{ m.text }}
                        </div>

                        <div v-if="m.links?.length" class="mt-2 flex max-w-[85%] flex-wrap gap-1.5">
                            <button
                                v-for="l in m.links"
                                :key="l.url + l.label"
                                type="button"
                                class="inline-flex items-center gap-1 rounded-lg border border-accent/40 bg-accent/10 px-2.5 py-1 text-xs font-semibold text-accent transition hover:bg-accent hover:text-white"
                                @click="openLink(l)"
                            >
                                {{ l.label }}
                                <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 17 17 7M8 7h9v9" /></svg>
                            </button>
                        </div>

                        <div v-if="m.role === 'bot' && m.suggestions?.length && i === messages.length - 1 && !loading" class="mt-2.5 flex flex-wrap gap-1.5">
                            <button
                                v-for="s in m.suggestions"
                                :key="s"
                                type="button"
                                class="rounded-full border border-line bg-surface px-3 py-1 text-xs text-muted transition hover:border-accent hover:text-accent"
                                @click="send(s)"
                            >
                                {{ s }}
                            </button>
                        </div>
                    </div>

                    <!-- Typing indicator -->
                    <div v-if="loading" class="chat-in flex items-center gap-1 self-start rounded-2xl rounded-bl-md border border-line bg-surface px-4 py-3 w-fit">
                        <span class="chat-dot size-1.5 rounded-full bg-muted" />
                        <span class="chat-dot size-1.5 rounded-full bg-muted [animation-delay:0.15s]" />
                        <span class="chat-dot size-1.5 rounded-full bg-muted [animation-delay:0.3s]" />
                    </div>
                </div>

                <!-- Input -->
                <form class="flex items-center gap-2 border-t border-line bg-surface p-3" @submit.prevent="send()">
                    <input
                        ref="inputEl"
                        v-model="input"
                        type="text"
                        maxlength="300"
                        placeholder="Ask me anything..."
                        class="min-w-0 flex-1 rounded-xl border border-line bg-bg px-3.5 py-2.5 text-sm text-ink placeholder:text-muted/70 focus:border-accent focus:ring-4 focus:ring-accent/15 focus:outline-none"
                        aria-label="Message"
                    />
                    <button
                        type="submit"
                        class="grid size-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-accent-2 to-accent text-white transition hover:scale-105 disabled:opacity-40 disabled:hover:scale-100"
                        :disabled="!input.trim() || loading"
                        aria-label="Send"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z" /></svg>
                    </button>
                </form>
            </section>
        </transition>

        <!-- Floating button -->
        <button
            type="button"
            class="group relative grid size-14 place-items-center rounded-full bg-gradient-to-br from-accent-2 via-accent to-cyan text-white shadow-xl shadow-accent/30 transition hover:scale-105 active:scale-95"
            :aria-label="open ? 'Close chat assistant' : 'Open chat assistant'"
            :aria-expanded="open"
            @click="toggle"
        >
            <span v-if="!open" class="absolute inset-0 animate-ping rounded-full bg-accent opacity-25" />
            <transition mode="out-in" enter-from-class="rotate-90 scale-50 opacity-0" enter-active-class="transition duration-200" leave-to-class="-rotate-90 scale-50 opacity-0" leave-active-class="transition duration-150">
                <svg v-if="!open" key="chat" class="relative size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z" />
                    <path d="M8.5 12h.01M12 12h.01M15.5 12h.01" stroke-width="3" />
                </svg>
                <svg v-else key="close" class="relative size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 6l12 12M18 6L6 18" /></svg>
            </transition>
            <span v-if="unread && !open" class="absolute -top-0.5 -right-0.5 grid size-5 place-items-center rounded-full border-2 border-bg bg-laravel text-[10px] font-bold">1</span>
        </button>
    </div>
</template>
