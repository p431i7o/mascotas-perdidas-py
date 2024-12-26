@extends('layouts.default.default')
@push('styles')
    <style>
        #map-container { height: 600px; width: 100%; }
    </style>
@endpush
@section('content')
    @if(isset($message))
        <div class="alert alert-@if(isset($error)) danger @else info @endif alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    <div class="container my-5">
        <div class="p-5 text-center bg-body-tertiary rounded-3">
            <h1 class="text-body-emphasis">Mascotas Perdidas PY</h1>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                ¿Qué desea reportar?
            </p>
            <div class="d-inline-flex gap-2 mb-5">
                <a class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" href="{{route('reports.create',['type'=>'lost'])}}">
                    Una mascota perdida
                    <svg class="bi ms-2" width="24" height="24"><use xlink:href="#arrow-right-short"/></svg>
                </a>
                <a class="btn btn-outline-secondary btn-lg px-4 rounded-pill" href="{{route('reports.create',['type'=>'found'])}}">
                    Una mascota encontrada
                </a>
            </div>
        </div>
    </div>


    @if($reportes->count() > 0)
        <div class="container">
            <h1 class="display-4">&Uacute;ltimos Reportes:</h1>
            <div class="row mb-5 mt-5">
                <div id="map-container"></div>
            </div>


            <div class="row mb-2">
                @foreach ($reportes as $fila)
                    <div class="col-md-6">
                        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-4 d-flex flex-column position-static">
                                <strong class="d-inline-block mb-2 text-primary-emphasis">{{ $fila->type }}</strong>
                                <h3 class="mb-0">{{ $fila->name }}</h3>
                                <div class="mb-1 text-body-secondary">Nov 12</div>
                                <p class="card-text mb-auto">{{ Str::of($fila->description)->limit(50,'...',true) }}</p>
                                <a href="{{ route('reports.show',$fila->id) }}" class="icon-link gap-1 icon-link-hover stretched-link">
                                    Ampliar reporte
                                    <svg class="bi"><use xlink:href="#chevron-right"/></svg>
                                </a>
                            </div>
                            <div class="col-auto d-none d-lg-block">
                                @foreach(json_decode($fila->attachments) as $index=>$attachment)
                                    <img width="200" class="bd-placeholder-img" src="{{ route('report.image.show', [$fila->id, $index,'thumb']) }}" />
                                    @break
                                @endforeach
                                {{--                            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row" id="row_paginator">
                Páginas: <br/>
                @for($i=0;$i<ceil($reportCount/$limit);$i++)
                    <a class="btn @if($currentPage == $i+1)btn-primary @else btn-outline-primary @endif"
                       href="{{ route('root') }}?page={{ $i+1 }}">
                        {{ $i+1 }}
                    </a>&nbsp;
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
            map = new Mapa(coordinates, 6, 'marker');

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
