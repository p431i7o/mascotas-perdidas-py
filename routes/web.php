<?php

use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Auth\RegisterController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Configuraciones de fortify
$limiter = config('fortify.limiters.login');
$enableViews = config('fortify.views', true);
$verificationLimiter = config('fortify.limiters.verification', '6,1');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('login');


Route::post('/login', [LoginController::class, 'store'])
    ->middleware(
        array_filter(
            [
                'guest:'.config('fortify.guard'),
                $limiter ? 'throttle:'.$limiter : null,
            ]
        )
    );

Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');


// Registration...
if (Features::enabled(Features::registration())) {
    if ($enableViews) {
        Route::get('/register', [RegisterController::class, 'create'])
            ->middleware(
                [
                    'guest:'.config('fortify.guard')
                ]
            )
            ->name('register');
    }

    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware(
            [
                'guest:'.config('fortify.guard')
            ]
        );
}

// Email Verification...
if (Features::enabled(Features::emailVerification())) {
    if ($enableViews) {
        Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
            ->name('verification.notice');
    }

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
        ->name('verification.send');

}

//Estas rutas de aca en adelante requiren que la cuenta este verificada
// Esto genera las rutas de login y verificacion
Route::middleware(['auth','verified'])->group(function(){
    Route::get('/home',[HomeController::class,'index'])->name('home');
    Route::get('/dashboard',[HomeController::class,'index'])->name('dashboard');
});