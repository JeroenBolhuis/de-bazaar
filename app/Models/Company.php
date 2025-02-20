<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'logo_path',
        'description',
        'theme_settings',
        'is_active',
    ];

    protected $casts = [
        'theme_settings' => 'json',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        return $this->hasMany(CompanySetting::class);
    }

    public function getSettingValue($key, $default = null)
    {
        $setting = $this->settings()->where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'boolean':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $setting->value;
            case 'float':
                return (float) $setting->value;
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }
} 