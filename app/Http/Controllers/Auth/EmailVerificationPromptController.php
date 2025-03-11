<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // If the email is already verified, redirect to the dashboard
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard') // Redirect directly to the dashboard if verified
            : view('auth.verify'); // Show the verification page if not verified
    }
}
