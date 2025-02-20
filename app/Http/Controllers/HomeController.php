<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        // TODO: Get featured listings, rentals, and auctions
        return view('home');
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
