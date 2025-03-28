<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Purchase;
use App\Models\RentalPeriod;
use App\Models\AuctionBidding;
use Illuminate\Http\Request;

class SalesAndPurchaseController extends Controller
{
    public function purchases(Request $request)
    {
        // Get active tab
        $activeTab = $request->get('active_tab', 'sales');
        $sortBy = $request->get('sort_by', 'date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Purchases
        $purchasesQuery = Purchase::with('advertisement')
            ->where('purchases.user_id', auth()->id());

        // Filters
        if ($request->filled('start_date')) {
            $purchasesQuery->whereDate('purchases.purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $purchasesQuery->whereDate('purchases.purchase_date', '<=', $request->end_date);
        }

        // Sorting
        if ($sortBy === 'price') {
            $purchasesQuery->join('advertisements', 'purchases.advertisement_id', '=', 'advertisements.id')
                     ->orderBy('advertisements.price', $sortDirection)
                     ->select('purchases.*');
        } else {
            $purchasesQuery->orderBy('purchases.purchase_date', 'desc');
        }

        $purchases = $purchasesQuery->paginate(10);

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

        // Sorting
        if ($sortBy === 'price') {
            $rentalsQuery->join('advertisements', 'rental_periods.advertisement_id', '=', 'advertisements.id')
                        ->orderBy('advertisements.price', $sortDirection)
                        ->select('rental_periods.*');
        } else {
            $rentalsQuery->orderBy('rental_periods.start_date', $sortDirection);
        }

        $rentals = $rentalsQuery->paginate(10);

        return view('salesAndPurchases.purchases', compact('purchases', 'rentals', 'activeTab', 'sortBy', 'sortDirection', 'startDate', 'endDate'));
    }

    public function purchasesCalendar(Request $request)
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

        return view('salesAndPurchases.purchases-calendar', compact('calendar', 'month', 'year', 'previousMonth', 'previousYear', 'nextMonth', 'nextYear'));
    }

    public function sales(Request $request)
    {
        // Get active tab
        $activeTab = $request->get('active_tab', 'sales');
        $sortBy = $request->get('sort_by', 'date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get sales
        $salesQuery = Purchase::with(['advertisement', 'user'])
            ->whereHas('advertisement', function($query) {
                $query->where('user_id', auth()->id());
            });

        // Filters
        if ($request->filled('start_date')) {
            $salesQuery->whereDate('purchases.purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $salesQuery->whereDate('purchases.purchase_date', '<=', $request->end_date);
        }

        // Sorting
        if ($sortBy === 'price') {
            $salesQuery->join('advertisements', 'purchases.advertisement_id', '=', 'advertisements.id')
                     ->orderBy('advertisements.price', $sortDirection)
                     ->select('purchases.*');
        } else {
            $salesQuery->orderBy('purchases.purchase_date', $sortDirection);
        }

        $sales = $salesQuery->paginate(10);

        // Get rentals
        $rentalsQuery = RentalPeriod::with(['advertisement', 'user'])
            ->whereHas('advertisement', function($query) {
                $query->where('user_id', auth()->id());
            });

        // Filters
        if ($request->filled('start_date')) {
            $rentalsQuery->whereDate('rental_periods.start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $rentalsQuery->whereDate('rental_periods.end_date', '<=', $request->end_date);
        }

        // Sorting
        if ($sortBy === 'price') {
            $rentalsQuery->join('advertisements', 'rental_periods.advertisement_id', '=', 'advertisements.id')
                         ->orderBy('advertisements.price', $sortDirection)
                         ->select('rental_periods.*');
        } else {
            $rentalsQuery->orderBy('rental_periods.start_date', $sortDirection);
        }

        $rentals = $rentalsQuery->paginate(10);

        return view('salesAndPurchases.sales', compact('sales', 'rentals', 'activeTab', 'sortBy', 'sortDirection', 'startDate', 'endDate'));
    }

    public function salesCalendar(Request $request)
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
        $rentals = RentalPeriod::with(['advertisement', 'user'])
            ->whereHas('advertisement', function($query) {
                $query->where('user_id', auth()->id());
            })
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

        return view('salesAndPurchases.sales-calendar', compact('calendar', 'month', 'year', 'previousMonth', 'previousYear', 'nextMonth', 'nextYear'));
    }

    public function auctionCalendar(Request $request)
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

        // Get all auctions within the date range
        $auctions = Advertisement::where('type', 'auction')
            ->where('user_id', auth()->id())
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('auction_start_date', [$start, $end])
                    ->orWhereBetween('auction_end_date', [$start, $end])
                    ->orWhere(function($q) use ($start, $end) {
                        $q->where('auction_start_date', '<=', $start)
                          ->where('auction_end_date', '>=', $end);
                    });
            })
            ->get();

        // Create calendar array
        $calendar = [];
        $currentDate = $start->copy();

        while ($currentDate <= $end) {
            $dateString = $currentDate->format('Y-m-d');
            
            // Get auctions for this day
            $dayAuctions = $auctions->filter(function($auction) use ($currentDate) {
                // Use betweenIncluded to include the exact start and end dates even with time components
                return $currentDate->betweenIncluded(
                    $auction->auction_start_date->startOfDay(),
                    $auction->auction_end_date->endOfDay()
                );
            });

            $calendar[$dateString] = $dayAuctions;
            
            $currentDate->addDay();
        }

        return view('salesAndPurchases.auction-calendar', compact('calendar', 'month', 'year', 'previousMonth', 'previousYear', 'nextMonth', 'nextYear'));
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

        // Directly check the count in the database
        $bidCount = AuctionBidding::whereHas('advertisement', function($query) {
            $query->where('type', 'auction')
                  ->where(function($q) {
                      // Count bids for active or future auctions only
                      $q->where('auction_end_date', '>=', now())
                        ->orWhere('auction_start_date', '>', now());
                  });
        })->where('user_id', $user_id)->count();
        
        // Check if limit reached
        if ($bidCount >= 4) {
            return back()->with('error', 'You have reached the maximum limit of bids (4).');
        }

        // Check if auction is active
        if (!$advertisement->isAuctionActive()) {
            if (now()->lt($advertisement->auction_start_date)) {
                return back()->with('error', 'This auction has not started yet.');
            } else {
                return back()->with('error', 'This auction has ended.');
            }
        }

        // Get the minimum bid amount (current highest bid or starting price)
        $minBidAmount = $advertisement->auctionBiddings()->max('amount') ?? $advertisement->price;

        // Validate bid amount
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $minBidAmount,
            ],
        ], [
            'amount.min' => 'Your bid must be at least €' . number_format($minBidAmount, 2)
        ]);

        // Create the bid
        $bid = new AuctionBidding();
        $bid->advertisement_id = $advertisement->id;
        $bid->user_id = $user_id;
        $bid->amount = $request->amount;
        $bid->save();

        return back()->with('success', 'Bid placed successfully!');
    }
}
