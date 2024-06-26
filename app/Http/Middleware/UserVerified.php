<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(empty(Auth::user()->email_verified_at)){
            // $this->guard()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect('/login');
        }
        return $next($request);
    }
}
