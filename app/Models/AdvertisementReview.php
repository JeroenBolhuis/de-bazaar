<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementReview extends Model
{
    protected $fillable = [
        'reviewer_id',
        'advertisement_id',
        'rating',
        'comment',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
