<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinigameRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_type',
        'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isWorldRecord(): bool
    {
        $highestScore = static::where('game_type', $this->game_type)
            ->where(function($query) {
                $query->where('score', '>', $this->score)
                    ->orWhere(function($q) {
                        $q->where('score', '=', $this->score)
                            ->where('created_at', '<', $this->created_at);
                    });
            })
            ->exists();

        return !$highestScore;
    }

    public static function getWorldRecords()
    {
        return static::whereIn('id', function($query) {
            $query->selectRaw('MAX(id)')
                ->from('minigame_records')
                ->groupBy('game_type')
                ->whereIn('id', function($subquery) {
                    $subquery->selectRaw('MAX(id)')
                        ->from('minigame_records')
                        ->whereIn('score', function($scoreQuery) {
                            $scoreQuery->selectRaw('MAX(score)')
                                ->from('minigame_records')
                                ->groupBy('game_type');
                        })
                        ->groupBy('game_type');
                });
        })->get();
    }
}
