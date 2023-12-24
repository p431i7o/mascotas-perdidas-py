@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                
                <div class="card-body">
                    <h5 class="card-tile">@if(empty($record->id)) {{__("New Report")}} @else {{__("Edit Report")}} @endif</h5>
                    
                    {{-- <div class="container"> --}}
                        <form method="POST" action="{{ empty($record->id)?route('reports.store'):route('reports.update',$record->id)}}">
                            <div class="form-group">
                              <label for="type" class="col-sm-12 col-form-label">Tipo de reporte</label>
                              <select class="form-control" name="type" id="type">
                                <option value="Lost">Perdido</option>
                                <option value="Found">Encontrado</option>
                                
                              </select>
                            </div>
                            <div class="form-group ">
                                <label for="inputName" class="col-sm-12 col-form-label">{{__("Name")}}</label>
                                {{-- <div class="col-12"> --}}
                                    <input type="text" class="form-control" name="name" id="name" placeholder="{{__("Name")}} {{__("Optional")}}">
                                {{-- </div> --}}
                                <small id="helpId" class="form-text text-muted">Si es una mascota perdida, a que nombre responde</small>
                            </div>

                            <div class="form-group">
                              <label for="address" class="col-sm-12 col-form-label">{{__("Address")}}</label>
                              <input type="text" class="form-control" name="address" id="address" aria-describedby="helpId" placeholder="{{__("Address")}}">
                              <small id="helpId" class="form-text text-muted">Direccion aproximada</small>
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