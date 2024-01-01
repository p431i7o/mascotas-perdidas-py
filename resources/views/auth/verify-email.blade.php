@extends('layouts.default')
@section('content')
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                        <div class="card-body">
                            @if (session('status') == 'verification-link-sent')
                                <div class="alert alert-success" role="alert">

                                    Un nuevo enlace de verificacion de email te ha sido enviado!
                                </div>
                            @endif


                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
