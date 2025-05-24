<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\HttpResponse;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
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
            'name'=>'required|string|max:255',
            'email'=>'required|email|'.Rule::unique('users', 'email')->ignore($this->route('id')),
            'password'=>'required|min:6',
            'specialty'=>'json',
            'specialty.*' => 'string',
            'license_number'=>'required|string|max:255',
            'education'=>'required|string|max:255',
            'experience_years'=>'required',
            'biography'=>'nullable',
            'phone'=>'nullable|string|max:255',
            'address'=>'nullable|string',
            'availability' => 'array',
            'availability.Mon' => 'array',
            'availability.Wed' => 'array',
            'availability.Fri' => 'array',
            'availability.*.*' => 'string|regex:/^\d{2}:\d{2}$/',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(HttpResponse::fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
