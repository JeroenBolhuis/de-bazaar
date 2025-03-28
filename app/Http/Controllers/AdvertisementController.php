<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use Carbon\Carbon;

class AdvertisementController extends Controller
{

    public function index(Request $request)
    {
        $query = Advertisement::query();

        // Type filter
        if ($request->has('types') && !empty($request->types)) {
            $query->whereIn('type', $request->types);
        }

        // Category filter
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereIn('category', $request->categories);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Auction status filters
        if ($request->has('auction_status') && in_array('auction', $request->types ?? [])) {
            if (in_array('live', $request->auction_status)) {
                $query->where('type', 'auction')
                    ->where('auction_start_date', '<=', now())
                    ->where('auction_end_date', '>=', now());
            }

            if (in_array('upcoming', $request->auction_status)) {
                $query->where('type', 'auction')
                    ->where('auction_start_date', '>', now());
            }

            if (in_array('ending_soon', $request->auction_status)) {
                $query->where('type', 'auction')
                    ->where('auction_end_date', '>=', now())
                    ->where('auction_end_date', '<=', now()->addHours(24));
            }
        }

        // Rental period filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // This would need a more complex query depending on your rental availability model
            // This is a simplified version
            $query->where('type', 'rental')
                ->where(function($q) use ($request) {
                    $q->whereDoesntHave('rentals', function($rental) use ($request) {
                        $rental->where('start_date', '<=', $request->end_date)
                            ->where('end_date', '>=', $request->start_date);
                    });
                });
        }

        // Sort options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    // Count the number of users who have favorited this advertisement
                    $query->withCount('favoritedBy')
                        ->orderByDesc('favorited_by_count');
                    break;
            }
        } else {
            // Default sorting
            $query->latest();
        }

        $advertisements = $query->paginate(9);

        return view('advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('advertisements.create');
    }

    public function store(Request $request)
    {
        $baseValidation = [
            'type' => 'required|in:listing,rental,auction',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add type-specific validation rules
        switch ($request->type) {
            case 'auction':
                $baseValidation = array_merge($baseValidation, [
                    'auction_start_date' => 'required|date|after:now',
                    'auction_end_date' => 'required|date|after:auction_start_date',
                ]);
                break;
            case 'rental':
                $baseValidation = array_merge($baseValidation, [
                    'condition' => 'required|numeric|min:0|max:100',
                    'wear_per_day' => 'required|numeric|min:0|max:100',
                ]);
                break;
        }

        $validated = $request->validate($baseValidation);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('advertisements', 'public');
            $validated['image'] = $path;
        }

        // Add user_id to the validated data
        $validated['user_id'] = auth()->id();

        $advertisement = Advertisement::create($validated);

        return redirect()->route('advertisements.show', $advertisement);
    }

    public function show(Advertisement $advertisement)
    {
        $blockedDates = [];
        if ($advertisement->type === 'rental') {
            $blockedDates = $advertisement->rentalPeriods()
                ->get(['start_date', 'end_date'])
                ->map(function($period) {
                    return [
                        'from' => $period->start_date->format('Y-m-d'),
                        'to' => $period->end_date->format('Y-m-d'),
                    ];
                })->toArray();
        }

        return view('advertisements.show', compact('advertisement', 'blockedDates'));
    }
}
