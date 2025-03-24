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

        if ($user->favorites()->where('advertisement_id', $advertisement->id)->exists()) {
            $user->favorites()->detach($advertisement->id);
        } else {
            $user->favorites()->attach($advertisement->id);
        }

        return back();
    }
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('user')->paginate(9); // paginate als je wilt!

        return view('favorites.index', compact('favorites'));
    }
}
