<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\ListingPurchase;
use App\Models\RentalPeriod;
use App\Models\AuctionBidding;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // listings
        $listingsQuery = ListingPurchase::with('advertisement')
            ->where('user_id', auth()->id());

        // Filters
        if ($request->filled('start_date')) {
            $listingsQuery->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $listingsQuery->whereDate('purchase_date', '<=', $request->end_date);
        }

        // Sorteren
        if ($request->sort === 'date_asc') {
            $listingsQuery->orderBy('purchase_date', 'asc');
        } else {
            $listingsQuery->orderBy('purchase_date', 'desc');
        }

        $listings = $listingsQuery->paginate(10);

        // Rentals
        $rentalsQuery = RentalPeriod::with('advertisement')
            ->where('user_id', auth()->id());

        // Filters
        if ($request->filled('start_date')) {
            $rentalsQuery->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $rentalsQuery->whereDate('end_date', '<=', $request->end_date);
        }

        // Sorteren
        if ($request->sort === 'date_asc') {
            $rentalsQuery->orderBy('start_date', 'asc');
        } else {
            $rentalsQuery->orderBy('start_date', 'desc');
        }

        $rentals = $rentalsQuery->paginate(10);

        return view('purchases.index', compact('listings', 'rentals'));
    }

    public function buy_advertisement(Advertisement $advertisement)
    {
        $user = auth()->user();

        $user->purchases()->create([
            'advertisement_id' => $advertisement->id,
            'purchase_date' => now(),
        ]);

        return redirect()->route('purchases.index')->with('success', 'Product purchased successfully!');
    }

    public function rent_advertisement(Request $request, Advertisement $advertisement)
    {
        $user_id = auth()->user()->id;
        if ($user_id === $advertisement->user_id) {
            return back()->with('error', 'You cannot rent your own advertisement.');
        }

        if ($advertisement->type !== 'rental') {
            return back()->with('error', 'This advertisement is not available for rent.');
        }

        $verified = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check if the item is already rented for the requested period
        $conflictingRental = RentalPeriod::where('advertisement_id', $advertisement->id)
            ->where(function($query) use ($verified) {
                $query->where(function($q) use ($verified) {
                    // Check if start_date falls within an existing rental
                    $q->where('start_date', '<=', $verified['start_date'])
                      ->where('end_date', '>=', $verified['start_date']);
                })->orWhere(function($q) use ($verified) {
                    // Check if end_date falls within an existing rental
                    $q->where('start_date', '<=', $verified['end_date'])
                      ->where('end_date', '>=', $verified['end_date']);
                })->orWhere(function($q) use ($verified) {
                    // Check if rental period encompasses an existing rental
                    $q->where('start_date', '>=', $verified['start_date'])
                      ->where('end_date', '<=', $verified['end_date']);
                });
            })->exists();

        if ($conflictingRental) {
            return back()->with('error', 'The item is not available for the selected dates.');
        }

        $rentalPeriod = new RentalPeriod();
        $rentalPeriod->advertisement_id = $advertisement->id;
        $rentalPeriod->user_id = $user_id;
        $rentalPeriod->start_date = $verified['start_date'];
        $rentalPeriod->end_date = $verified['end_date'];
        $rentalPeriod->save();

        return redirect()->route('purchases.index')->with('success', 'Advertisement rented successfully!');
    }

    public function getBlockedDates(Advertisement $advertisement)
    {
        $blockedPeriods = RentalPeriod::where('advertisement_id', $advertisement->id)
            ->get(['start_date', 'end_date'])
            ->map(function($period) {
                return [
                    'from' => $period->start_date->format('Y-m-d'),
                    'to' => $period->end_date->format('Y-m-d'),
                ];
            });

        return response()->json($blockedPeriods);
    }

    public function bid_advertisement(Request $request, Advertisement $advertisement)
    {
        $user_id = auth()->user()->id;
        if ($user_id === $advertisement->user_id) {
            return back()->with('error', 'You cannot bid on your own advertisement.');
        }

        if ($advertisement->type !== 'auction') {
            return back()->with('error', 'This advertisement is not an auction.');
        }

        // Check if auction is active
        if (!$advertisement->isAuctionActive()) {
            if (now()->lt($advertisement->auction_start_date)) {
                return back()->with('error', 'This auction has not started yet.');
            } else {
                return back()->with('error', 'This auction has ended.');
            }
        }

        $verified = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . ($advertisement->bids()->max('amount') ?? $advertisement->price),
            ],
        ]);

        // Create the bid
        $bid = new Bid();
        $bid->advertisement_id = $advertisement->id;
        $bid->user_id = $user_id;
        $bid->amount = $verified['amount'];
        $bid->save();

        return back()->with('success', 'Bid placed successfully!');
    }
}
