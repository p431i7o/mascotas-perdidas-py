@extends('layouts.default')
@section('content')

    <div class="container">
        <div class="row text-center">
            <h4 class="display-3 text-center">Recuperar Cuenta</h4>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <form id="demo-form" method="POST" class="form-signin needs-validation accordion" action="<?=route('password.update');?>" >

                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-label-group">
                    <input type="email" id="email" name="email" class="form-control"  required autofocus value="{{$request->email}}">
                    <label for="inputemail">Email</label>
                    @error('email')
                        <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-label-group">
                    <input type="password" id="password" name="password" class="form-control"  required autofocus>
                    <label for="inputpassword">Contraseña</label>
                    @error('password')
                        <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-label-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"  required autofocus>
                    <label for="inputpassword_confirmation">Repetir Contraseña</label>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>

                <button class="g-recaptcha btn btn-lg btn-primary btn-block"
                data-sitekey="<?=env('captcha_public');?>"
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
