@extends('layouts.default.default')
@push('meta-data')
    <meta property="og:url"           content="{{route('reports.show', $report->id)}}" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Mascotas Perdidas PY" />
    <meta property="og:description"   content="Reporta y encuentra mascotas perdidas en Paraguay" />
    <meta property="og:image"         content="{{Vite::asset('resources/img/temporal-logo-mascotas-perdidas-py-thumb.jpeg')}}" />

    <meta name="twitter:card" content="Reporte de Mascota {{ $report->type=='Perdido'?'Perdida':'Encontrada' }}">
    <meta name="twitter:title" content="Mascotas Perdidas PY">
    <meta name="twitter:description" content="Reporta mascotas perdidas y encontradas de manera gratuita en Paraguay.">
    <meta name="twitter:image" content="{{Vite::asset('resources/img/temporal-logo-mascotas-perdidas-py-thumb.jpeg')}}">
@endpush
@section('content')
    <div class="container">
        <div class="row text-center">
            {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
            <h4 class="display-3 text-center">{{ $report->name ?? '-- Sin nombre --' }} <small>[{{ $report->type }}]</small>
            </h4>

        </div>
    </div>
    <div class="container">
        @include('layouts.default.parts.messages')
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

            <div id="carouselOfPictures"  class="carousel slide carousel-dark mt-3 mb-5" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach (json_decode($report->attachments) as $index => $value)
                    <div class="carousel-item {{$index==0?'active':''}}">
                        <img  class="d-block w-100" src="{{ route('report.image.show', [$report->id, $index]) }}" alt=""/>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselOfPictures" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselOfPictures" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

{{--        <div class="row mt-3 mb-5">--}}
{{--            @foreach (json_decode($report->attachments) as $index => $value)--}}
{{--                <div class="col-xxl-4 col-xl-3 col-sm-12 col-md-6 mb-1">--}}
{{--                    <a target="_blank" href="{{ route('report.image.show', [$report->id, $index]) }}">--}}
{{--                        <img class="col-12" src="{{ route('report.image.show', [$report->id, $index]) }}" />--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
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
{{--                <tr>--}}
{{--                    <th>Latitud</th>--}}
{{--                    <td>{{ $report->latitude }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Longitud</th>--}}
{{--                    <td>{{ $report->longitude }}</td>--}}
{{--                </tr>--}}
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
        <div class="text-left mt-5">

            Compartir en: <a href="https://twitter.com/intent/tweet?text={{ urlencode("Mascotas Perdidas PY \n".$report->name .', Mascota '.($report->type=='Perdido'?'Perdida':'Encontrada')) }}&url={{ urlencode(route('reports.show', $report->id)) }}" class="btn btn-primary btn-sm" id="b"><i class="fa-brands fa-x-twitter"></i></a>
            <!-- Load Facebook SDK for JavaScript -->
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>

            <!-- Your share button code -->
            <div class="fb-share-button"
                 data-href="{{ route('reports.show',$report->id) }}"
                 data-layout="button_count">
            </div>
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
            <div class="row mt-1">
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
        var DEFAULT_MAX_ZOOM_MAP = 16;

        // Villa Hayes - Paraguay.
        var DEFAULT_LNG = {{ $report->longitude }}; // -57.623807;
        var DEFAULT_LAT = {{ $report->latitude }}; //-23.299114;
    </script>
@endpush
@push('scripts')
    <script type="module">
        $(document).ready(function() {
            let coordinates = new Array();
            coordinates['lng']  = {{ $report->longitude }};
            coordinates['lat'] = {{ $report->latitude }};
            mapaLocal = new MapaLocal(coordinates, 15, 'marker');

            var marker = setMarkerToLocation(coordinates, {{ $report->id }}, "{{ $report->name ?? __($report->type) }}", 12, false, false);

            marker.on('click', clickZoom);

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
