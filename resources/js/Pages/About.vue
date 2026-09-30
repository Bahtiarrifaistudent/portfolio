<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
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
    <Head title="Tentang" />

    <div class="pt-12 sm:pt-16">
        <AboutSection :profile="profile" />
    </div>
    <ArchitectureSection v-if="architecture.length" :layers="architecture" />
    <StackSection :stack="stack" />
    <div class="border-t border-line">
        <JourneySection :journey="journey" />
    </div>
</template>
