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

class RegisterController extends RegisteredUserController
{
    protected string $redirectTo = '/home';

    public function create(Request $request): RegisterViewResponse
    {
        return parent::create($request);
    }

    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
    {
        return parent::store($request,$creator);
    }
}
