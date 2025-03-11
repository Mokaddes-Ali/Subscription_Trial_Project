<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subscription', function () {
    return view('subscription');
})->name('subscription');


//login with google socialite

Route::get('login/google', [SocialController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google-collback', [SocialController::class, 'handleGoogleCallback'])->name('auth.google-callback');
Route::get('login/github', [SocialController::class, 'redirectToGithub']);
Route::get('login/facebook', [SocialController::class, 'redirectToFacebook']);



Route::get('/verify', [TwoFactorController::class, 'verify'])->name('verify');
Route::post('/verify-otp', [TwoFactorController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'factor'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
