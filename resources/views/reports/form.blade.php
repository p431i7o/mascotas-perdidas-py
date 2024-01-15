@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row text-center">
        {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
        <h4 class="display-3 text-center">@if(empty($record->id)) {{__("New Report")}} @else {{__("Edit Report")}} @endif</h4>

    </div>
</div>
@if ($errors->any())
<div class="container">
    <div class="row">
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    </div>
</div>

@endif
<div class="container">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">

                <div class="card-body">


                    {{-- <div class="container"> --}}
                        <form enctype="multipart/form-data" id="form-report" method="POST" action="{{ empty($record->id)?route('reports.store'):route('reports.update',$record->id)}}">
                            @csrf
                            @if(!empty($record->id))
                                <input type="hidden" name="id" value="{{ $record->id }}"/>
                                @method('PUT')
                            @endif
                            <div class="form-group">
                              <label for="type" class="col-sm-12 col-form-label">Tipo de reporte</label>
                              <select class="form-control" name="type" id="type">
                                @foreach (['Lost','Found'] as  $option)
                                    <option value="{{$option}}" @if(old('type',$record->type)==$option) @selected(true) @endif>{{__($option)}}</option>))
                                @endforeach


                              </select>
                            </div>
                            <div class="form-group">
                              <label for="animal_kind_id">Tipo de Animal</label>
                              <select class="form-control" name="animal_kind_id" id="animal_kind_id">
                                @foreach ($kinds as $kind)
                                    <option value="{{$kind->id}}"  @if(old('type',$record->animal_kind_id)==$kind->id) @selected(true) @endif>{{$kind->name}}</option>

                                @endforeach
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="date">Fecha *</label>
                              <input required value="{{ old('date',$record->date) }}" type="datetime-local" class="form-control" name="date" id="date" aria-describedby="helpId" placeholder="Fecha en la que ocurrio">
                              <small id="helpId" class="form-text text-muted">Fecha en la que se perdio/encontró</small>
                            </div>
                            <div class="form-group ">
                                <label for="inputName" class="col-sm-12 col-form-label">{{__("Name")}} <small>({{__("Optional")}})</small></label>
                                {{-- <div class="col-12"> --}}
                                    <input type="text" value="{{ old('name',$record->name) }}" class="form-control" name="name" id="name" placeholder="{{__("Name")}} ">
                                {{-- </div> --}}
                                <small id="helpId" class="form-text text-muted">Si es una mascota perdida, a que nombre responde</small>
                            </div>

                            <div class="form-group">
                              <label for="description">Descripción</label>
                              <textarea class="form-control"
                                name="description"
                                id="description"
                                rows="3" placeholder="Describa datos del animal, caracteristicas únicas, donde se perdió/encontró">{{ old('description',$record->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg btn-block mb-1" onclick="send_marker()">
                                    Ubicar punto en el mapa
                                </button>
                                <div id="map-container"></div>
                                <label for="latitude">Ubicación <small>(Latitud/Longitud)</small></label>
                              <input type="text" value="{{ old('latitude',$record->latitude) }}" class="form-control col-12" name="latitude" id="latitude" aria-describedby="latitudHelpId" placeholder="Latitud">
                              <small id="latitudHelpId" class="form-text text-muted">Haga click en el mapa</small>
                              <input type="text" value="{{ old('longitude',$record->longitude) }}" class="form-control col-12" name="longitude" id="longitude" aria-describedby="longitudHelpId" placeholder="Longitud">
                              <small id="longitudHelpId" class="form-text text-muted">Haga click en el mapa</small>


                            </div>

                            <div class="form-group">
                              <label for="address" class="col-sm-12 col-form-label">{{__("Address")}} <small>({{__("Optional")}})</small></label>
                              <input type="text" value="{{ old('address',$record->address) }}" class="form-control" name="address" id="address" aria-describedby="helpId" placeholder="{{__("Address")}}">
                              <small id="helpId" class="form-text text-muted">Direccion aproximada</small>
                            </div>

                            <div class="form-group">
                              <label for="pictures[]">Imagenes del animal</label>
                              <input type="file" multiple class="form-control-file" name="pictures[]" id="pictures[]" placeholder="seleccione una foto para subir" aria-describedby="pictureHelpId">
                              <small id="pictureHelpId" class="form-text text-muted">Seleccione hasta 3 imagenes para subir</small>
                            </div>

                            <div class="form-group row">
                                    <div class="alert alert-warning d-none" id="errores"></div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block mb-1" onclick="validarYGuardar();">{{__("Save") }}</button>

                            </div>
                        </form>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pre-scripts')
<script type="text/javascript">
    var HOSTNAME = '{{url('/')}}';
    var HOSTNAME_API = HOSTNAME + '/api/';

    // Map.
    var DEFAULT_ZOOM_MAP = 6;
    var DEFAULT_ZOOM_MARKER = 16;
    var DEFAULT_MIN_ZOOM_MAP = 6;
    var DEFAULT_MAX_ZOOM_MAP = 20;

    // Villa Hayes - Paraguay.
    var DEFAULT_LNG = -57.623807;
    var DEFAULT_LAT = -23.299114;
</script>
<script  id="loadMap" data_load_map=marker type="text/javascript" charset="utf-8">
    window.onload = function()
        {
            var action = document.getElementById("loadMap").getAttribute("data_load_map");
            // debugger;
            localization(action);


        };
</script>
@endpush

@push('scripts')

    <script type="text/javascript">
        function clickZoom(e) {
            map.map.setView(e.target.getLatLng(),DEFAULT_ZOOM_MARKER);
            // map.map.setZoom(15);
        }
        @if(!empty($record->id))
            //@TODO: ENCONTRAR EL EVENTO CORRECTO DE LEAFTLET PARA SETEAR el marcador
           setTimeout(function(){
            var marker = L.marker([{{$record->latitude }}, {{$record->longitude}} ],{
                id:{{ $record->id }},
                draggable: 'true',
            }).addTo(map.map).bindPopup("{{$record->name??__($record->type)}}").on('dragend',ondragend);
           },1500)

        @endif

        function send_marker (){
            marker_point_map(event, ((gps_active)? DEFAULT_ZOOM_MARKER : DEFAULT_ZOOM_MAP))
        }
    </script>
    <script>
        function validarYGuardar(){
            var esValido = true;
            var tipo_reporte = $('#type').val();
            var tipo_animal = $('#animal_kind_id').val();
            // var reporte_mascota_nombre = $('#reporte_mascota_nombre').val();
            var reporte_descripcion = $('#description').val();
            var latitud = $('#latitude').val();
            var longitud = $('#longitude').val();

            var reporte_fecha = $('#date').val();

            var errores = []
            if(tipo_reporte==null || tipo_reporte ==""){
                esValido = false;
                errores.push('<li>Debe elegir un tipo de reporte</li>');
            }

            if(tipo_animal==null || tipo_animal ==""){
                esValido = false;
                errores.push('<li>Debe elegir un tipo de animal</li>');
            }

            if(reporte_descripcion==null || reporte_descripcion ==""){
                esValido = false;
                errores.push('<li>Debe cargar una descripción</li>');
            }

            if(reporte_fecha==null || reporte_fecha ==""){
                esValido = false;
                errores.push('<li>Debe cargar la fecha en la que se encontró/perdió el animal</li>');
            }

            if(latitud==null || latitud == "" || longitud == null || longitud == ""){
                esValido = false;
                errores.push("<li>Debe elegir un punto en el mapa</li>");
            }

            var $fileUpload = $("input[type='file']");
            if (parseInt($fileUpload.get(0).files.length)>3){
                esValido = false;
                errores.push("<li>3 Imágenes es el límite de imágenes a subir</li>");
            }

            if(!esValido){
                $('#errores').html('<ul>'+(errores.join(' '))+'</ul>');
                $('#errores').removeClass('d-none');
            }else{
                $('#errores').addClass('d-none');
                $('#form-report').submit();
            }


        }
    </script>


@endpush

@push('styles')
    <style>
        #map-container { height: 600px; width: 100%; }
    </style>
@endpush
