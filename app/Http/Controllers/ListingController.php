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
     * Show the form for creating a new listing.
     */
    public function create(): View
    {
        return view('listings.create');
    }

    /**
     * Store a newly created listing in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // TODO: Implement listing creation
        return redirect()->route('listings.index')
            ->with('success', __('Listing created successfully.'));
    }

    /**
     * Display the specified listing.
     */

    public function show(Advertisement $advertisement)
    {
        return view('listings.show', compact('advertisement'));
    }

    /**
     * Show the form for editing the specified listing.
     */
    public function edit(string $id): View
    {
        // TODO: Implement listing retrieval for editing
        return view('listings.edit');
    }

    /**
     * Update the specified listing in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // TODO: Implement listing update
        return redirect()->route('listings.show', $id)
            ->with('success', __('Listing updated successfully.'));
    }


    /**
     * Remove the specified listing from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // TODO: Implement listing deletion
        return redirect()->route('listings.index')
            ->with('success', __('Listing deleted successfully.'));
    }
}
