<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        'user_type_id',
        'company_name',
        'kvk_number',
        'vat_number',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'language',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'contract_approved' => 'boolean',
            'contract_approved_at' => 'datetime',
        ];
    }

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
        return $this->hasMany(Review::class);
    }


    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('slug', $role);
    }

    public function isBusinessUser()
    {
        return $this->userType && $this->userType->is_business;
    }

    public function canAdvertise()
    {
        return $this->userType && $this->userType->can_advertise;
    }
}
