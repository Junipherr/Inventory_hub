import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        allowedHosts: ['0.0.0.0'],
        host: '0.0.0.0',
        port: 5174,
        hmr: {
            host: '0.0.0.0',
        },
    },
});