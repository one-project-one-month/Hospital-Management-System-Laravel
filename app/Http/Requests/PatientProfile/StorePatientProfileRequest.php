<?php

namespace App\Http\Requests\PatientProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePatientProfileRequest extends FormRequest
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
            'date_of_birth'=>'required|date',
            'gender'=>'required|in:male,female',
            'phone'=>'nullable|string|max:13',
            'address'=>'required|string|max:255',
            'relation'=>'required|string|max:20',
            'blood_type'=>'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'validation_error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
