<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListingController extends Controller
{
    /**
     * Display a listing of listings.
     */


    public function index()
    {
        $query = Advertisement::query();
        // Exclude purchased ads for logged-in user
        if (auth()->check()) {
            $purchasedIds = auth()->user()->purchases()->pluck('advertisement_id')->toArray();
            $query->whereNotIn('id', $purchasedIds);
        }

        $advertisements = $query->latest()->paginate(9);

        return view('listings.index', compact('advertisements'));
    }


    /**
     * Display the specified listing.
     */

    public function show(Advertisement $advertisement)
    {
        return view('listings.show', compact('advertisement'));
    }
}
