import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

import Components from "unplugin-vue-components/vite";
import {
    UnpluginVueComponentsResolver,
    UnpluginDirectivesResolver,
    UnpluginModulesResolver,
} from "maz-ui/resolvers";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/css/app.css"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            dts: true,
            resolvers: [
                UnpluginVueComponentsResolver(),
                UnpluginDirectivesResolver(),
                UnpluginModulesResolver(),
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'courrierskdg.sn',
        },
        cors: true,
    }, 
});
