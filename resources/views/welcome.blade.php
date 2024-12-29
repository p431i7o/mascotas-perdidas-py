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
                <a class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" href="{{route('reports.create',['type'=>'Lost'])}}">
                    Una mascota perdida
                </a>
                <a class="btn btn-outline-secondary btn-lg px-4 rounded-pill" href="{{route('reports.create',['type'=>'Found'])}}">
                    Una mascota encontrada
                </a>
            </div>
        </div>
    </div>


    @if($reports->count() > 0)
        <div class="container">
            <h1 class="display-4">&Uacute;ltimos Reportes:</h1>
            <div class="row mb-5 mt-5">
                <div id="map-container"></div>
            </div>


            <div class="row mb-2">
                @foreach ($reports as $reportRecord)
                    <div class="col-md-6">
                        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-4 d-flex flex-column position-static">
                                <strong class="d-inline-block mb-2 text-primary-emphasis">{{ $reportRecord->type }}</strong>
                                <h3 class="mb-0">{{ $reportRecord->name }}</h3>
                                <div class="mb-1 text-body-secondary">{{ $reportRecord->date->diffForHumans() }}</div>
                                <p class="card-text mb-auto">{{ Str::of($reportRecord->description)->limit(50,'...',true) }}</p>
                                <a href="{{ route('reports.show',$reportRecord->id) }}" class="icon-link gap-1 icon-link-hover stretched-link">
                                    Ampliar reporte
                                    <svg class="bi"><use xlink:href="#chevron-right"/></svg>
                                </a>
                            </div>
                            <div class="col-auto d-none d-lg-block">
                                @foreach(json_decode($reportRecord->attachments) as $index=>$attachment)
                                    <img width="200" class="bd-placeholder-img" src="{{ route('report.image.show', [$reportRecord->id, $index,'thumb']) }}" />
                                    @break
                                @endforeach
                                {{--                            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(ceil($reportCount/$limit) >= 2)
            <div class="row">
                <div class="form-group mr-5">
                    Páginas
                </div>
                <div class="btn-group" id="row_paginator">

                    @for($i=0;$i<ceil($reportCount/$limit);$i++)
                        <a class="btn btn-sm @if($currentPage == $i+1)btn-primary @else btn-outline-primary  @endif"
                           href="{{ route('root') }}?page={{ $i+1 }}">
                            {{ $i+1 }}
                        </a>&nbsp;
                    @endfor
                </div>
            </div>
            @endif

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
    //var HOSTNAME_API = HOSTNAME + '/api/';

    // Map.
    var DEFAULT_ZOOM_MAP = 6;
    var DEFAULT_ZOOM_MARKER = 10;
    var DEFAULT_MIN_ZOOM_MAP = 6;
    var DEFAULT_MAX_ZOOM_MAP = 16;

    // Villa Hayes - Paraguay.
    var DEFAULT_LNG = -57.623807;
    var DEFAULT_LAT = -23.299114;
</script>
<script  id="loadMap" data_load_map=marker type="text/javascript" charset="utf-8">
</script>
@endpush

@push('scripts')
    <script type="module">

        $(document).ready(function(){
            let coordinates = new Array();
            coordinates['lng']  = DEFAULT_LNG;
            coordinates['lat'] = DEFAULT_LAT;
            mapaLocal = new MapaLocal(coordinates, 6, 'marker');

            @foreach ($reports as $index=>  $record)
                setMarkerToLocation({'lng':{{$record->longitude}},'lat':{{$record->latitude}} },{{ $record->id }}, "{{$record->name?($record->name.' ('.__($record->type).')'):__($record->type)}}",(DEFAULT_MAX_ZOOM_MAP-DEFAULT_MIN_ZOOM_MAP)/2,false,false)
                .on('click',clickZoom);
            @endforeach

        });
    </script>
@endpush
