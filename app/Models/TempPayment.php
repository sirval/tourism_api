<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempPayment extends Model
{

    protected $fillable = [
        'booking_id',
        'travel_options_id',
        'fullname',
        'phone',
        'email',
        'tx_ref',
        'amount_paid',
        'status',
    ];

    
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
