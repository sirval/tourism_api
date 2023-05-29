<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\TempPayment;
use App\Traits\ApiResponsesTrait;
use App\Traits\Utils;
use GuzzleHttp\Client;

class PaymentService
{
    use ApiResponsesTrait;
    use Utils;
    // protected $paystackUrl = config('services.paystack.baseurl');

    public function makePayment($request)
    {
       try {
            $query = Booking::query();
            $booking = $query->with('travelOptions')->where('id', $request['booking_id'])->first();
        
            if (! $booking ) {
                return $this->errorResponse('Booking detail with specified ID not found', 404, false);
            }
            $arr_booking = $booking->toArray();
        
            // Prepare the request data
            $tx_ref = $this->generateTxRef();
            $data = [
                'amount' => (int) $arr_booking['amount'] * 100, 
                'email' => $arr_booking['booking_email'], 
                'currency' => 'NGN', 
                'reference' => $tx_ref, 
                'callback_url' => config('services.paystack.testhook'), 
                'metadata' => [
                    'custom_fields' => [
                        [
                            'fullname' => auth()->user()->name,
                            'value' => "Payment for". $arr_booking['travel_options']['option'] .'booking'
                        ]
                    ]
                ]
            ];

            // save to temp_payment table incase of failure
            $payment_data = new TempPayment;
            $payment_data->booking_id           = $arr_booking['id'];
            $payment_data->travel_options_id    = $arr_booking['travel_option_id'];
            $payment_data->fullname             = auth()->user()->name;
            $payment_data->phone                = $arr_booking['phone'];
            $payment_data->email                = $arr_booking['booking_email'];
            $payment_data->tx_ref               = $tx_ref;
            $payment_data->amount_paid          = $arr_booking['amount'];
            $payment_data->status               = 0;

            $payment_data->save();

            $client = new Client();
            $response = $client->request('POST', config('services.paystack.baseurl') . '/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.pubtestsecret'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Return the authorization URL to the client
            return $this->successResponse(['authorization_url' => $responseData['data']['authorization_url']], 'Payment detail successfully sent to paystack. Use the url to authorize payment from your browser');

       } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
       }
    }

    public function handlePaymentCallback($request)
    {
        try {
            // Get the payment reference from the query string
            $reference = $request->input('reference');

            // Make a GET request to Paystack's verify transaction endpoint
            $client = new Client();
            $response = $client->request('GET', config('services.paystack.baseurl') . '/transaction/verify/' . $reference, [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.paystack.pubtestsecret'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($responseData['data']['status'] === 'success') {
                // Process payment details and update database
                $temp_payment_data =  TempPayment::where('tx_ref', $reference)->first();
            
                //update temp payment status
                $temp_payment_data->status = 1;
                $temp_payment_data->save();
                $arr_booking = $temp_payment_data->toArray();
                //update central ledger
                $payment_data = new Payment; 
                $payment_data->booking_id           = $arr_booking['booking_id'];
                $payment_data->travel_options_id    = $arr_booking['travel_options_id'];
                $payment_data->fullname             = $arr_booking['fullname'];
                $payment_data->phone                = $arr_booking['phone'];
                $payment_data->email                = $arr_booking['email'];
                $payment_data->tx_ref               = $reference;
                $payment_data->amount_paid          = $arr_booking['amount_paid'];
                $payment_data->status               = 1;
                $payment_data->save();

                return $this->successResponse([], 'Payment successfull');
            } else {
                return $this->errorResponse('Payment Failed', 500);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    
}
