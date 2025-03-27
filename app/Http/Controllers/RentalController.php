<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    /**
     * Display a listing of rentals.
     */
    public function index()
    {
        $rentals = Advertisement::where('type', 'rental')->with('reviews')->latest()->paginate(9);
        return view('rentals.index', compact('rentals'));
    }

    /**
     * Display the specified rental.
     */
    public function show(string $id): View
    {
        // TODO: Implement rental retrieval
        return view('rentals.show');
    }

    /**
     * Display the calendar view for a rental item.
     */
    public function calendar(string $id): View
    {
        // TODO: Implement calendar view with availability
        return view('rentals.calendar');
    }

    /**
     * Book a rental for specific dates.
     */
    public function book(Request $request, string $id): RedirectResponse
    {
        // TODO: Implement rental booking
        return redirect()->route('rentals.show', $id)
            ->with('success', __('Rental booked successfully.'));
    }

    /**
     * Return a rented item.
     */
    public function return(Request $request, string $id): RedirectResponse
    {
        // TODO: Implement rental return process
        return redirect()->route('rentals.show', $id)
            ->with('success', __('Rental returned successfully.'));
    }
}
