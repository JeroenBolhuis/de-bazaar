<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'type',
        'auction_start_date',
        'auction_end_date',
        'condition',
        'wear_per_day',
        'image',
        'is_active',
    ];

    protected $casts = [
        'auction_start_date' => 'datetime',
        'auction_end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isAuctionActive()
    {
        if ($this->type !== 'auction') {
            return false;
        }

        $now = now();
        return $now->between($this->auction_start_date, $this->auction_end_date);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(AdvertisementReview::class);
    }

    public function relatedAdvertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_related', 'advertisement_id', 'related_advertisement_id');
    }

    public function bids()
    {
        return $this->hasMany(AuctionBidding::class);
    }

    public function rentalPeriods()
    {
        return $this->hasMany(RentalPeriod::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function highestBidOrPrice()
    {
        return $this->hasOne(AuctionBidding::class)->orderBy('amount', 'desc')->withDefault(function ($bidding) {
            $bidding->amount = $this->price;
        });
    }
}
