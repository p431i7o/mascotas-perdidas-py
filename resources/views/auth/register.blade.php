@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row text-center">
        <h4 class="display-3 text-center">Registro</h4>
    </div>
</div>
<div class="container">
    <div class="row">
            <form method="POST" action="{{route('register')}}" class="form-signin needs-validation accordion">
                @csrf

                @if(Session::has('result'))
                    @if(Session::get('result'))

                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ nl2br(Session::get('message')) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @else
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ nl2br(Session::get('message')) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                    @endif
                @endif
                <div class="form-label-group">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Nombre completo" value="{{old('name')}}" name="name">
                    <label for="floatingInput">{{ __("Name") }}</label>
                </div>
                <div class="form-label-group">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{old('email')}}" name="email">
                    <label for="floatingInput">{{ __("Email address") }}</label>
                </div>
                <div class="form-label-group">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="{{ __("Password") }}" name="password">
                    <label for="floatingPassword">{{ __("Password") }}</label>
                </div>
                <div class="form-label-group">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="{{ __("Password Confirmation") }}" name="password_confirmation">
                    <label for="floatingPassword">{{ __("Password Confirmation") }}</label>
                </div>

                <div class="form-check mb-3">
                    <label>
                        <input type="checkbox" class="form-check-input" name="accept_term_conditions" >Acepto <a href="{{ route('legal') }}">Los terminos y condiciones</a>
                    </label>
                </div>
                <button class="g-recaptcha btn btn-lg btn-primary btn-block"
                data-sitekey="<?=config('app.captcha_public');?>"
                data-callback='onSubmit'
                data-action='submit'
                type="submit">Register</button>

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
    </div>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
       function onSubmit(token) {
         document.getElementById("demo-form").submit();
       }
     </script>
@endsection
@push('styles')
<style>
    .form-signin {
      width: 100%;
      max-width: 420px;
      padding: 15px;
      margin: auto;
    }

    .form-signin .form-floating:focus-within {
    z-index: 2;
    }

    .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    }
</style>
@endpush
