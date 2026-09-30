<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SiteNavbar from '../Components/SiteNavbar.vue';
import SiteFooter from '../Components/SiteFooter.vue';
import SplashScreen from '../Components/SplashScreen.vue';
import CvDownloadModal from '../Components/CvDownloadModal.vue';
import CvViewerModal from '../Components/CvViewerModal.vue';
import ChatWidget from '../Components/ChatWidget.vue';

const page = usePage();
const profile = computed(() => page.props.profile);

// Changes on every page visit -> drives the page transition
const pageKey = computed(() => page.url.split('?')[0].split('#')[0]);
</script>

<template>
    <SplashScreen :profile="profile" />
    <SiteNavbar :profile="profile" />
    <main class="min-h-screen overflow-x-clip">
        <!-- Page transition: old page zooms out & blurs, new page zooms in through a "portal" -->
        <Transition name="page" mode="out-in">
            <div :key="pageKey" class="page-shell">
                <slot />
            </div>
        </Transition>
    </main>

    <!-- Portal rings that burst from the center on every page change -->
    <Transition name="portal">
        <div :key="pageKey" class="page-portal" aria-hidden="true">
            <span class="page-portal-ring" />
            <span class="page-portal-ring page-portal-ring--2" />
        </div>
    </Transition>
    <SiteFooter :profile="profile" />
    <ChatWidget :profile="profile" />
    <CvViewerModal :profile="profile" />
    <CvDownloadModal />
</template>
