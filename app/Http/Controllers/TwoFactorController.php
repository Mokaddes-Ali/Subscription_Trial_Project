<?php

namespace App\Http\Controllers;

use Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Flasher\Laravel\Facade\Flasher;

class TwoFactorController extends Controller
{
    public function verify()
    {
        return view('auth.verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required',
        ]);

        $user = Auth::user();

        if ($user->two_factor_code !== $request->two_factor_code) {
            Flasher::addError('❌ Invalid OTP! Please try again.');
            return back()->withInput();
        }

        if ($user->two_factor_expires_at < now()) {
            Flasher::addWarning('⚠️ OTP expired! A new code has been sent.');
            return back();
        }

        // Mark OTP as verified by setting email_verified_at
        $user->email_verified_at = now();
        $user->cleanTwoFactorCode(); // Remove OTP after verification
        $user->save();

        // Check subscription status and redirect accordingly
    if ($user->is_subscribed == 0) {
        Flasher::addInfo('📢 Please complete your subscription.');
        return redirect()->route('subscription');
    }

        Flasher::addSuccess('🎉 You have successfully logged in!');
        return redirect()->route('dashboard');
    }

    public function resend(Request $request)
    {
        $user = Auth::user();
        $user->regenerateTwoFactorCode();
        $user->notify(new TwoFactorCodeNotification());

        Flasher::addInfo('📩 A new OTP has been sent to your email.');
        return back();
    }
}
