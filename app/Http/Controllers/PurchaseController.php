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
            ->where('listing_purchases.user_id', auth()->id());

        // Filters
        if ($request->filled('start_date')) {
            $listingsQuery->whereDate('listing_purchases.purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $listingsQuery->whereDate('listing_purchases.purchase_date', '<=', $request->end_date);
        }

        // Sorteren
        if ($request->sort === 'price_asc') {
            $listingsQuery->join('advertisements', 'listing_purchases.advertisement_id', '=', 'advertisements.id')
                         ->orderBy('advertisements.price', 'asc')
                         ->select('listing_purchases.*');
        } else if ($request->sort === 'price_desc') {
            $listingsQuery->join('advertisements', 'listing_purchases.advertisement_id', '=', 'advertisements.id')
                         ->orderBy('advertisements.price', 'desc')
                         ->select('listing_purchases.*');
        } else {
            $listingsQuery->orderBy('listing_purchases.purchase_date', 'desc');
        }

        $listings = $listingsQuery->paginate(10);

        // Rentals
        $rentalsQuery = RentalPeriod::with('advertisement')
            ->where('rental_periods.user_id', auth()->id());

        // Filters
        if ($request->filled('start_date')) {
            $rentalsQuery->whereDate('rental_periods.start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $rentalsQuery->whereDate('rental_periods.end_date', '<=', $request->end_date);
        }

        // Rental State Filter
        if ($request->filled('rental_state')) {
            $now = now();
            switch ($request->rental_state) {
                case 'active':
                    $rentalsQuery->where('rental_periods.start_date', '<=', $now)
                                ->where('rental_periods.end_date', '>=', $now);
                    break;
                case 'upcoming':
                    $rentalsQuery->where('rental_periods.start_date', '>', $now);
                    break;
                case 'completed':
                    $rentalsQuery->where('rental_periods.end_date', '<', $now);
                    break;
            }
        }

        // Sorteren
        if ($request->sort === 'price_asc') {
            $rentalsQuery->join('advertisements', 'rental_periods.advertisement_id', '=', 'advertisements.id')
                        ->orderBy('advertisements.price', 'asc')
                        ->select('rental_periods.*');
        } else if ($request->sort === 'price_desc') {
            $rentalsQuery->join('advertisements', 'rental_periods.advertisement_id', '=', 'advertisements.id')
                        ->orderBy('advertisements.price', 'desc')
                        ->select('rental_periods.*');
        } else {
            $rentalsQuery->orderBy('rental_periods.start_date', 'desc');
        }

        $rentals = $rentalsQuery->paginate(10);

        return view('purchases.index', compact('listings', 'rentals'));
    }

    public function calendar(Request $request)
    {
        // Get current month and year from request or use current date
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Create Carbon instance for the first day of the month
        $firstDayOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1);
        
        // Calculate previous and next month/year
        $previousMonth = $firstDayOfMonth->copy()->subMonth()->month;
        $previousYear = $firstDayOfMonth->copy()->subMonth()->year;
        $nextMonth = $firstDayOfMonth->copy()->addMonth()->month;
        $nextYear = $firstDayOfMonth->copy()->addMonth()->year;

        // Get start and end dates for the calendar view (including padding days)
        $start = $firstDayOfMonth->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::MONDAY);
        $end = $firstDayOfMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);

        // Get all rentals within the date range
        $rentals = RentalPeriod::with('advertisement')
            ->where('user_id', auth()->id())
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                          ->where('end_date', '>=', $end);
                    });
            })
            ->get();

        // Create calendar array
        $calendar = [];
        $currentDate = $start->copy();

        while ($currentDate <= $end) {
            $dateString = $currentDate->format('Y-m-d');
            
            // Get rentals for this day
            $dayRentals = $rentals->filter(function($rental) use ($currentDate) {
                return $currentDate->between($rental->start_date, $rental->end_date);
            });

            $calendar[$dateString] = $dayRentals;
            
            $currentDate->addDay();
        }

        return view('purchases.calendar', compact('calendar', 'month', 'year', 'previousMonth', 'previousYear', 'nextMonth', 'nextYear'));
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
