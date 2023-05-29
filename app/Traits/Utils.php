<?php

namespace App\Traits;

use App\Models\TempPayment;
use Illuminate\Support\Str;

trait Utils{

    public function generateTxRef(): string
    {
        $tx_ref = 'TR_'. Str::uuid()->toString();
        //check if tx_ref exists
        $is_used = TempPayment::where('tx_ref', $tx_ref)->first('tx_ref');
        if($is_used !== $tx_ref){
            return $tx_ref;
        }else{
            return 'TRX_'. Str::uuid()->toString();
        }
    }
}