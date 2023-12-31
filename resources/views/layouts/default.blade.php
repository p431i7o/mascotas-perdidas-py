<!DOCTYPE html>
<html class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mascotas Perdidas</title>
    @stack('pre-scripts')
    @stack('styles')
    <!-- CSS only -->
    {{-- <link rel="stylesheet" href="<?= base_url('/assets/dist/css/bootstrap.css') ?>"> --}}
    @vite('resources/js/app.js')]
</head>

<body class="d-flex flex-column h-100">
    <header>
        @include('layouts.parts.navbar')
    </header>
    <main role="main" class="flex-shrink-0">

        @yield('content')

    </main>
    <footer class="footer mt-auto py-3">
        <div class="container">
            <span class="text-muted">
                Mascotas Perdidas Py - <?= date('Y') ?> Todos los derechos reservados
            </span>
        </div>
    </footer>
    {{-- <script src="<?= base_url('/assets/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?= base_url('/assets/dist/js/bootstrap.min.js') ?>" ></script> --}}
        @stack('scripts')
</body>

</html>
