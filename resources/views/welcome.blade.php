@extends('layouts.default')
@section('content')
    <?php if (isset($mensaje)) {
        if (isset($error)) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
        } else {
            echo "<div class='alert alert-info' alert-dismissible fade show' role='alert'>";
        }
        echo $mensaje;
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '</div>';
    } ?>
    @if(request()->input('page',1) <= 1)
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-2">Mascotas Perdidas Py</h1>
            <p>Hola, soy p431i7o <small>(Pablito en leet)</small> en las redes, creador del sitio, te preguntarás que es este sitio y para que sirve?
                Bueno, la respuesta es sencilla, mi idea es que la gente pueda reportar en esta página cuando pierdan a sus
                mascotas, de manera a centralizar un poco más los esfuerzos de búsqueda,
                y también que la gente que los encuentra puedan anunciarlos aquí, de esa manera quienes rescataron y quienes
                perdieron puedan coincidir con más facilidad.</p>
            <p><strong>¿Cómo funciona esto?</strong></p>
            <p>Pues no es muy complicado, <u>lo primero que hay que hacer</u> es que te registres con una dirección de correo
                electrónico válido</p>
            <p>Luego de que completes el formulario de registro, te enviaremos un email con un enlace para que confirmes que
                la dirección de correo es efectivamente tuya, una vez que confirmes tu email
                ya podras iniciar una sesión y estarás listo para hacer tu primer reporte.</p>
            @guest

            <p>Listo quiero <a class="btn btn-primary btn-sm" href="<?= route('register') ?>" role="button">crear una
                    cuenta ahora</a> o si ya tienes una tal vez quieras <a class="btn btn-primary btn-sm "
                    href="<?= route('login') ?>" role="button">Iniciar Sesi&oacute;n</a></p>
            @endguest
        </div>
    </div>
    @endif

    @if($reportes->count() > 0)
        <div class="container">
            <div class="row mb-5 mt-5">
                <div id="map-container"></div>
            </div>
            <h1 class="display-4">&Uacute;ltimos Reportes:</h1>
            <div class="row" id="row_results">
                @foreach ($reportes as $fila)

                    <div class='col-md-4 mb-5 col-sm-12 col-xl-3'>
                    <a href="{{ route('reports.show',$fila->id) }}" target='_blank'>
                        @if(!empty($fila->name))
                            <h2>{{ $fila->name }}</h2>
                        @else
                            <h2>{{ $fila->type }}</h2>
                        @endif
                        </a>
                        <br/>


                    <p>{{ Str::of($fila->description)->limit(50) }}</p>

                    <p>Departamento: {{ $fila->Department->name }} <br/>
                    Ciudad: {{ $fila->City->name }}<br/>
                    Distrito: {{ $fila->District->name }}<br/>
                    Barrio: {{ $fila->Neighborhood->name }}<br/>
                    <div class="row">
                    @foreach(json_decode($fila->attachments) as $index=>$attachment)
                        <img class="col-4" src="{{ route('report.image.show', [$fila->id, $index,'thumb']) }}" />
                    @endforeach
                    </div>

                    <a href="{{ route('reports.show',$fila->id) }}" target="_blank">Ver</a>
                    </div>

                @endforeach
            </div>
            <div class="row" id="row_paginator">
                Páginas: <br/>
                @for($i=0;$i<ceil($reportCount/$limit);$i++)
                    <a class="btn @if($currentPage == $i+1)btn-primary @else btn-outline-primary @endif" href="{{ route('root') }}?page={{ $i+1 }}">{{ $i+1 }}</a>&nbsp;
                @endfor
            </div>
        </div>
    @else
        <div class="container">
            <h1 class="display-4">Sin Reportes</h1>
        </div>
    @endif
@endsection

@push('pre-scripts')
<script type="text/javascript">
    var HOSTNAME = '{{url('/')}}';
    var HOSTNAME_API = HOSTNAME + '/api/';

    // Map.
    var DEFAULT_ZOOM_MAP = 6;
    var DEFAULT_ZOOM_MARKER = 10;
    var DEFAULT_MIN_ZOOM_MAP = 6;
    var DEFAULT_MAX_ZOOM_MAP = 20;

    // Villa Hayes - Paraguay.
    var DEFAULT_LNG = -57.623807;
    var DEFAULT_LAT = -23.299114;
</script>
<script  id="loadMap" data_load_map=marker type="text/javascript" charset="utf-8">
</script>
@endpush

@push('scripts')
    <script type="module">
        //window.onload = function(){};
        $(document).ready(function(){
            // console.log('ready');
            // var action = document.getElementById("loadMap").getAttribute("data_load_map");
            // var action = document.getElementById("loadMap").getAttribute("data_load_map");
            // if(getCookie("informedAboutLocation")==""){
            //     Swal.fire({
            //         title: "Desea compartir su ubicación?",
            //         text:"La ubicación es utilizada para poder ubicar el punto en el mapa, si elige denegar igual puede arrastrar el marcador hasta la ubicación correcta",
            //         showDenyButton: false,
            //         showCancelButton: false,
            //         confirmButtonText: "Entiendo",

            //         }).then((result) => {
            //         /* Read more about isConfirmed, isDenied below */
            //         if (result.isConfirmed) {
            //             setCookie("informedAboutLocation","yes");
            //             localization(action);
            //         }
            //     });
            // }else{
            //     localization(action);
            // }
            var action = document.getElementById("loadMap").getAttribute("data_load_map");
            let coordinates = new Array();
            coordinates['lng']  = DEFAULT_LNG;
            coordinates['lat'] = DEFAULT_LAT;
            map = new Map(coordinates, 6, 'marker');

            @foreach ($reportes as $index=>  $record)
                var marker_{{ $index }} = L.marker([{{$record->latitude }}, {{$record->longitude}} ],{
                id:{{ $record->id }},
                draggable: false,
                })
                .addTo(map.map)
                .bindPopup("<a href=\"{{route('reports.show',$record->id)}}\" target='_blank'>{{$record->name??__($record->type)}}</a><br/> {{ Str::of($record->description)->limit(50) }}")
                .on('click',clickZoom);
            @endforeach

        });
    </script>
    <script type="text/javascript">
    function clickZoom(e) {
        map.map.setView(e.target.getLatLng(),DEFAULT_ZOOM_MARKER);

    }





        function send_marker (){
            marker_point_map(event, ((gps_active)? DEFAULT_ZOOM_MARKER : DEFAULT_ZOOM_MAP))
        }
    </script>
@endpush

@push('styles')
    <style>
        #map-container { height: 600px; width: 100%; }
    </style>
@endpush
