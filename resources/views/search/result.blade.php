@extends('layouts.default.default')
@push('styles')
    <style>
        #map-container { height: 600px; width: 100%; }
    </style>
@endpush
@section('content')
@if($results->count() > 0)
    <div class="container">
        <div class="row mb-5">
            <div id="map-container"></div>
        </div>
        <h1 class="display-4">Resultados:</h1>
        <div class="row mb-2">
            @foreach ($results as $reportRecord)
                <div class="col-md-6" >
                    <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 h-lg-250 position-relative p-2" style="min-height: 200px;">
                        <div class="col-lg-8 col-sm-12 col-md-6 d-flex flex-column position-static">
                            <strong class="d-inline-block mb-2 text-primary">{{ $reportRecord->type }}</strong>
                            <h3 class="mb-0">{{ $reportRecord->name??'--' }}</h3>
                            <div data-bs-placement="top" data-bs-toggle="tooltip" data-bs-title="{{ $reportRecord->date->format("d/m/Y") }}"   class="mb-1 text-body-secondary">{{ $reportRecord->date->diffForHumans() }}</div>
                            <p class="card-text mb-auto">{{ Str::of($reportRecord->description)->limit(200,'...',true) }}</p>
                            <a href="{{ route('reports.show',$reportRecord->id) }}" class="">
                                Ampliar reporte
                                <i class="fa-solid fa-circle-chevron-right"></i>
                            </a>
                        </div>
                        <div class="col-lg-4 col-sm-12 col-md-6">
                            @foreach(json_decode($reportRecord->attachments) as $index=>$attachment)
                                <img class="bd-placeholder-img w-100" src="{{ route('report.image.show', [$reportRecord->id, $index,'thumb']) }}" />
                                @break
                            @endforeach
                        </div>
                    </div>
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
    var DEFAULT_MAX_ZOOM_MAP = 16;

    // Villa Hayes - Paraguay.
    var DEFAULT_LNG = -57.623807;
    var DEFAULT_LAT = -23.299114;
</script>

</script>
@endpush

@push('scripts')
    <script type="module">
        //window.onload = function(){};
        $(document).ready(function(){


            let coordinates = new Array();
            coordinates['lng']  = DEFAULT_LNG;
            coordinates['lat'] = DEFAULT_LAT;
            mapaLocal = new MapaLocal(coordinates, 6, 'marker');

            @foreach ($results as $index=>  $record)
            setMarkerToLocation({
                    'lng':{{$record->longitude}},
                    'lat':{{$record->latitude}} },
                {{ $record->id }},
                "<a href=\"{{route('reports.show',$record->id)}}\" target='_blank'>{{$record->name?($record->name.' ('.__($record->type).')'):__($record->type)}}</a>",(DEFAULT_MAX_ZOOM_MAP-DEFAULT_MIN_ZOOM_MAP)/2,false,false)
                .on('click',clickZoom);
            @endforeach

        });
    </script>
@endpush
