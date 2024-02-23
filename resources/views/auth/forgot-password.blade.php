@extends('layouts.default')
@section('content')

    <div class="container">
        <div class="row text-center">
            <h4 class="display-3 text-center">Recuperar Cuenta</h4>
            <p>Si ha olvidado la contraseña con la que se registró, no hay problema, le enviaremos un email con un enlace para poder cambiarla</p>
        </div>
        @if (session('status'))
          <div class="alert alert-info">{{session('status')}}</div>
        @endif
    </div>
    <div class="container">
        <div class="row">

            <form id="demo-form" method="POST" class="form-signin needs-validation accordion" action="{{ route('password.email') }}" >

                @csrf
                {{-- <input type="hidden" name="token" value="{{ $token }}">  --}}
                    <!-- <div class="">
                        <label for="firstName">Nombre Completo:</label>
                        <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
                            <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div> -->
                @if(session('error'))

                    <div class="alert alert-warning">{{ session('message') }}</div>
                @endif


                <div class="form-label-group">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Direcci&oacute;n de correo" required autofocus>
                    <label for="inputEmail">Correo electr&oacute;nico</label>
                    @error('email')
                        <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                  </div>

                <button class="g-recaptcha btn btn-lg btn-primary btn-block"
                data-sitekey="{{config('app.captcha_public')}}"
                data-callback='onSubmit'
                data-action='submit'>Cambiar contraseña</button>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
   function onSubmit(token) {
     document.getElementById("demo-form").submit();
   }
 </script>
@endpush
@push('styles')
<style type="text/css">

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
