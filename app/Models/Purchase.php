<?php

namespace App\Models;

use App\Models\Traits\HasDiscountedPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory, HasDiscountedPrice;

    protected $fillable = [
        'user_id',
        'advertisement_id',
        'purchase_date',
        'discount_percentage',
        'original_price'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'discount_percentage' => 'float',
        'original_price' => 'float',
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
