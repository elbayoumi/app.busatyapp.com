<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Get the locale from the 'ln' header or fallback
        $locale = $request->header('ln') ?? config('app.fallback_locale');

        // Ensure available_locales is always an array
        $availableLocales = (array) config('app.available_locales');

        // Check if locale is allowed
        if (in_array($locale, $availableLocales)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }

        return $next($request);
    }
}
