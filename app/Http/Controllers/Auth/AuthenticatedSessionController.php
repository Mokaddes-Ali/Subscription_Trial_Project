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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // Check if user has verified OTP (using email_verified_at)
        if (is_null($user->email_verified_at)) {
            // Generate new OTP and send notification
            $user->regenerateTwoFactorCode();
            $user->notify(new TwoFactorCodeNotification());

            return redirect()->route('verify');
        }

         // Check subscription status and redirect accordingly
    if ($user->is_subscribed == 0) {
        return redirect()->route('subscription');
    }

        Flasher::addSuccess('Login successful! Welcome back.');
        return redirect()->intended(route('dashboard', absolute: false));
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

