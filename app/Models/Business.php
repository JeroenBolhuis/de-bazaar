<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kvk_number',
        'vat_number',
        'domain',
        'custom_url',
        'theme_settings',
    ];

    protected $casts = [
        'theme_settings' => 'array',
    ];

    /**
     * Get the users associated with the business.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the components associated with the business.
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'business_components')
            ->withPivot(['id', 'title', 'content', 'order'])
            ->orderBy('business_components.order');
    }

    /**
     * Get all the advertisements from all users associated with the business.
     */
    public function advertisements(): HasManyThrough
    {
        return $this->hasManyThrough(Advertisement::class, User::class);
    }
} 