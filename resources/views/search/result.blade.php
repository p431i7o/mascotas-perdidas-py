@extends('layouts.default')
@section('content')
@if($results->count() > 0)
    <div class="container">
        <div class="row mb-5">
            <div id="map-container"></div>
        </div>
        <h1 class="display-4">Resultados:</h1>
        <div class="row" id="row_results">
            @foreach ($results as $fila)

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

                    <a href="{{ route('reports.show',$fila->id) }}" target="_blank">Ver</a>
                    </div>

                @endforeach
        </div>
    </div>

@else
<div class="container">
    <h1 class="display-4">Sin Resultados</h1>
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

            var action = document.getElementById("loadMap").getAttribute("data_load_map");
            let coordinates = new Array();
            coordinates['lng']  = DEFAULT_LNG;
            coordinates['lat'] = DEFAULT_LAT;
            map = new Map(coordinates, 6, 'marker');

            @foreach ($results as  $record)
                var marker = L.marker([{{$record->latitude }}, {{$record->longitude}} ],{
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
        // map.map.setZoom(15);
    }

        //@TODO: ENCONTRAR EL EVENTO CORRECTO DE LEAFTLET PARA SETEAR el marcador
        // setTimeout(function(){
        //     @foreach ($results as  $record)
        //         var marker = L.marker([{{$record->latitude }}, {{$record->longitude}} ],{
        //         id:{{ $record->id }},
        //         draggable: false,
        //     })
        //     .addTo(map.map)
        //     .bindPopup("<a href=\"{{route('reports.show',$record->id)}}\" target='_blank'>{{$record->name??__($record->type)}}</a><br/> {{ Str::of($record->description)->limit(50) }}")
        //     .on('click',clickZoom);
        //     @endforeach

        //     // map.map.setZoom(15)
        // },1500)



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
