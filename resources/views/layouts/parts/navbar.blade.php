<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= route('root') ?>">Mascotas Perdidas</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if (Route::current()->getName() == 'root') {
                echo 'active';
            } ?>">
                <a class="nav-link" href="<?= route('root') ?>">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if (Route::current()->getName() == 'help') {
                    echo 'active';
                } ?>" href="<?= route('help') ?>">Ayuda</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link <?php if ( Route::has('report/*') ) {
                    echo 'active';
                } ?>" href="<?= route('reports.index') ?>">Reportes de Mascotas</a>
            </li> --}}

            <li class="nav-item">
                <a class="nav-link <?php if (Route::current()->getName() == 'legal') {
                    echo 'active';
                } ?>" href="<?= route('legal') ?>">Legal</a>
            </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link <?php if (Route::current()->getName() == 'reports.create') {
                            echo 'active';
                    } ?>" href="<?= route('reports.create') ?>">Nuevo reporte</a>
                    </li>
                @else
                <li class="nav-item">
                    <a class="nav-link <?php if (Route::has('login')) {
                        echo 'active';
                    } ?>" href="<?= route('login') ?>">Iniciar Sesi&oacute;n</a>
                    <!-- tabindex="-1"  disabled aria-disabled="true" -->
                </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link <?php if (Route::current()->getName() == 'register') {
                            echo 'active';
                        } ?>" href="<?= route('register') ?>">Registro</a>
                    </li>
                    @endif
                @endauth
            {{-- <li class="nav-item">
                <a class="nav-link <?php if (Route::has('profile') ) {
                    echo 'active';
                } ?>" href="<?= route('home') ?>">Mi cuenta</a>
            </li> --}}
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('logout') ?>">Cerrar Sesi&oacute;n</a>
                    <!-- tabindex="-1"  disabled aria-disabled="true" -->
                </li>
            @endauth

        </ul>
        <form action="<?= route('search') ?>" class="form-inline my-2 my-lg-0">
            <input name="search" class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
    </div>
</nav>
