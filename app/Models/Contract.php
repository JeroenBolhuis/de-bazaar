<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users who have accepted this contract.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_contracts')
                    ->withTimestamps();
    }

    /**
     * Check if a user has accepted this contract.
     */
    public function isAcceptedByUser(?User $user): bool
    {
        if (!$user) return false;
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Get all active contracts that a user hasn't accepted yet.
     */
    public static function getUnsignedActiveContracts(?User $user)
    {
        if (!$user) return collect();
        
        return static::where('is_active', true)
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('contract_id')
                    ->from('user_contracts')
                    ->where('user_id', $user->id);
            })
            ->get();
    }
}