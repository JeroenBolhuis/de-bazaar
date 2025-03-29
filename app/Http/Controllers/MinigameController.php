<?php

namespace App\Http\Controllers;

use App\Models\MinigameRecord;
use Illuminate\Http\Request;

class MinigameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $userRecords = MinigameRecord::where('user_id', $user->id)->get();
        $worldRecords = MinigameRecord::getWorldRecords();
        $userWorldRecords = $worldRecords->where('user_id', $user->id);
        
        return view('minigames.index', [
            'userRecords' => $userRecords,
            'worldRecords' => $worldRecords,
            'userWorldRecords' => $userWorldRecords,
            'discount' => $user->getMinigameDiscountPercentage()
        ]);
    }

    public function game1Intro()
    {
        return view('minigames.game1-intro');
    }

    public function game1()
    {
        // Clear any previous score data when starting a new game
        request()->session()->forget('last_submitted_score');
        return view('minigames.game1');
    }

    public function game2Intro()
    {
        return view('minigames.game2-intro');
    }

    public function game2()
    {
        // Clear any previous score data when starting a new game
        request()->session()->forget('last_submitted_score');
        return view('minigames.game2');
    }

    public function game3Intro()
    {
        return view('minigames.game3-intro');
    }

    public function game3()
    {
        // Clear any previous score data when starting a new game
        request()->session()->forget('last_submitted_score');
        return view('minigames.game3');
    }

    public function game4Intro()
    {
        return view('minigames.game4-intro');
    }

    public function game4()
    {
        // Clear any previous score data when starting a new game
        request()->session()->forget('last_submitted_score');
        return view('minigames.game4');
    }

    public function submitScore(Request $request)
    {
        $validated = $request->validate([
            'game_type' => 'required|in:game1,game2,game3,game4',
            'score' => 'required|integer'
        ]);

        // Prevent duplicate submissions from page refreshes
        if ($request->session()->has('last_submitted_score')) {
            return redirect()->route('minigames.results');
        }

        $record = MinigameRecord::create([
            'user_id' => auth()->id(),
            'game_type' => $validated['game_type'],
            'score' => $validated['score']
        ]);

        // Get rank and total players (higher score is better)
        $rank = MinigameRecord::where('game_type', $validated['game_type'])
            ->where('score', '>', $validated['score'])
            ->distinct('user_id')
            ->count() + 1;

        $totalPlayers = MinigameRecord::where('game_type', $validated['game_type'])
            ->distinct('user_id')
            ->count();

        // Get top 10 scores (ordered by highest score first)
        $topScores = MinigameRecord::with('user')
            ->where('game_type', $validated['game_type'])
            ->orderBy('score', 'desc')
            ->take(10)
            ->get();

        // Store the score data in session to handle refreshes
        $scoreData = [
            'score' => $validated['score'],
            'rank' => $rank,
            'totalPlayers' => $totalPlayers,
            'topScores' => $topScores,
            'isWorldRecord' => $record->isWorldRecord(),
            'game_type' => $validated['game_type']
        ];
        $request->session()->put('last_submitted_score', $scoreData);

        return redirect()->route('minigames.results');
    }

    public function results(Request $request)
    {
        if (!$request->session()->has('last_submitted_score')) {
            return redirect()->route('minigames.game1.intro');
        }

        return view('minigames.results', $request->session()->get('last_submitted_score'));
    }
}
