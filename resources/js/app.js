import '@fontsource-variable/plus-jakarta-sans';
import '@fontsource-variable/space-grotesk';
import '@fontsource-variable/jetbrains-mono';
import '../css/app.css';
import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { reveal } from './directives/reveal';
import SiteLayout from './Layouts/SiteLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Bahtiar Rifai';

createInertiaApp({
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        // Semua halaman memakai SiteLayout (navbar + footer) secara default
        page.default.layout = page.default.layout || SiteLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .directive('reveal', reveal)
            .mount(el);
    },
    progress: {
        color: '#e879f9',
    },
});
