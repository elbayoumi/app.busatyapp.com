<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AttendantsAactive
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

        if ($request->user()->typeAuth == 'attendants') {


            if ($request->user()->bus_id != '') {
                return $next($request);

            }else {
                return response('لم يتم اضافة بيانات  الباص بعد', 500);

            }
        }else {
            return response('Unauthorized.', 401);
        }
    }
}
