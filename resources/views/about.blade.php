@extends('layouts.default.default')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-2 mt-5">Mascotas Perdidas Py @include('layouts.default.parts.logo',['size' => '150'])</h1>
            <h2>¿Qué es y para que sirve?</h2>
            <p>Antes que nada hola!, soy p431i7o, creador del sitio</p>

            <p>Bueno, la respuesta es sencilla, mi idea es que la gente pueda reportar en esta página cuando pierdan a sus
                mascotas (o cuando encuentren una), de manera a centralizar un poco más los esfuerzos de búsqueda,
                y también que la gente que los encuentra puedan anunciarlos aquí, de esa manera quienes rescataron y quienes
                perdieron puedan coincidir con más facilidad.</p>
            <p>
                El uso del sitio es totalmente gratuito.
            </p>
            <p>
                El proyecto se encuentra disponible en <a href="https://github.com/p431i7o/mascotas-perdidas-py">Github</a>
                para quienes quieran contribuir con el proyecto, reportar errores en la aplicación, o incluso replicarlo
                en otros países ya que esta versión está ajustada para ser usada en Paraguay 🇵🇾
            </p>
{{--            <p><strong>¿Cómo funciona esto?</strong></p>--}}
{{--            <p>Pues no es muy complicado, <u>lo primero que hay que hacer</u> es que te registres con una dirección de correo--}}
{{--                electrónico válido</p>--}}
{{--            <p>Luego de que completes el formulario de registro, te enviaremos un email con un enlace para que confirmes que--}}
{{--                la dirección de correo es efectivamente tuya, una vez que confirmes tu email--}}
{{--                ya podras iniciar una sesión y estarás listo para <a class="btn btn-primary btn-sm" href="{{ route('reports.create')}}"> hacer tu primer reporte</a>.</p>--}}
{{--            @guest--}}

{{--                <p>Listo quiero <a class="btn btn-primary btn-sm" href="<?= route('register') ?>" role="button">crear una--}}
{{--                        cuenta ahora</a> o si ya tienes una tal vez quieras <a class="btn btn-primary btn-sm "--}}
{{--                                                                               href="<?= route('login') ?>" role="button">Iniciar Sesi&oacute;n</a></p>--}}
{{--            @endguest--}}
        </div>
    </div>
@endsection
