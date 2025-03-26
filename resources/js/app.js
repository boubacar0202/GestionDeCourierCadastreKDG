import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import * as MazUI from "maz-ui";
import DefaultLayout from "./Layouts/DefaultLayout.vue";
import { installToaster } from 'maz-ui';



const appName = import.meta.env.VITE_APP_NAME || "Laravel";

import "maz-ui/styles";

const toasterOptions = {
    position: "bottom-right",
    timeout: 5000,
    persistent: false,
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        page.default.layout = page.default.layout || DefaultLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
    
        // Gestion globale des erreurs
        app.config.errorHandler = (err) => {
            console.error("❌ Erreur attrapée :", err);
            app.config.globalProperties.$toast.error("Une erreur est survenue !");
        };
    
        // Configuration de MazUI avec la couleur personnalisée
        app.use(MazUI, {
            colors: {
                primary: '#FE5835',  // 🔥 Définit la couleur principale pour MazUI
            },
        });
    
        // Ajout des plugins
        app.use(plugin)
            .use(ZiggyVue)
            .use(installToaster, toasterOptions)
            .mount(el);
    },    
      
    progress: {
        color: "#FE5835",
    },
});
