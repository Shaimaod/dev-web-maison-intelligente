import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
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
    ],
    server: {
        port: 5174, // Assurez-vous que Vite fonctionne sur ce port
        proxy: {
            '/js': 'http://localhost:8000/js', // Proxy les fichiers JS via Laravel
            '/css': 'http://localhost:8000/css', // Proxy les fichiers CSS via Laravel
        },
        strictPort: true,  // Forcer Vite à utiliser le port spécifié (5174)
        cors: true, // Autoriser CORS pour les requêtes entre serveurs
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
