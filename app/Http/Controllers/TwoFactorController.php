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
            'two_factor_code' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();

        // Check if OTP matches
        if ($user->two_factor_code !== $request->two_factor_code) {
            Flasher::addError('❌ Invalid OTP! Please try again.');
            return back()->withInput();
        }

        // Check if OTP expired
        if (now()->greaterThan($user->two_factor_expires_at)) {
            Flasher::addWarning('⚠️ OTP expired! A new code has been sent.');
            $user->regenerateTwoFactorCode(); // Regenerate new OTP
            return back();
        }

        // OTP Verified, clear it
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        Flasher::addSuccess('✅ OTP Verified! Welcome back.');

        return redirect()->route('dashboard'); // Redirect to dashboard after successful 2FA
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
