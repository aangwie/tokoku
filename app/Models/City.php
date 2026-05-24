<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'code',
        'name',
    ];

    /**
     * Get the province that owns this city
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get all districts for this city
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
