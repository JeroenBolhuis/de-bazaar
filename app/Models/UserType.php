<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'can_advertise',
        'is_business',
        'max_listings',
        'max_rental_listings',
        'max_auction_listings',
    ];

    protected $casts = [
        'can_advertise' => 'boolean',
        'is_business' => 'boolean',
        'max_listings' => 'integer',
        'max_rental_listings' => 'integer',
        'max_auction_listings' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
} 