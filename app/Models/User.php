<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'business_id',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function favorites()
    {
        return $this->belongsToMany(Advertisement::class, 'favorites')->withTimestamps();
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function reviews()
    {
        return $this->hasMany(UserReview::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getCanSellAttribute()
    {
        return ($this->role === 'seller' || $this->role === 'business') || $this->isAdmin();
    }

    public function isBusiness()
    {
        return $this->role === 'business';
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function minigameRecords()
    {
        return $this->hasMany(MinigameRecord::class);
    }

    public function getMinigameDiscountPercentage()
    {
        // Get all world records
        $worldRecords = MinigameRecord::getWorldRecords();
        
        // Count how many of those world records belong to this user
        $userWorldRecordCount = $worldRecords->where('user_id', $this->id)->count();
            
        return $userWorldRecordCount * 20; // 20% discount per world record
    }

    /**
     * Get the contracts accepted by the user.
     */
    public function contracts()
    {
        return $this->belongsToMany(Contract::class, 'user_contracts')
                    ->withTimestamps();
    }
}
