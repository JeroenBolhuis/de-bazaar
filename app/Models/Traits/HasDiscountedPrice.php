<?php

namespace App\Models\Traits;

trait HasDiscountedPrice
{
    public function getFinalPriceAttribute()
    {
        return $this->original_price * (1 - $this->discount_percentage / 100);
    }
} 