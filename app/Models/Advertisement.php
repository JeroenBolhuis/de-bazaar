<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function relatedAdvertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_related', 'advertisement_id', 'related_advertisement_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function rentalPeriods()
    {
        return $this->hasMany(RentalPeriod::class);
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }


    // Relation: Advertisement favorited by users
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
