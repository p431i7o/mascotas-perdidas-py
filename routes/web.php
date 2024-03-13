<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportsController;
use phpDocumentor\Reflection\Types\Resource_;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReportDenounceController;
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

Route::get('/', [HomeController::class, 'root'])->name('root');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/searchAutocomplete', [HomeController::class, 'autoComplete'])->name('search.autocomplete');

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
    Route::get('/my-profile',[HomeController::class,'profile'])->name('profile');
    Route::post('/my-profile',[HomeController::class, 'updateProfile']);

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/report/new', [ReportsController::class, 'create'])->name('reports.create');
    Route::post('/report/save', [ReportsController::class, 'store'])->name('reports.store');
    Route::get('/report/{report}/edit', [ReportsController::class, 'edit'])->name('reports.edit');
    Route::put('/report/{report}/update', [ReportsController::class, 'update'])->name('reports.update');
    Route::delete('/report/{report}/delete',[ReportsController::class, 'destroy'])->name('reports.delete');
    Route::post('/report/{report}/renovate',[ReportsController::class,'renovate'])->name('reports.renovate');

    Route::get('/report/{report}/denounce',[ReportDenounceController::class,'store'])->name('report.denounce');

    Route::get('/moderate-reports',[ModerationController::class,'index'])->name('moderation.index');
    Route::post('/moderate-report/approve',[ModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderate-report/reject',[ModerationController::class, 'reject'])->name('moderation.reject');

    Route::post('/message/{message}/conversation',[MessageController::class, 'getConversation'])->name('messages.getConversation');
    Route::post('/message/{message}/response',[MessageController::class, 'respondMessage'])->name('message.response');
    Route::resource('/messages',MessageController::class)->except(['create','show','update','edit']);

    Route::post('/user/{user}/updateRole',[UserController::class,'updateRole'])->name('user.updateRole');
    Route::post('/user/{user}/registeredMail',[UserController::class, 'sendVerifyMail'])->name('user.registered.mail');
    Route::post('/user/{user}/resetPassword',[UserController::class, 'sendResetPasswordMail'])->name('user.resetPassword.mail');
    Route::resource('/user',UserController::class);

    Route::get('/report/denounces',[ReportDenouncecontroller::class,'index'])->name('denounce.index');
    Route::resource('/report/{report}/denounce',ReportDenounceController::class)->except(['index','create','show','edit','update','destroy']);
    Route::post('/report/{report}/rejectDenounce',[ReportDenounceController::class, 'reject'])->name('reportDenounce.reject');
});

Route::get('/report/{report}/image/{index}/show',[ReportsController::class,'showImage'])->name('report.image.show');
Route::get('/report/{report}/show',[ReportsController::class,'show'])->name('reports.show');
