import './bootstrap';
import 'leaflet';
import './utilities';
import './map';

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import jquery from 'jquery';
window.$ = jquery;
window.jQuery = jquery;

//  import './sb-admin-2';
import  'jquery.easing';
import 'chart.js';
// window.eax = easing;

import.meta.glob([
    '../img/**',
    // '../js/sb-admin-2.js',
    // '../js/chart-area-demo.js',
    // '../js/chart-bar-demo.js',
    // '../js/chart-pie-demo.js'
    // '../fonts/**',
    // '../../node_modules/bootstrap-icons/font/fonts/**'
  ]);

// import './sb-admin-2.js';

import '../sass/app.scss'
import '../css/app.css'
import '../css/sb-admin-2.css'
import '../css/sb-admin-2.min.css'

