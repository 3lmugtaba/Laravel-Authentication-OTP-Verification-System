<?php

use App\Http\Controllers\Auth\ForgetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('register'));

Route::middleware('guest')->group(function () {
    Route::get('/register', fn () => view('auth.register.register'))->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
    Route::get('/verify-OTP', [RegisterController::class, 'showOtpForm'])->name('registration.otp.form');
    Route::post('/verify-OTP', [RegisterController::class, 'verifyOTP'])->middleware('throttle:10,1')->name('vOTP');
    Route::post('/verify-OTP/resend', [RegisterController::class, 'resendOtp'])->middleware('throttle:3,1')->name('registration.otp.resend');

    Route::get('/login', fn () => view('auth.login.login'))->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.store');

    Route::get('/reset', [ForgetController::class, 'showFormReset'])->name('formReset');
    Route::post('/reset', [ForgetController::class, 'resetPassword'])->middleware('throttle:3,1')->name('reset');
    Route::get('/reset/verify-OTP', [ForgetController::class, 'showOtpForm'])->name('reset.otp.form');
    Route::post('/reset/verify-OTP', [ForgetController::class, 'verifyResetOtp'])->middleware('throttle:10,1')->name('setNewPassword');
    Route::post('/reset/verify-OTP/resend', [ForgetController::class, 'resendResetOtp'])->middleware('throttle:3,1')->name('reset.otp.resend');
    Route::get('/reset/new-password', [ForgetController::class, 'showNewPasswordForm'])->name('reset.password.form');
    Route::post('/reset/checkPassword', [ForgetController::class, 'checkPassword'])->middleware('throttle:5,1')->name('checkPassword');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboardPage'])->name('Dash');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile/{id}', [RegisterController::class, 'profileIndex'])->name('profile');
});
