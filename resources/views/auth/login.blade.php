@extends('layouts.default')
@section('content')
        

        
        <main class="form-signin w-100 m-auto">
            <form method="POST" action="{{route('login')}}">
                @csrf
                {{-- <img class="mb-4" src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> --}}
                <h1 class="h3 mb-3 fw-normal">Iniciar Sesion</h1>
                @if (session('status'))
                    <div class="alert alert-info">{{session('status')}}</div>
                @endif
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{old('email')}}" name="email">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                    <label for="floatingPassword">Password</label>
                </div>

                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me" name="remember"> Remember me
                    </label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
                <a href="{{route('password.request')}}">Olvide mi contraseña</a>
                <p class="mt-5 mb-3 text-muted">&copy; 2017–2022</p>
            </form>
        </main>
@endsection

@push('styles')
<style>
    /* sign-in  */
html,body {
height: 100%;
}

/* body {
display: flex;
align-items: center;
padding-top: 40px;
padding-bottom: 40px;
background-color: #f5f5f5;
} */

.form-signin {
max-width: 420px;
padding: 15px;
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