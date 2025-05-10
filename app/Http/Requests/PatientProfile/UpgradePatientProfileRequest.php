<?php

namespace App\Http\Requests\PatientProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpgradePatientProfileRequest extends FormRequest
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
            'date_of_birth' => "required|date",
            'patient_name' => "required|string|max:255",
            'gender' => "required|string|max:255|in:male,female,other",
            'phone' => "required|string|max:255",
            'address' => "required|string|max:2000",
            'relation' => "required|string|max:255|in:father,mother,son,self,daughter,other",
            'blood_type' => "required|string|max:255|in:A+,A-,B+,B-,AB+,AB-,O+,O-",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'status' => 'error',
            'status_code' => 422,
            'message' => 'Validation Error',
            'data' => $validator->errors()
        ], 422 ));
    }
}
