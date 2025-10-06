<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserVerifyEmail
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

        if ($request->user() && $request->user()->email_verified_at !== null) {
            return $next($request);
        }else {
            return response('You need to confirm your account. We have sent you an activation code, please check your email.', 403);
        }


    }
}
