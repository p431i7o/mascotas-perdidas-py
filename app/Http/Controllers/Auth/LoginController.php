<?php

namespace App\Http\Controllers\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\LogoutResponse;

class LoginController extends AuthenticatedSessionController
{

    public function store(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        return parent::store($request);
    }


    public function create(Request $request): LoginViewResponse
    {
        return parent::create($request);
    }

    public function destroy(Request $request): LogoutResponse
    {
        return parent::destroy($request);
    }
}
