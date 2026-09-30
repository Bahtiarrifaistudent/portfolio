<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import PageHeader from '../Components/PageHeader.vue';
import AboutSection from '../Components/AboutSection.vue';
import ArchitectureSection from '../Components/ArchitectureSection.vue';
import StackSection from '../Components/StackSection.vue';
import JourneySection from '../Components/JourneySection.vue';

defineProps({
    architecture: { type: Array, default: () => [] },
    stack: { type: Object, default: () => ({}) },
    journey: { type: Array, default: () => [] },
});

const profile = computed(() => usePage().props.profile);
</script>

<template>
    <Head title="About" />

    <PageHeader
        kicker="About"
        title="The person behind the code."
        description="My story, how I build applications, the tools I use, and where I am heading next."
        :breadcrumb="[{ label: 'Home', href: '/' }, { label: 'About' }]"
    />

    <AboutSection :profile="profile" />
    <ArchitectureSection v-if="architecture.length" :layers="architecture" />
    <StackSection :stack="stack" />
    <div class="border-t border-line">
        <JourneySection :journey="journey" />
    </div>
</template>
