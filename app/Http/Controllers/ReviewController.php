<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Advertisement $advertisement)
    {
        return view('advertisements.review', compact('advertisement'));
    }

    public function store(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $advertisement->reviews()->create([
            'reviewer_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('advertisements.show', $advertisement)->with('success', 'Review submitted!');
    }
}
