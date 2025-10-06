<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isParents
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->typeAuth == 'parents') {
            return $next($request);
        }else {
            return response('Unauthorized.', 401);
        }
    }
}
