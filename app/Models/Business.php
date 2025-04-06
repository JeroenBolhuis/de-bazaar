<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kvk_number',
        'vat_number',
        'domain',
        'theme_settings',
    ];

    protected $casts = [
        'theme_settings' => 'json',
        'is_active' => 'boolean',
    ];

    public function components()
    {
        return $this->hasMany(BusinessComponent::class)->orderBy('order');
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
