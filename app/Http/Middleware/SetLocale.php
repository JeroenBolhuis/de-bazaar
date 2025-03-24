<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // First priority: Session
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        // Second priority: Cookie
        elseif ($request->cookie('locale')) {
            App::setLocale($request->cookie('locale'));
        }
        // Default: Config default locale
        else {
            App::setLocale(config('app.locale'));
        }
        
        return $next($request);
    }
} 