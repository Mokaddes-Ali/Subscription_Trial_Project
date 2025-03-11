<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated but hasn't completed 2FA, redirect to 2FA verification
        if (auth()->check() && auth()->user()->two_factor_code) {
            return redirect()->route('verify');
        }

        return $next($request); // Continue to next step if 2FA is completed
    }
}
