<?php

use Laravel\Fortify\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ReportsController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use phpDocumentor\Reflection\Types\Resource_;

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

Route::get('/', [HomeController::class, 'root'])->name('root');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/login', [LoginController::class, 'create'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('login');


Route::post('/login', [LoginController::class, 'store'])
    ->middleware(
        array_filter(
            [
                'guest:' . config('fortify.guard'),
                $limiter ? 'throttle:' . $limiter : null,
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
                    'guest:' . config('fortify.guard')
                ]
            )
            ->name('register');
    }

    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware(
            [
                'guest:' . config('fortify.guard')
            ]
        );
}

// Email Verification...
if (Features::enabled(Features::emailVerification())) {
    if ($enableViews) {
        Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
            ->name('verification.notice');
    }

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'signed', 'throttle:' . $verificationLimiter])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'throttle:' . $verificationLimiter])
        ->name('verification.send');
}

if(Features::enabled(Features::resetPasswords())){

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware('guest')->name('password.request');

    Route::get('/reset-password/{token}', function (Request $request, $token) {
        return view('auth.reset-password', ['token' => $token,'request'=>$request]);
    })->middleware('guest')->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    })->middleware('guest')->name('password.update');
}



//Estas rutas de aca en adelante requiren que la cuenta este verificada
// Esto genera las rutas de login y verificacion
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/report/new', [ReportsController::class, 'create'])->name('reports.create');
    Route::post('/report/save', [ReportsController::class, 'store'])->name('reports.store');
    Route::get('/report/{report}/edit', [ReportsController::class, 'edit'])->name('reports.edit');
    Route::put('/report/{report}/update', [ReportsController::class, 'update'])->name('reports.update');
    Route::delete('/report/{report}/delete',[ReportsController::class, 'destroy'])->name('reports.delete');

    Route::get('/report/{report}/show',[ReportsController::class,'show'])->name('reports.show');
    Route::get('/report/{report}/image/{index}/show',[ReportsController::class,'showImage'])->name('report.image.show');
    Route::get('/report/{report}/denounce',[ReportsController::class,'denounce'])->name('report.denounce');

    Route::get('/moderate-reports',[ModerationController::class,'index'])->name('moderation.index');
    Route::post('/moderate-report/approve',[ModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderate-report/reject',[ModerationController::class, 'reject'])->name('moderation.reject');

    Route::resource('/messages',MessageController::class);
});
