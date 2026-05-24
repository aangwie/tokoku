<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'code',
        'name',
    ];

    /**
     * Get the city that owns this district
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all villages for this district
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
