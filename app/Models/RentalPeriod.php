<?php

namespace App\Models;

use App\Models\Traits\HasDiscountedPrice;
use Illuminate\Database\Eloquent\Model;

class RentalPeriod extends Model
{
    use HasDiscountedPrice;

    protected $fillable = [
        'advertisement_id',
        'user_id',
        'start_date',
        'end_date',
        'return_image',
        'discount_percentage',
        'original_price'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_percentage' => 'float',
        'original_price' => 'float',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 