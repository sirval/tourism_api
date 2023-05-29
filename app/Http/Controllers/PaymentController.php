<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $service;

    /**
    *
    * @return void
    */
   public function __construct(PaymentService $service) 
   {
        $this->middleware(['auth:api', 'acl:user'], ['except' => ['handlePaymentCallback']]);
        $this->service = $service;
   } 

   public function pay(Request $request)
   {
        return response()->json($this->service->makePayment($request));
   }

   public function handlePaymentCallback(Request $request)
   {
        return response()->json($this->service->handlePaymentCallback($request));
   }
}
