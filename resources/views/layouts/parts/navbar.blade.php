<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= route('root') ?>">Mascotas Perdidas</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item @if(Route::current()->getName() == 'home') active @endif">
                <a class="nav-link" href="{{ route('home') }}">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Route::current()->getName() == 'help') active @endif" href="{{ route('help')}}">Ayuda</a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if (Route::current()->getName() == 'legal') active @endif" href="{{ route('legal')}}">Legal</a>
            </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link @if (Route::current()->getName() == 'reports.create') active @endif" href="{{ route('reports.create') }}">Nuevo reporte</a>
                    </li>
                    @if(auth()->user()->can(\App\Repositories\Permissions::MODERATE_REPORTS))
                        <li class="nav-item">
                            <a class="nav-link @if (Route::current()->getName() == 'moderation.index') active @endif"   href="{{ route('moderation.index')}}">Moderar</a>
                        </li>
                    @endif

                    @if(auth()->user()->can(\App\Repositories\Permissions::MANAGE_USERS))
                        <li class="nav-item">
                            <a class="nav-link @if (Route::current()->getName() == 'users.index') active @endif"   href="{{ route('user.index')}}">Usuarios</a>
                        </li>
                    @endif
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
                    <form method="POST" action="{{ route('logout') }}" id="logout_form">
                        @csrf
                        <a href="javascript:;" class="clear nav-link" onclick="$('#logout_form').submit();">Cerrar Sesion</a>
                    </form>
                    {{-- <a class="nav-link" href="<?= route('logout') ?>">Cerrar Sesi&oacute;n</a> --}}
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
