<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class isAdmin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function guard()
    {
        return Auth::guard();
    }
    protected function loggedOut(Request $request)
    {

    }
    public function handle(Request $request, Closure $next)
    {
        if(isset(Auth::user()->name)){

            if(Auth::user()->is_admin == 0){
                $this->guard()->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                if ($response = $this->loggedOut($request)) {
                    return $response;
                }

                return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect('/login');
            }
        }


        return $next($request);
    }
}
