@extends('layouts.default')
@section('content')
    <div class="container">
        <div class="row text-center">
            {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
            <h4 class="display-3 text-center">{{ $report->name ?? '-- Sin nombre --' }} <small>[{{ $report->type }}]</small>
            </h4>

        </div>
    </div>
    <div class="container">
        <div class="row">
            @if(session('message'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        @auth
            @if(Auth::user()->id != $report->user_id)
                <div class="row">
                    <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa-regular fa-envelope"></i>
                        Contactar por este reporte
                    </a>
                </div>
            @endif
        @else
            <div class="row">
                <p><a href="{{ route('register') }}">Registrarse</a> o <a href="{{route('login')}}"> Iniciar sesion</a> para contactar <i class="fa-regular fa-envelope"></i> por este reporte</p>
            </div>
        @endauth

        <div class="row mt-3 mb-5">
            @foreach (json_decode($report->attachments) as $index => $value)
                <div class="col-xxl-4 col-xl-3 col-sm-12 col-md-6 mb-1">
                    <a target="_blank" href="{{ route('report.image.show', [$report->id, $index]) }}">
                        <img class="col-12" src="{{ route('report.image.show', [$report->id, $index]) }}" />
                    </a>
                </div>
            @endforeach
        </div>
        <div class="row">
            <table class="table col-sm-12 col-md-4">
                <tr>
                    <th>Nombre</th>
                    <td>{{ $report->name }}</td>
                </tr>
                <tr>
                    <th>Departamento</th>
                    <td>{{ $report->Department()->first()->name??'--' }}</td>
                </tr>
                <tr>
                    <th>Ciudad</th>
                    <td>{{ $report->City()->first()->name??'--' }}</td>
                </tr>
                <tr>
                    <th>Barrio</th>
                    <td>{{ $report->Neighborhood()->first()->name??'--' }}</td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td>{{ $report->address }}</td>
                </tr>
            </table>
            <div class="col-sm-12 col-md-6">
                <strong>Descripción</strong><br />
                {{ $report->description }}<br />
                <strong>Fecha</strong><br />
                {{ $report->date->isoFormat('DD/MMM/YYYY HH:mm') }}<br />
            </div>
        </div>
        <hr />
        <div class="row">
            <div id="map-container"></div>
        </div>
        @auth
            @if(Auth::user()->id != $report->user_id)
                <div class="row mt-5">
                    <button type="button" class="btn btn-danger" onclick="denounceReport()">
                        <i class="fa-solid fa-flag"></i> Denunciar este reporte
                    </button>
                </div>
            @endif
        @else
            <div class="row">
                <p><a href="{{ route('register') }}">Registrarse</a> o <a href="{{route('login')}}"> Iniciar sesion</a> para denunciar <i class="fa-solid fa-flag"></i> este reporte</p>
            </div>
        @endauth
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Contactar por este reporte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="application/x-www-form-urlencoded" method="POST" action="{{ route('messages.store') }}">
                        @csrf
                        <input type="hidden" name="report_id" value="{{$report->id}}"/>
                        <div class="row">
                            <textarea class="form-control" rows="3" name="message" placeholder="Escriba aqui su mensaje"></textarea>
                        </div>
                        <div class="row mt-5">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
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
        var DEFAULT_ZOOM_MARKER = 13;
        var DEFAULT_MIN_ZOOM_MAP = 6;
        var DEFAULT_MAX_ZOOM_MAP = 20;

        // Villa Hayes - Paraguay.
        var DEFAULT_LNG = {{ $report->longitude }}; // -57.623807;
        var DEFAULT_LAT = {{ $report->latitude }}; //-23.299114;
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
        function clickZoom(e) {
            map.map.setView(e.target.getLatLng(), DEFAULT_ZOOM_MARKER);
        }

        $(document).ready(function() {

            var action = document.getElementById("loadMap").getAttribute("data_load_map");
            let coordinates = new Array();
            coordinates['lng']  = {{ $report->longitude }};
            coordinates['lat'] = {{ $report->latitude }};
            map = new Map(coordinates, 15, 'marker');
            var marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
                id: {{ $report->id }},
                draggable: false,
            }).addTo(map.map).bindPopup("{{ $report->name ?? __($report->type) }}").on('click', clickZoom);

        });
    </script>
    <script type="module">

        window.denounceReport = async function(){

            const { value: text } = await Swal.fire({
                icon: "warning",
                input: "textarea",
                inputLabel: "Denunciar Reporte",
                cancelButtonText:'Cancelar',
                confirmButtonText:'Listo! Enviar',
                inputPlaceholder: "Por qué no debería esto estar aquí...",
                inputAttributes: {
                    "aria-label": "Por qué no debería esto estar aquí"
                },
                showCancelButton: true
            });
            if (text) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('report.denounce',$report->id) }}",
                    data: {
                        comment:text,
                        _token:'{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.success){
                            Swal.fire("Denuncia recibida!");
                        }else{
                            Swal.fire("Hubo un problema al procesar su envío, reintente en unos minutos")
                        }
                    }
                });
            }
        }
    </script>
@endpush
