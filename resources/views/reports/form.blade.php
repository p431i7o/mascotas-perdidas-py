@extends('layouts.default.default')
@section('content')
<div class="container">
    <div class="row text-center">
        <h4 class="display-3 text-center mt-5">@if(empty($record->id)) {{__("New Report")}} @else {{__("Edit Report")}} @endif</h4>
    </div>
</div>
@include('layouts.default.parts.messages')
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
                    <form enctype="multipart/form-data" id="form-report" method="POST" action="{{ empty($record->id)?route('reports.store'):route('reports.update',$record->id)}}">
                        @csrf
                        @if(!empty($record->id))
                            <input type="hidden" name="id" value="{{ $record->id }}"/>
                            @method('PUT')
                        @endif
                        <div class="form-group">
                          <label for="type" class="col-sm-12 col-form-label">
                              <strong>Tipo de reporte *</strong>
                          </label>
                          <select class="form-select" name="type" id="type">
                            @foreach (['Lost','Found'] as  $option)
                                <option value="{{$option}}" @if(__(old('type',$record->type))==__($option)) @selected(true) @endif>{{__($option)}}</option>))
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="animal_kind_id">
                                <strong>Tipo de animal *</strong>
                            </label>
                            <select class="form-select" name="animal_kind_id" id="animal_kind_id">
                            @foreach ($kinds as $kind)
                                  <option value="{{$kind->id}}"  @if(old('type',$record->animal_kind_id)==$kind->id) @selected(true) @endif>
                                      {{$kind->name}} (Por ejemplo: {{$kind->example}})
                                  </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="date">
                              <strong>Fecha *</strong>
                          </label>
                          <input required value="{{ old('date',$record->date) }}" type="datetime-local" max="{{ Carbon\Carbon::now() }}" class="form-control" name="date" id="date" aria-describedby="helpId" placeholder="Fecha en la que ocurrio">
                          <small id="helpId" class="form-text text-muted">
                              Fecha en la que se perdio/encontró la mascota
                          </small>
                        </div>
                        <div class="form-group ">
                            <label for="name" class="col-sm-12 col-form-label">
                                <strong>{{__("Name")}}</strong>
                                <small>({{__("Optional")}})</small>
                            </label>
                                <input type="text" value="{{ old('name',$record->name) }}" class="form-control" name="name" id="name" placeholder="Nombre de la mascota "/>
                            <small id="helpId" class="form-text text-muted">
                                Si es una mascota perdida, a que nombre responde
                            </small>
                        </div>

                        <div class="form-group">
                          <label for="description">
                              <strong>{{__("Description")}} *</strong>
                          </label>
                          <textarea class="form-control"
                            name="description"
                            id="description"
                            rows="3" placeholder="Describa datos del animal, caracteristicas únicas, donde se perdió/encontró. Además de alguna forma de contacto (telefono,mail,etc)">{{ old('description',$record->description) }}</textarea>
                            <small id="helpId" class="form-text text-muted">
                                Procure dejar la mayor cantidad de información como para que el reencuentro sea más fácil
                            </small>
                        </div>
                        <div class="form-group mt-2">
                            <label for="address" class="col-sm-12 col-form-label">
                                <strong>{{__("Address")}} <small>*</small></strong>
                            </label>
                            <input type="text" required value="{{ old('address',$record->address) }}" class="form-control" name="address" id="address" aria-describedby="helpId" placeholder="Calle principal, numero, intersección más cercana">
                        </div>
                        <div class="form-group">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary btn-lg btn-block mt-5 mb-1" onclick="send_marker()">
                                    Ubicar punto en el mapa
                                </button>

                            </div>
                            <div id="map-container" class="hidden"></div>
                            <div class="row d-none">
                                <div class="col-6">
                                    <label for="latitude"><small>Latitud</small></label>
                                    <input type="text" value="{{ old('latitude',$record->latitude) }}" class="form-control col-12" name="latitude" id="latitude" aria-describedby="latitudHelpId" placeholder="Latitud">
                                </div>
                                <div class="col-6">
                                    <small>Longitud</small>
                                    <input type="text" value="{{ old('longitude',$record->longitude) }}" class="form-control col-12" name="longitude" id="longitude" aria-describedby="longitudHelpId" placeholder="Longitud">
                                </div>
                            </div>
                        </div>


                        @if(!$record->id)
                        <div class="form-group mt-4">
                          <label for="pictures[0]">
                              <strong>Imágenes del animal *</strong>
                              <small id="helpId">(Al menos una foto es requerida)</small>
                          </label>
                            @for($i = 0; $i < config('app.number_of_pictures'); $i++)
                                <div class="mb-3">
                                    <input type="file"  class="form-control" name="pictures[]" id="pictures[{{$i}}]" placeholder="seleccione una foto para subir" aria-describedby="pictureHelpId" accept="{{implode(', ',array_map(function($type){return ".{$type}";},explode(',',config('app.allowed_picture_extensions'))))}}">
                                </div>
                            @endfor
                          <small id="pictureHelpId" class="form-text text-muted">
                              Seleccione hasta {{config('app.number_of_pictures')}} imágenes para subir
                          </small>
                        </div>
                        @endif
                        @if(!auth()->user())
                            <div class="form-group mb-3">
                                <div class="col-12">
                                    <strong>Dirección de correo electronico *</strong>
                                    <input type="email" class="form-control" maxlength="255" name="email" id="email" required placeholder="juan.perez@gmail.com" value="{{old('email',$record->email)}}" @if(!auth()->user() && isset($record->email)) readonly @endif)/>
                                    <small id="emailHelp" class="form-text text-muted">Este email se usará para enviar un email de confirmación para dar de alta la publicación, ademas de que pueda editar o renovar su cuando esta alcance su vencimiento.</small>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="alert alert-warning d-none" id="errores"></div>
                            <button type="button" class="g-recaptcha btn btn-primary btn-lg btn-block mb-1"
                                    data-sitekey="{{config('app.captcha_public')}}"
                                    data-callback='validateAndSave'
                                    data-action='submit'>
                                {{__("Save") }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js"></script>
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


        var DEFAULT_LNG = -57.623807;
        var DEFAULT_LAT = -23.299114;
    </script>
    {{--<script  id="loadMap" data_load_map=marker type="text/javascript" charset="utf-8">--}}
    </script>
@endpush
@push('scripts')
    <script type="module">
        $(document).ready(function(){
            if(getCookie("informedAboutLocation")==""){
                Swal.fire({
                    title: "Desea compartir su ubicación?",
                    text:"La ubicación es utilizada para poder ubicar el punto en el mapa, si elige denegar igual puede arrastrar el marcador hasta la ubicación correcta",
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: "Entiendo",

                }).then((result) => {
                    if (result.isConfirmed) {
                        setCookie("informedAboutLocation","yes");
                        localization(action);
                        drawMap();
                    }
                });
            }else{
                localization(action);
                drawMap();
            }
        });

        function drawMap(){
            let action = "marker";
            let coordinates = new Array();
            coordinates['lng']  = DEFAULT_LNG;
            coordinates['lat'] = DEFAULT_LAT;
            window.mapaLocal = new MapaLocal(coordinates, 6, action);
        }
        $(document).ready(function(){
            @if(!empty($record->id))
                setMarkerToLocation({'lng':{{$record->longitude}},'lat':{{$record->latitude}}},{{$record->id}},"{{$record->name??__($record->type)}}",15,false,true);
            @endif
        });

        window.send_marker = function(){
            if($('#map-container').hasClass('d-none')){
                $('#map-container').removeClass('d-none');
            }
            setToMyLocation();
        }

        window.validateAndSave = function(){
            let isValid = true;
            let reportKind = $('#type').val();
            let animalType = $('#animal_kind_id').val();

            let reportDescription = $('#description').val();
            let latitude = $('#latitude').val();
            let longitude = $('#longitude').val();

            let reportDate = $('#date').val();
            let address = $('#address').val();

            let errors = []
            if(reportKind==null || reportKind ==""){
                isValid = false;
                errors.push('<li>Debe elegir un tipo de reporte</li>');
            }

            if(animalType==null || animalType ==""){
                isValid = false;
                errors.push('<li>Debe elegir un tipo de animal</li>');
            }

            if(reportDescription==null || reportDescription ==""){
                isValid = false;
                errors.push('<li>Debe cargar una descripción</li>');
            }

            if(address==null || address ==""){
                isValid = false;
                errors.push('<li>Debe cargar una dirección aproximada</li>');
            }

            if(reportDate==null || reportDate ==""){
                isValid = false;
                errors.push('<li>Debe cargar la fecha en la que se encontró/perdió el animal</li>');
            }

            if(latitude==null || latitude == "" || longitude == null || longitude == ""){
                isValid = false;
                errors.push("<li>Debe elegir un punto en el mapa</li>");
            }

            @if(!$record->id)
            let fileUpload = $("input[type='file']");
            if (parseInt(fileUpload.get(0).files.length)>{{config('app.number_of_pictures')}}){
                isValid = false;
                errors.push("<li>{{config('app.number_of_pictures')}} Imágenes es el límite de imágenes a subir</li>");
            }else if(parseInt(fileUpload.get(0).files.length)==0){
                isValid = false;
                errors.push("<li>Debe subir almenos una imagen</li>");
            }
            @endif
            @if(!auth()->user())
                let email = $('#email').val();
                if(email==null || email ==""){
                    isValid = false;
                    errors.push('<li>Debe ingresar una dirección de correo electrónico</li>');
                }
            @endif

            if(!isValid){
                $('#errores').html('<ul>'+(errors.join(' '))+'</ul>');
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
