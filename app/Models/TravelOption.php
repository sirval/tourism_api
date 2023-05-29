<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelOption extends Model
{

    protected $fillable = [
        'option',
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
    public function availableTravelOption() : HasMany
    {
        return $this->hasMany(AvailableTravelOption::class);
    }

    public function booking() : HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
