<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ApplyLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->cookie('locale', config('app.locale'));
        App::setLocale($locale);
        return $next($request);
    }
} 