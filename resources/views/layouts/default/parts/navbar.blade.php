<nav class="navbar navbar-expand-lg navbar-light bg-light" aria-label="Barra de navegación de Mascotas Perdidas PY">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= route('root') ?>">
            @if(Route::current()->getName() != 'root' && Route::current()->getName() != 'about')
                @include('layouts.default.parts.logo',['size' => '40'])
            @endif
            Mascotas Perdidas PY
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#theNavbar" aria-controls="theNavbar" aria-expanded="false" aria-label="Cambiar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="theNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
            @auth
                <li class="nav-item">
                    <a class="nav-link @if(Route::current()->getName() == 'home') active @endif"
                       @if(Route::current()->getName() == 'home') aria-current="page" @endif
                       href="{{ route('home') }}">
                        Tablero
                    </a>
                </li>
            @endauth
                <li class="nav-item">
                    <a class="nav-link @if (Route::current()->getName() == 'about') active @endif"
                       @if (Route::current()->getName() == 'about') aria-current="page" @endif
                       href="{{ route('about')}}">
                        Qué es esto?
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link @if (Route::current()->getName() == 'help') active @endif"--}}
{{--                       @if (Route::current()->getName() == 'help') aria-current="page" @endif--}}
{{--                       href="{{ route('help')}}">--}}
{{--                        Ayuda--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li class="nav-item">
                    <a class="nav-link @if (Route::current()->getName() == 'legal') active @endif"
                       @if (Route::current()->getName() == 'legal') aria-current="page" @endif
                       href="{{ route('legal')}}">
                        Legal
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>--}}
{{--                </li>--}}


            @auth
                <li class="nav-item">
                    <a class="nav-link @if (Route::current()->getName() == 'reports.create') active @endif"
                       @if (Route::current()->getName() == 'reports.create') aria-current="page" @endif
                       href="{{ route('reports.create') }}">Nuevo reporte</a>
                </li>
                @if(auth()->user()->can(\App\Repositories\Permissions::MODERATE_REPORTS) || auth()->user()->can(\App\Repositories\Permissions::MANAGE_USERS) || auth()->user()->can(\App\Repositories\Permissions::MANAGE_DENOUNCES))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">Administración</a>
                        <ul class="dropdown-menu">
                            @if(auth()->user()->can(\App\Repositories\Permissions::MODERATE_REPORTS))
                                <li>
                                    <a class="dropdown-item"  @if (Route::current()->getName() == 'moderation.index') active @endif"
                                       href="{{ route('moderation.index')}}">
                                        Moderar reportes
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->can(\App\Repositories\Permissions::MANAGE_USERS))
                                <li>
                                    <a class="dropdown-item"  @if (Route::current()->getName() == 'user.index') active @endif"
                                       href="{{ route('user.index')}}">
                                        Lista de Usuarios
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->can(\App\Repositories\Permissions::MANAGE_DENOUNCES))
                                <li>
                                    <a class="dropdown-item"  @if (Route::current()->getName() == 'denounce.index') active @endif"
                                       href="{{ route('denounce.index')}}">
                                        Denuncias Pendientes
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">[{{ Auth::user()->email }}]</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Mi perfil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout_form">
                                @csrf
                                <a href="javascript:;" class="dropdown-item" onclick="$('#logout_form').submit();">Cerrar Sesión</a>
                            </form>
                        </li>
                    </ul>
                </li>


            @else
                <li class="nav-item">
                    <a class="nav-link @if (Route::current()->getName() == 'login') active @endif"
                       @if (Route::current()->getName() == 'login') aria-current="page" @endif
                       href="{{ route('login') }}">Iniciar Sesi&oacute;n</a>
                    <!-- tabindex="-1"  disabled aria-disabled="true" -->
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link @if (Route::current()->getName() == 'register') active @endif"
                           @if (Route::current()->getName() == 'register') aria-current="page" @endif
                           href="{{ route('register') }}">Registro</a>
                    </li>
                @endif
            @endauth
            </ul>
            <form id="main_form_search" role="search" action="<?= route('search') ?>" class="d-flex" role="search">
                <input id="search" name="search" class="form-control me-2" type="text" placeholder="Ciudad o departamento" aria-label="Buscar" data-toggle="tooltip" data-placement="bottom" title="Escriba el nombre de la ciudad y el autocompletado le sugerirá si es una ciudad o un departamento">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>
<script type="module">
    $( "#search" ).autocomplete({
      serviceUrl: "{{route('search.autocomplete')}}",
      minChars: 2,
      maxHeight: 400,
      width: 300,
      zIndex: 9999,
      deferRequestBy: 1000, // miliseconds
      noCache: false,
      dataKey: 'name',

      onSelect: function(value, data) {
        // console.log(value,data,'selected');
          $("#main_form_search").submit();

      },
      searchKey: 'name',
      identifier: 'some-unique-identifier', // added to attribute data-identifier of the autocomplete div
      formatResult: function(value, data) {

        console.log('format result', value, data);
        console.log(this);
        //return value;
        let search = (this.searchKey) ? data[this.searchKey] : ''
        if (this.dataKey) data = data[this.dataKey]
        let pattern = '(' + value.replace(this.regEx, '\\$1') + ')'
        return ((search + ' (' + data).replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>') + ')')
    }
    });
</script>
<style>
    .autocomplete-w1 { position: absolute; top: 0px; left: 0px; margin: 6px 0 0 6px; }
    .autocomplete { border: 1px solid #999; background: #FFF; cursor: default; text-align: left; max-height: 350px; overflow: auto; margin: -6px 6px 6px -6px; }
    .autocomplete .selected { background: #F0F0F0; }
    .autocomplete div { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete strong { font-weight: normal; color: #3399FF; }
</style>
