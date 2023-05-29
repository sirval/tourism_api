<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvailableTravelOption extends Model
{
    protected $fillable = [
        'travel_option_id',
        'date',
        'location',
        'min_price_range',
        'max_price_range',
        'type',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function travelOption() : BelongsTo
    {
        return $this->belongsTo(TravelOption::class);
    }

    protected function setMinPriceRangeAttribute($value)
    {
        if ($value != 0) {
            $this->attributes['min_price_range'] = $value / 100;
        }else{
            $this->attributes['min_price_range'] = 0;
        }
    }

    protected function setMaxPriceRangeAttribute($value)
    {
        if ($value != 0) {
            $this->attributes['max_price_range'] = $value / 100;
        }else{
            $this->attributes['max_price_range'] = 0;
        }
    }

    protected function getMinPriceRangeAttribute(){
        return $this->attributes['min_price_range'] * 100;
    }

    protected function getMaxPriceRangeAttribute(){
        return $this->attributes['max_price_range'] * 100;
    }



}
