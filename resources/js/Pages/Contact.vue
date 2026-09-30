<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import PageHeader from '../Components/PageHeader.vue';
import ContactSection from '../Components/ContactSection.vue';

const profile = computed(() => usePage().props.profile);
</script>

<template>
    <Head title="Contact" />

    <PageHeader
        kicker="Contact"
        title="Let's work together."
        description="Send a message through the form, or reach me directly on any of the channels below."
        :breadcrumb="[{ label: 'Home', href: '/' }, { label: 'Contact' }]"
    />

    <ContactSection :profile="profile" />
</template>
