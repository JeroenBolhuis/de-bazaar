<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Get the advertisements associated with the business.
     */
    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }
} 