import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css', 
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~jquery': path.resolve(__dirname, 'node_modules/jquery'),
            '~jquery.easing':path.resolve(__dirname,'node_modules/jquery.easing'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~fontawesome':path.resolve(__dirname,'node_modules/@fortawesome/fontawesome-free'),
            '~chart.js':path.resolve(__dirname,'node_modules/chart.js')
        }
    },
});
