<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Advertisement Review Methods
    public function createAdvertisementReview(Advertisement $advertisement)
    {
        return view('advertisements.review', compact('advertisement'));
    }

    public function storeAdvertisementReview(Request $request, Advertisement $advertisement)
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

        return redirect()->route('advertisements.show', $advertisement)
            ->with('success', 'Review submitted!');
    }

    // User Review Methods
    public function createUserReview(User $user)
    {
        return view('users.review', compact('user'));
    }

    public function storeUserReview(Request $request, User $user)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user->reviews()->create([
            'reviewer_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Check if the previous URL was an advertisement page
        $redirectTo = $request->input('redirect_to');
        if (str_contains($redirectTo, '/advertisements/')) {
            return redirect($redirectTo)->with('success', 'Review submitted!');
        }

        return redirect()->route('users.show', $user)
            ->with('success', 'Review submitted!');
    }
}
