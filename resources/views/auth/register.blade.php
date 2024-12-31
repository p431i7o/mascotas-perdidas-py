@extends('layouts.default.default')
@section('content')
<div class="container">
    <div class="row text-center">
        <h4 class="display-3 text-center">Registro</h4>
    </div>
</div>
@include('layouts.default.parts.messages')
<div class="container">

    <div class="row text-center">
        <div class="">
            <form id="register-form" method="POST" action="{{route('register')}}" class="form-signin needs-validation accordion">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="text-align: left;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @csrf

{{--                @if (session('status'))--}}
{{--                    <div class="alert alert-info">{{session('status')}}</div>--}}
{{--                @endif--}}
                <div class="form-label-group">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Nombre completo" value="{{old('name')}}" name="name" maxlength="255">
                    <label for="floatingInput">{{ __("Full name") }}</label>
                </div>
                <div class="form-label-group">
                    <input type="email" class="form-control" id="floatingInputEmail" placeholder="nombre@ejemplo.com" value="{{old('email')}}" name="email" maxlength="300">
                    <label for="floatingInputEmail">{{ __("Email address") }}</label>
                </div>
{{--                 <div class="form-label-group">--}}
{{--                    <input type="phone" class="form-control" id="floatingInputPhone" placeholder="09xx555123" value="{{old('phone')}}" name="phone" maxlength="20">--}}
{{--                    <label for="floatingInputPhone">{{ __("Phone") }}</label>--}}
{{--                </div> --}}
{{--                <div class="form-group">--}}
{{--                    <label for="floatingInputCity">{{ __("City") }}</label>--}}
{{--                    <select id="floatingInputCity" class="form-control" name="city">--}}
{{--                        @foreach ($cities as $city)--}}
{{--                            <option value="{{ $city->name }}">{{ $city->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    --}}{{-- <input type="text" class="form-control" id="floatingInputCity" placeholder="por ej Asunción" value="{{old('city')}}" name="city" maxlength="250"> --}}
{{--                </div>--}}

                <div class="form-label-group">
                    <input id="password" type="password" class="form-control" id="floatingPassword" placeholder="{{ __("Password") }}" name="password">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                    <label for="floatingPassword">{{ __("Password") }}</label>
                </div>
                <div class="form-label-group">
                    <input type="password" class="form-control" id="floatingPassword2" placeholder="{{ __("Password Confirmation") }}" name="password_confirmation">
                    <i class="bi bi-eye-slash" id="togglePasswordConfirm"></i>
                    <label for="floatingPassword2">{{ __("Password Confirmation") }}</label>
                </div>

                <div class="form-check mb-3">
                    <label>
                        <input type="checkbox" class="form-check-input" name="accept_term_conditions" >Acepto <a target="_blank" href="{{ route('legal') }}">Los terminos y condiciones</a>
                    </label>
                </div>
                <button class="g-recaptcha btn btn-lg btn-primary btn-block"
                data-sitekey="{{config('app.captcha_public')}}"
                data-callback='onSubmit'
                data-action='submit'
                type="submit">Registrarme!</button>
            </form>
        </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
       function onSubmit(token) {
         document.getElementById("register-form").submit();
       }
     </script>
<script type="module">
    $(document).ready(function() {
        $('#togglePassword').on('click', function() {
            $(this).toggleClass('bi-eye bi-eye-slash');
            $(this).parent().find("input").attr('type') === 'password' ? $(this).parent().find("input").attr('type','text') : $(this).parent().find("input").attr('type','password');
        });
        $('#togglePasswordConfirm').on('click', function() {
            $(this).toggleClass('bi-eye bi-eye-slash');
            $(this).parent().find("input").attr('type') === 'password' ? $(this).parent().find("input").attr('type','text') : $(this).parent().find("input").attr('type','password');
        });
    });

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

    .form-label-group {
      position: relative;
      margin-bottom: 1rem;
    }

    .form-label-group > input,
    .form-label-group > label {
      height: 3.125rem;
      padding: .75rem;
    }

    .form-label-group > label {
      position: absolute;
      top: 0;
      left: 0;
      display: block;
      width: 100%;
      margin-bottom: 0; /* Override default `<label>` margin */
      line-height: 1.5;
      color: #495057;
      pointer-events: none;
      cursor: text; /* Match the input under the label */
      border: 1px solid transparent;
      border-radius: .25rem;
      transition: all .1s ease-in-out;
    }

    .form-label-group input::-webkit-input-placeholder {
      color: transparent;
    }

    .form-label-group input:-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-moz-placeholder {
      color: transparent;
    }

    .form-label-group input::placeholder {
      color: transparent;
    }

    .form-label-group input:not(:placeholder-shown) {
      padding-top: 1.25rem;
      padding-bottom: .25rem;
    }

    .form-label-group input:not(:placeholder-shown) ~ label {
      padding-top: .25rem;
      padding-bottom: .25rem;
      font-size: 12px;
      color: #777;
    }

    .form-label-group  > i{
        position: absolute;
        padding: 0.75rem;
        top: 0;
        right: 10px;
        cursor: pointer;
    }

    /* Fallback for Edge
    -------------------------------------------------- */
    @supports (-ms-ime-align: auto) {
      .form-label-group > label {
        display: none;
      }
      .form-label-group input::-ms-input-placeholder {
        color: #777;
      }
    }

    /* Fallback for IE
    -------------------------------------------------- */
    @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
      .form-label-group > label {
        display: none;
      }
      .form-label-group input:-ms-input-placeholder {
        color: #777;
      }
    }
</style>
@endpush
