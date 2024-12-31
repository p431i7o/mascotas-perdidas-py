<!DOCTYPE html>
<html class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mascotas Perdidas PY - Encuentra y Reporta Mascotas Extraviadas en Paraguay</title>
    <meta name="description" content="Reporta y encuentra mascotas perdidas en Paraguay. Sube fotos, ubicación y detalles para ayudar a reunir familias con sus animales.">
    <meta name="keywords" content="mascotas perdidas, mascotas encontradas, Paraguay, animales extraviados, localizar mascotas, reportar mascotas">
    <meta name="author" content="Mascotas Perdidas PY">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="PY">
    <meta name="geo.placename" content="Paraguay">
    <meta name="geo.position" content="-23.4425;-58.4438">
    <meta name="ICBM" content="-23.4425, -58.4438">
    @stack('meta-data')
    @stack('pre-scripts')
    @stack('styles')
    <!-- CSS only -->
    {{-- <link rel="stylesheet" href="<?= base_url('/assets/dist/css/bootstrap.css') ?>"> --}}
    @vite('resources/js/app.js')
    @vite('node_modules/jquery.autocomplete/jquery.autocomplete.js')
    <style>
        #buttonBackToTop {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed/sticky position */
            bottom: 20px; /* Place the button at the bottom of the page */
            right: 30px; /* Place the button 30px from the right */
            z-index: 9999999; /* Make sure it does not overlap */
            border: none; /* Remove borders */
            outline: none; /* Remove outline */
            /*background-color: color(--bs-btn-bg); !* Set a background color *!*/
            color: white; /* Text color */
            cursor: pointer; /* Add a mouse pointer on hover */
            padding: 15px; /* Some padding */
            border-radius: 10px; /* Rounded corners */
            font-size: 18px; /* Increase font size */
        }

        #buttonBackToTop:hover {
            background-color: #555; /* Add a dark-grey background on hover */
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
<header>
    @include('layouts.default.parts.navbar')
</header>
<main role="main" class="flex-shrink-0">

    @yield('content')

</main>
<button onclick="backToTop()" id="buttonBackToTop" title="Volver arriba" class="btn btn-primary rounded-circle"><i class="fa fa-arrow-up"></i></button>
@include('layouts.default.parts.footer')
{{-- <script src="<?= base_url('/assets/jquery-3.5.1.min.js') ?>"></script>
    <script src="<?= base_url('/assets/dist/js/bootstrap.min.js') ?>" ></script> --}}
@stack('scripts')
<script>
    let buttonBackToTop = document.getElementById("buttonBackToTop");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            buttonBackToTop.style.display = "block";
        } else {
            buttonBackToTop.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function backToTop() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>
</body>

</html>
