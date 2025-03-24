<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * Display the home page.
     */
    public function index(): View
    {
        $advertisements = Advertisement::latest()->take(10)->get();

        return view('home', compact('advertisements'));
    }


    /**
     * Display the about page.
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy(): View
    {
        return view('pages.privacy');
    }

    /**
     * Display the terms of service page.
     */
    public function terms(): View
    {
        return view('pages.terms');
    }
}
