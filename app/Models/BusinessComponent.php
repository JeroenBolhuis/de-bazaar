<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessComponent extends Model
{
    protected $fillable = [
        'business_id',
        'type',
        'order',
        'content',
        'image_path',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
