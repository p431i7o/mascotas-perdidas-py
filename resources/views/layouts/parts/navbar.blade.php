<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= route('root') ?>">Mascotas Perdidas</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            @auth
                <li class="nav-item @if(Route::current()->getName() == 'home') active @endif">
                    <a class="nav-link" href="{{ route('home') }}">Tablero <span class="sr-only">(current)</span></a>
                </li>
            @endauth
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

                    @if(auth()->user()->can(\App\Repositories\Permissions::MANAGE_DENOUNCES))
                        <li class="nav-item">
                            <a class="nav-link @if (Route::current()->getName() == 'denounce.index') active @endif"   href="{{ route('denounce.index')}}">Denuncias</a>
                        </li>
                    @endif
                @else
                <li class="nav-item">
                    <a class="nav-link @if (Route::current()->getName() == 'login') active @endif" href="{{ route('login') }}">Iniciar Sesi&oacute;n</a>
                    <!-- tabindex="-1"  disabled aria-disabled="true" -->
                </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link @if (Route::current()->getName() == 'register') active @endif" href="{{ route('register') }}">Registro</a>
                    </li>
                    @endif
                @endauth

            @auth
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout_form">
                        @csrf
                        <a href="javascript:;" class="clear nav-link" onclick="$('#logout_form').submit();">[{{ Auth::user()->email }}] Cerrar Sesión</a>
                    </form>
                    {{-- <a class="nav-link" href="<?= route('logout') ?>">Cerrar Sesi&oacute;n</a> --}}
                    <!-- tabindex="-1"  disabled aria-disabled="true" -->
                </li>
            @endauth

        </ul>
        <form action="<?= route('search') ?>" class="form-inline my-2 my-lg-0">
            <input id="search" name="search" class="form-control mr-sm-2" type="text" placeholder="Ciudad o departamento" aria-label="Buscar" data-toggle="tooltip" data-placement="top" title="Escriba el nombre de la ciudad y el autocompletado le sugerirá si es una ciudad o un departamento">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
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
        console.log(value,data,'selected');

      },
      searchKey: 'name',
      identifier: 'some-unique-identifier', // added to attribute data-identifier of the autocomplete div
      formatResult: function(value, data) {

        console.log('format result', value, data);
        console.log(this);
        //return value;
         var search = (this.searchKey) ? data[this.searchKey] : ''
        if (this.dataKey) data = data[this.dataKey]
        var pattern = '(' + value.replace(this.regEx, '\\$1') + ')'
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
