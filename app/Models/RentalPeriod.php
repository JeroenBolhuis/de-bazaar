<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalPeriod extends Model
{
    protected $fillable = [
        'advertisement_id',
        'user_id',
        'start_date',
        'end_date',
        'return_image',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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