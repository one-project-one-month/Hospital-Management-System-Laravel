<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException (response()->json([
            'status' => 'error',
            'status_code' => '422',
            'message' => 'Validation Error',
            'data' => $validator->errors()
        ]));
    }
}
