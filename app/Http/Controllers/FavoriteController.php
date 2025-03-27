<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Advertisement $advertisement)
    {
        $user = Auth::user();
        $status = false;

        if ($user->favorites()->where('advertisement_id', $advertisement->id)->exists()) {
            $user->favorites()->detach($advertisement->id);
            $status = false;
        } else {
            $user->favorites()->attach($advertisement->id);
            $status = true;
        }

        // For AJAX requests, return JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'favorited' => $status
            ]);
        }

        // For regular requests, redirect back
        return back();
    }
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('user')->paginate(9); // paginate als je wilt!

        return view('favorites.index', compact('favorites'));
    }
}
