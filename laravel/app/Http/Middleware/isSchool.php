<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isSchool
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->typeAuth == 'school') {
            return $next($request);
        }else {
            return response('Unauthorized.', 401);
        }
    }
}
