<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import ContactSection from '../Components/ContactSection.vue';

const profile = computed(() => usePage().props.profile);
</script>

<template>
    <Head title="Kontak" />
    <div class="pt-12 sm:pt-16">
        <ContactSection :profile="profile" />
    </div>
</template>
