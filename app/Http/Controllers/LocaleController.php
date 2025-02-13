<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function setLocale(Request $request, string $locale)
    {
        if (!in_array($locale, ['nl', 'en'])) {
            return redirect()->back();
        }

        App::setLocale($locale);
        Session::put('locale', $locale);
        return redirect()->back()
            ->withCookie(cookie()->forever('locale', $locale));
    }
} 