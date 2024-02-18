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
// import 'chart.js';
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
// import '../css/sb-admin-2.css'
// import '../css/sb-admin-2.min.css'

import  DataTable from 'datatables.net-bs4';
import 'datatables.net-responsive-bs4'
import 'datatables.net-buttons-bs4'
// import 'datatables.net-select-bs4'
// import 'pdfmake'; //algo le falta y no funciona
// import 'jszip'; //no uso por eso comento
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-buttons/js/buttons.colVis.mjs';

window.Datatable = DataTable;
// DataTable(window, window.$);

let lang = import.meta.globEager('./es-MX.json');
let messages_es_mx = lang['./es-MX.json'];
// window.lang = lang;
window.messages_es_mx = messages_es_mx;

// ES6 Modules or TypeScript
import Swal from 'sweetalert2';
// import Swal from 'sweetalert2/dist/sweetalert2.all.js';
window.Swal = Swal;
import 'bootstrap-switch-button';
