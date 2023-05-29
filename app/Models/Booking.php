<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    //constant definition
    const IS_ACTIVE = 1;
    const IS_CANCELLED = 0;

    protected $fillable = [
       'user_id',
       'travel_option_id',
       'from',
       'to',
       'phone',
       'booking_email',
       'departure_date',
       'arrival_date',
       'num_guest',
       'payment_status',
       'booking_status',
       'amount'
    ];

    protected $hidden = [
        'created_at', 
        'updated_at',
    ];

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function temp_payment(): HasOne
    {
        return $this->hasOne(TempPayment::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function travelOptions(): BelongsTo
    {
        return $this->belongsTo(TravelOption::class, 'travel_option_id');
    }

    protected function setAmountAttribute($value)
    {
        if ($value != 0) {
            $this->attributes['amount'] = $value / 100;
        }else{
            $this->attributes['amount'] = 0;
        }
    }

    protected function getAmountAttribute(){
        return $this->attributes['amount'] * 100;
    }
}
