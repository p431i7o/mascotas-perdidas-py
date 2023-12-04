<?php

namespace App\Http\Controllers\Auth;

use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use App\Providers\RouteServiceProvider;

use Laravel\Fortify\Fortify;

class RegisterController extends RegisteredUserController{


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // public function __construct(StatefulGuard $guard)
    // {
    //     parent::__construct($guard);
    // }

    /**
     * Show the registration view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\RegisterViewResponse
     */
    public function create(Request $request): RegisterViewResponse
    {
        return parent::create($request);
    }

    /**
     * Create a new registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Laravel\Fortify\Contracts\RegisterResponse
     */
    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
    {

        return parent::store($request,$creator);
        // // return  parent::store($request,$creator);
        // event(new Registered($user = $creator->create($request->all())));

        // // $this->guard->login($user);

        // // return app(RegisterResponse::class);
        // //Se deshabilita el login automático al registrarse
        // //$this->guard()->login($user);
        // $request->session()->flash('result', true);
        // $request->session()->flash('message', 'Se ha registrado correctamente! para iniciar sesión, favor confirme desde el enlace que enviamos a su correo.');
        
        
        // $register_response =  app(RegisterResponse::class);
        // // dd($register_response);
        // return $register_response;
        // // return $request->wantsJson()
        // //             ? new JsonResponse([], 201)
        // //             : redirect()->intended(Fortify::redirects('register'));
        // //             // : redirect($this->redirectPath());
    }
}