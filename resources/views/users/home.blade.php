@extends('layouts.default')

@section('content')
 <div class="container mt-5">
    <div class="row">
        <div class="card-columns">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title">Mis reportes</h4>
                    <p class="card-text"><a href="{{route('reports.index')}}">Ver mis reportes</a></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mis mensajes</h4>
                    <p class="card-text"><a href="#">Ver mis mensajes</a></p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mi perfil</h4>
                    <p class="card-text"><a href="#">Editar mi perfil</a></p>
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection