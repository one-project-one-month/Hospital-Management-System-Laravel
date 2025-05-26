<?php

namespace App\Http\Requests\PatientProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\HttpResponse;

class StorePatientProfileRequest extends FormRequest
{
    use HttpResponse;
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
            'name'=>'required|string|max:255',
            'age'=>'required|integer',
            'date_of_birth'=>'required|date',
            'gender'=>'required|in:male,female',
            'phone'=>'nullable|integer|max:13',
            'address'=>'required|string|max:255',
            'relation'=>'required|string|max:20',
            'blood_type'=>'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(HttpResponse::fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
