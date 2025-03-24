<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('advertisement')
            ->where('user_id', auth()->id());

        // Filters (bijvoorbeeld op datum)
        if ($request->has('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        // Sorteren
        if ($request->sort === 'date_asc') {
            $query->orderBy('purchase_date', 'asc');
        } else {
            $query->orderBy('purchase_date', 'desc');
        }

        $purchases = $query->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function store(Advertisement $advertisement)
    {
        $user = auth()->user();

        // Optional: prevent double purchase
        if ($user->purchases()->where('advertisement_id', $advertisement->id)->exists()) {
            return back()->with('error', 'You already purchased this product!');
        }

        $user->purchases()->create([
            'advertisement_id' => $advertisement->id,
            'purchase_date' => now(),
        ]);

        return redirect()->route('purchases.index')->with('success', 'Product purchased successfully!');
    }

}
