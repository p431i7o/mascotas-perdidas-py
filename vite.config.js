import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/sb-admin-2.js',
                'resources/js/chart-area-demo.js',
                'resources/js/chart-bar-demo.js',
                'resources/js/chart-pie-demo.js',
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
            '~chart.js':path.resolve(__dirname,'node_modules/chart.js'),
            '~leaflet':path.resolve(__dirname,'node_modules/leaflet'),
            '~datatables.net-bs4':path.resolve(__dirname,'node_modules/datatables.net-bs4'),
            '~datatables.net-responsive-bs4':path.resolve(__dirname,'node_modules/datatables.net-responsive-bs4')
        }
    },
});
