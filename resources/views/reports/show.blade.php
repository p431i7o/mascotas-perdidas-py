@extends('layouts.default')
@section('content')
    <div class="container">
        <div class="row text-center">
            {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
            <h4 class="display-3 text-center">{{ $report->name ?? '-- Sin nombre --' }} <small>[{{ $report->type }}]</small></h4>

        </div>
    </div>
    <div class="container">
        <div class="row">
            <a href="#" class="btn btn-primary"><i class="fa-regular fa-envelope"></i> Contactar por este reporte</a>
        </div>
        <div class="row">
            @foreach (json_decode($report->attachments) as $index => $value)
                <div class="col-sm-3">
                    <img src="{{ route('report.image.show', [$report->id, $index]) }}" />
                </div>
            @endforeach
        </div>
        <div class="row">
            <table class="table col-4">
                <tr>
                    <th>Nombre</th><td>{{ $report->name }}</td>
                </tr>
                <tr>
                    <th>Departamento</th><td>{{ $report->Department()->first()->name }}</td>
                </tr>
                <tr>
                    <th>Ciudad</th><td>{{ $report->City()->first()->name }}</td>
                </tr>
                <tr>
                    <th>Barrio</th><td>{{ $report->Neighborhood()->first()->name }}</td>
                </tr>
                <tr>
                    <th>Dirección</th><td>{{ $report->address }}</td>
                </tr>
            </table>
            <div class="col-4">
                <strong>Descripción</strong><br/>
                {{$report->description}}<br/>
                <strong>Fecha</strong><br/>
                {{$report->date->isoFormat('DD/MMM/YYYY HH:mm')}}<br/>
            </div>
        </div>
        <hr />
        <div class="row">
            <div id="map-container"></div>
        </div>
        <div class="row mt-5">
            <a href="{{ route('report.denounce',[$report->id])}}" class="btn btn-danger"><i class="fa-solid fa-flag"></i> Denunciar este reporte</a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #map-container {
            height: 600px;
            width: 100%;
        }
    </style>
@endpush
@push('pre-scripts')
    <script type="text/javascript">
        var HOSTNAME = '{{ url('/') }}';
        var HOSTNAME_API = HOSTNAME + '/api/';

        // Map.
        var DEFAULT_ZOOM_MAP = 6;
        var DEFAULT_ZOOM_MARKER = 16;
        var DEFAULT_MIN_ZOOM_MAP = 6;
        var DEFAULT_MAX_ZOOM_MAP = 20;

        // Villa Hayes - Paraguay.
        var DEFAULT_LNG =  {{ $report->longitude }}; // -57.623807;
        var DEFAULT_LAT =  {{ $report->latitude }}; //-23.299114;
    </script>
    <script id="loadMap" data_load_map=marker type="text/javascript" charset="utf-8">
        // window.onload = function() {
        //     var action = document.getElementById("loadMap").getAttribute("data_load_map");
        //     // debugger;
        //     localization(action);


        // };
    </script>
@endpush
@push('scripts')
<script type="module">
    //window.onload = function(){};
    $(document).ready(function(){
        console.log('ready');
        var action = document.getElementById("loadMap").getAttribute("data_load_map");
        localization(action);

    });
</script>
    <script type="text/javascript">
        function clickZoom(e) {
            map.map.setView(e.target.getLatLng(), DEFAULT_ZOOM_MARKER);
            // map.map.setZoom(15);
        }
        //@TODO: ENCONTRAR EL EVENTO CORRECTO DE LEAFTLET PARA SETEAR el marcador
        setTimeout(function() {
            var marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
                id: {{ $report->id }},
                draggable: false,
            }).addTo(map.map).bindPopup("{{ $report->name ?? __($report->type) }}").on('click', clickZoom);
            // debugger;
             map.map.setZoom(15);
        }, 1500)
    </script>
@endpush
