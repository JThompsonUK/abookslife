import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from "./Shared/Layout";
import route from 'ziggy'
import { ZiggyVue } from "ziggy";
import { Ziggy } from "./ziggy";

createInertiaApp({
    resolve: name => {
        let page = require(`./Pages/${name}`).default;
        page.layout ??= Layout;
        return page;
    },

    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mixin({ methods: { route } })
            .component('Link', Link)
            .component('Head', Head)
            // .use(VueGoogleMaps, {
            //     load: {
            //         key: 'AIzaSyCYhCxQiuTzX8x1FtUJX9ngVSkzaavqY3Q',
            //         libraries: 'places',
            //     },
            // })
            .mount(el)
    },

    title: title => `My App - ${title}`

});

InertiaProgress.init({
    color: 'red',
    showSpinner: true
});

