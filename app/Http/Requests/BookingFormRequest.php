<?php

namespace App\Http\Requests;

use App\Traits\ApiResponsesTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BookingFormRequest extends FormRequest
{
    use ApiResponsesTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id'           => ['required', 'numeric'],
            'travel_option_id'  => ['required', 'numeric'],
            'from'              => ['required', 'string'],
            'to'                => ['required', 'string'], 
            'phone'             => ['string'],
            'booking_email'     => ['required', 'email'],
            'departure_date'    => ['required', 'date_format:YY-MM-DD'],
            'arrival_date'      => ['date_format:YY-MM-DD'],
            'num_guest'         => ['required', 'numeric'],
            'amount'            => ['required'],
        ];
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'travel_option_id.required'   => 'Travel Optiion ID is required!',
            'travel_option_id.numeric'    => 'Travel Optiion ID must be a numeric!',
            'user_id.required'            => 'User ID is required!',
            'user_id.numeric'             => 'User ID must be a numeric!',
            'departure_date.required'     => 'Departure Date is required',
            'departure_date.date_format'  => 'Departure Date must be in the format YY-MM-DD',
            'arrival_date.date_format'    => 'Arrivaal Date must be in the format YY-MM-DD',
            'num_guest.required'          => 'Number of guest is required',
            'num_guest.numeric'           => 'Number of guest must be a numeric value',
            'booking_email.required'      => 'Booking Email is required',
            'booking_email.email'         => 'Booking Email must be a valid email',
            'amount.required'             => 'Amount is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator->errors(), 422);
    }
}
