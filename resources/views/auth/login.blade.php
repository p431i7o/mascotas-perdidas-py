@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row text-center">
        <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4>
    </div>
</div>
<div class="container">
    <div class="row">
        <form method="POST" action="{{route('login')}}" class="form-signin needs-validation accordion">
            @csrf
            {{-- <img class="mb-4" src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> --}}

            @if (session('status'))
                <div class="alert alert-info">{{session('status')}}</div>
            @endif
            <div class="form-label-group">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{old('email')}}" name="email">
                <label for="floatingInput">{{ __("Email address") }}</label>
            </div>
            <div class="form-label-group">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                <label for="floatingPassword">{{ __("Password") }}</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me" name="remember"> {{ __("Remember me") }}
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">{{ __('Sign in') }}</button>
            <a href="{{route('password.request')}}">{{ __("Forgot your password?") }}</a>
            <p class="mt-5 mb-3 text-muted">&copy; {{ date("Y") }}</p>
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
