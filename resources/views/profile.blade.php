@extends('layouts.default')

@section('content')
 <div class="container mt-5">
    <h1 class="display-4">Mi perfil</h1>
    @if(session('message'))

    <div class="row">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
        </div>
    </div>

    @endif
    <form action="{{ route('profile') }}" method="POST">
        @csrf
        <div class="form-group">
            <div class="col-md-3"><label>Email</label></div>
            <div class="col-md-9"><input class="form-control" name="email" type="email" value="{{ $user->email }}" maxlength="300" readonly></div>
        </div>

        <div class="form-group">
            <div class="col-md-3"><label>Nombre</label></div>
            <div class="col-md-9"><input class="form-control" name="name" type="text" value="{{ old('name',$user->name) }}" maxlength="255">
                @error('name')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-3"><label>Teléfono</label></div>
            <div class="col-md-9"><input class="form-control" name="phone" type="text" value="{{ old('phone',$user->phone) }}" maxlength="20">
                @error('phone')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-3"><label>Ciudad</label></div>
            <div class="col-md-9"><input class="form-control" name="city" type="text" value="{{ old('city',$user->city) }}" maxlength="250">
                @error('city')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        </div>

        <div class="form-group">
            <div class="col-md-3"><label>Contraseña <small>(completar solo para cambiar)</small></label></div>
            <div class="col-md-9"><input class="form-control" name="password" type="password" value="" >
                @error('password')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

        </div>

        <div class="form-group">
            <div class="col-md-3"><label>Confirme contraseña</label></div>
            <div class="col-md-9"><input class="form-control" name="password_confirmation" type="password" value="" >
                @error('password_confirm')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>

            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
 </div>
@endsection
