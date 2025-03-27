<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    protected $fillable = [
        'reviewer_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
