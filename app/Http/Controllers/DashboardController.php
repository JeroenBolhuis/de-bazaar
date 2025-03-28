<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Advertisement;
use App\Models\RentalPeriod;
use App\Models\AuctionBidding;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $advertisements = Advertisement::where('user_id', $user->id)->get();
        $rentedItems = RentalPeriod::where('user_id', $user->id)->get();
        $rentedOutItems = RentalPeriod::with('advertisement')->whereHas('advertisement', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $biddings = AuctionBidding::where('user_id', $user->id)
            ->whereHas('advertisement', function($query) {
                $query->where('auction_end_date', '>', now());
            })
            ->with('advertisement')->get();

        // Get counts for different types of listings
        $stats = [
            'active_listings' => $advertisements->where('type', 'listing')->count(),
            'active_rentals' => $advertisements->where('type', 'rental')->count(),
            'active_auctions' => $advertisements->where('type', 'auction')->count(),
            'active_biddings' => $biddings->count(),
        ];

        // Get upcoming events within 7 days or less and not in the past
        $upcomingEvents = [
            'rental_pickups' => $rentedItems->where('start_date', '>=', now()->startOfDay())
                ->where('start_date', '<', now()->addDays(7)->endOfDay())
                ->all(),
            'rental_returns' => $rentedItems->where('end_date', '>=', now()->startOfDay())
                ->where('end_date', '<', now()->addDays(7)->endOfDay())
                ->all(),
            'rental_gives' => $rentedOutItems->where('start_date', '>=', now()->startOfDay())
                ->where('start_date', '<', now()->addDays(7)->endOfDay())
                ->all(),
            'rental_receives' => $rentedOutItems->where('end_date', '>=', now()->startOfDay())
                ->where('end_date', '<', now()->addDays(7)->endOfDay())
                ->all(),
            'ending_auctions' => $advertisements->where('type', 'auction')
                ->where('auction_end_date', '>=', now()->startOfDay())
                ->where('auction_end_date', '<', now()->addDays(7)->endOfDay())
                ->all(),
        ];

        // Get business stats if user is a business user
        $businessStats = null;
        if ($user->isBusiness()) {
            $businessStats = [
                'total_revenue' => 0, // TODO: Implement total revenue
                'active_contracts' => 0, // TODO: Implement active contracts count
                'total_orders' => 0, // TODO: Implement total orders
                'customer_rating' => null, // TODO: Implement customer rating
            ];
        }

        return view('dashboard', compact('stats', 'upcomingEvents', 'businessStats'));
    }
}
