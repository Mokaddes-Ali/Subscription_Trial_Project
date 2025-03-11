<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\TwoFactorCodeNotification;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $request->user()->regenerateTwoFactorCode();
        $request->user()->notify(new TwoFactorCodeNotification());

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            Flasher::addSuccess('Please verify your email, we sent a verification code.');
            return redirect()->route('verify');
        }

        if ($user->is_subscribed == 0) {
            Flasher::addWarning('You need to subscribe to access the dashboard.');
            return redirect()->route('subscription');
        }

        Flasher::addSuccess('Login successful! Welcome back.');
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Flasher::addSuccess('Logout successful! See you again.');
        return redirect('/');
    }
}


