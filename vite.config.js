import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    define: {
        'process.env': {
            VITE_PAYPAL_SANDBOX_CLIENT_ID: JSON.stringify(process.env.PAYPAL_SANDBOX_CLIENT_ID),
            VITE_PAYPAL_SANDBOX_CLIENT_SECRET: JSON.stringify(process.env.PAYPAL_SANDBOX_CLIENT_SECRET),
        }
    }
});
