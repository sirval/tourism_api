<?php

namespace App\Http\Requests;

use App\Traits\ApiResponsesTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
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
            'name'      => ['required', 'string'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'confirmed', 'string', 'min:6'],
            'role_id'   => ['required', 'numeric'],
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
            'name.required'     => 'Name is required',
            'role_id.required'  => 'User role is required',
            'email.required'    => 'Email is required!',
            'password.required' => 'Password is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator->errors(), 422);
    }
}
