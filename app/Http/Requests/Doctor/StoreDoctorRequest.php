<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
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
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'speciality'=>'json',
            'license_number'=>'required|string|max:255',
            'education'=>'required|string|max:255',
            'experience_years'=>'required',
            'biography'=>'nullable',
            'phone'=>'nullable|numeric',
            'address'=>'nullable|numeric'


        ];
    }
}
