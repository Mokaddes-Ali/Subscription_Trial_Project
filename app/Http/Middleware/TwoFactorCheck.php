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
        $user = auth()->user();

        if ($user && is_null($user->email_verified_at)) {
            return redirect()->route('verify');
        }

        if ($user && $user->is_subscribed == 0) {
            return redirect()->route('subscription');
        }

        return $next($request);
    }
}
