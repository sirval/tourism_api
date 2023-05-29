<?php

namespace App\Http\Requests;

use App\Traits\ApiResponsesTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdminOptionRequestForm extends FormRequest
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
            'travel_option_id'  => ['required', 'numeric'],
            'date'              => ['required', 'date_format:YY-MM-DD'],
            'location'          => ['required', 'string'],
            'min_price_range'   => ['required'],
            'max_price_range'   => ['required'],
            'type'              => ['required', 'string'],
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
            'travel_option_id.required' => 'Travel Optiion ID is required!',
            'travel_option_id.numeric'  => 'Travel Optiion ID must be a numeric!',
            'date.required'             => 'Date is required',
            'date.date_format'          => 'Date must be in the format YY-MM-DD',
            'location.required'         => 'Location is required',
            'location.string'           => 'Location must be a string value',
            'type.required'             => 'Type is required',
            'type.string'               => 'Type must be a string value',
            'min_price_range.required'  => 'Minimum price range is required',
            'max_price_range.required'  => 'Maximum price range is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator->errors(), 422);
    }
}
