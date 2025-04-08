<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    public const TYPE_OPTIONS = [
        'about',
        'contact',
        'featured_advertisements',
        'image',
        'hero',
    ];

    protected $fillable = [
        'business_id',
        'type',
        'order',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Validation rules for the model.
     */
    public static function rules(): array
    {
        return [
            'type' => 'required|string|in:' . implode(',', array_keys(self::TYPE_OPTIONS)),
            'order' => 'required|integer|min:0',
        ];
    }

    /**
     * Get the business that owns the component.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
} 