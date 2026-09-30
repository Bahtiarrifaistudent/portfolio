<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SiteNavbar from '../Components/SiteNavbar.vue';
import SiteFooter from '../Components/SiteFooter.vue';

const page = usePage();
const profile = computed(() => page.props.profile);
</script>

<template>
    <SiteNavbar :profile="profile" />
    <main class="min-h-screen overflow-x-clip">
        <slot />
    </main>
    <SiteFooter :profile="profile" />
</template>
