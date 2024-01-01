@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="row text-center">
            <h4 class="display-3 text-center">Ayuda</h4>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <p>Esta sección está en construcción<br/>
            Cualquier consulta enviar a <a href="mailto:{{ config('app.mail_support') }}">Soporte</a></p>
        </div>
    </div>
@endsection
