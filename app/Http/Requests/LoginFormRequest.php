<?php

namespace App\Http\Requests;

use App\Traits\ApiResponsesTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
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
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:6'],
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
            'email.required' => 'Email is required!',
            'password.required' => 'Password is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator->errors(), 422);
    }
}
