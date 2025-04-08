<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessComponent extends Model
{
    protected $fillable = [
        'business_id',
        'component_id',
        'order',
        'title',
        'content'
    ];

    /**
     * Get the business that owns the component.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the component type.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
} 