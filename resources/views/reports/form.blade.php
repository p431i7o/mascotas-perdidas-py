@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                
                <div class="card-body">
                    <h5 class="card-tile">@if(empty($record->id)) {{__("New Report")}} @else {{__("Edit Report")}} @endif</h5>
                    
                    {{-- <div class="container"> --}}
                        <form method="POST" action="{{ empty($record->id)?route('reports.store'):route('reports.update',$record->id)}}">
                            @csrf
                            @if(!empty($record->id))
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
                                    <option value="{{$kind->id}}">{{$kind->name}}</option>

                                @endforeach
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="date">Fecha *</label>
                              <input required type="datetime-local" class="form-control" name="date" id="date" aria-describedby="helpId" placeholder="Fecha en la que ocurrio">
                              <small id="helpId" class="form-text text-muted">Fecha en la que se perdio/encontró</small>
                            </div>
                            <div class="form-group ">
                                <label for="inputName" class="col-sm-12 col-form-label">{{__("Name")}} <small>({{__("Optional")}})</small></label>
                                {{-- <div class="col-12"> --}}
                                    <input type="text" class="form-control" name="name" id="name" placeholder="{{__("Name")}} ">
                                {{-- </div> --}}
                                <small id="helpId" class="form-text text-muted">Si es una mascota perdida, a que nombre responde</small>
                            </div>

                            <div class="form-group">
                              <label for="description">Descripción</label>
                              <textarea class="form-control" 
                                name="description" 
                                id="description" 
                                rows="3" placeholder="Describa datos del animal, caracteristicas únicas, donde se perdió/encontró"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg btn-block mb-1" onclick="send_marker()">
                                    Ubicar punto en el mapa
                                </button>
                                <div id="map-container"></div>
                                <label for="latitude">Ubicación <small>(Latitud/Longitud)</small></label>
                              <input type="text" class="form-control col-6" name="latitude" id="latitude" aria-describedby="latitudHelpId" placeholder="Latitud">
                              <small id="latitudHelpId" class="form-text text-muted">Haga click en el mapa</small>
                              <input type="text" class="form-control col-6" name="longitude" id="longitude" aria-describedby="longitudHelpId" placeholder="Longitud">
                              <small id="longitudHelpId" class="form-text text-muted">Haga click en el mapa</small>
                              
                              
                            </div>

                            <div class="form-group">
                              <label for="address" class="col-sm-12 col-form-label">{{__("Address")}} <small>({{__("Optional")}})</small></label>
                              <input type="text" class="form-control" name="address" id="address" aria-describedby="helpId" placeholder="{{__("Address")}}">
                              <small id="helpId" class="form-text text-muted">Direccion aproximada</small>
                            </div>

                            <div class="form-group">
                              <label for="pictures[]">Imagenes del animal</label>
                              <input type="file" multiple class="form-control-file" name="pictures[]" id="pictures[]" placeholder="seleccione una foto para subir" aria-describedby="pictureHelpId">
                              <small id="pictureHelpId" class="form-text text-muted">Seleccione hasta 3 imagenes para subir</small>
                            </div>

                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">{{__("Save") }}</button>
                                </div>
                            </div>
                        </form>
                    {{-- </div> --}}
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

            localization(action);
        };
</script>
@endpush

@push('scripts')
    <script type="text/javascript">
        function send_marker (){
            marker_point_map(event, ((gps_active)? DEFAULT_ZOOM_MARKER : DEFAULT_ZOOM_MAP))
        }
    </script>
    <script>
        
    </script>

    
@endpush

@push('styles')
    <style>
        #map-container { height: 600px; width: 100%; }
    </style>
@endpush