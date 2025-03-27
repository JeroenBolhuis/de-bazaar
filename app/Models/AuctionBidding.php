<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionBidding extends Model
{
    protected $fillable = [
        'user_id',
        'advertisement_id',
        'amount',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }    
}
