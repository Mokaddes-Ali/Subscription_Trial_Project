<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subscription', function () {
    return view('subscription');
})->name('subscription');

// routes/web.php

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice'); // Prompt to verify email if not verified

    Route::get('/verify', [TwoFactorController::class, 'verify'])->name('verify');
    Route::post('/verify-otp', [TwoFactorController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified', 'twofactor'])->name('dashboard'); // Only accessible if email is verified and 2FA is completed
});


Route::get('/verify', [TwoFactorController::class, 'verify'])->name('verify');
Route::post('/verify-otp', [TwoFactorController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'twofactor'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
